<?php


namespace Module\Dokani\Services;


use Module\Dokani\Models\Sale;
use Module\Dokani\Models\Order;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\SaleDetail;
use Module\Dokani\Models\OrderDetail;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\CustomerRefer;
use Module\Dokani\Models\PurchaseOrder;
use Module\Dokani\Models\PointConfigure;

class PurchaseOrderService
{

    public $order;
    public $sale;

    public function store($request)
    {
        $this->order = PurchaseOrder::create([
            'supplier_id'       => $request->supplier_id,
            // 'invoice_no'        => "#PO-" . str_pad(rand(1,1000), 5, '0', 0),
            'payable_amount'    => $request->payable_amount,
            'previous_due'      => $request->previous_due ?? 0,
            'discount'          => $request->discount,
            'paid_amount'       => $request->paid_amount ?? 0,
            'due_amount'        => $request->due_amount ?? 0,
            'date'              => date('Y-m-d'),
        ]);
        if($this->order){
            $purchaseOrder = sprintf("%06d", $this->order->id);
            PurchaseOrder::find($this->order->id)->update([
                'invoice_no'    => 'PO-' . date('y') . $purchaseOrder,
            ]);
        }
        return $this->order;

    }

    public function orderDetails($request)
    {
        foreach ($request->product_ids ?? [] as $key => $product_id) {

            $details = $this->order->purchsase_order_details()->create([
                'product_id'    => $product_id,
                'price'         => $request->product_price[$key] ?? 0,
                'quantity'      => $request->product_qty[$key],
                'total_amount'  => $request->subtotal[$key],
                'expire_at'     => $request->expire_at[$key],
            ]);

        }

    }


    public function saleStore($request)
    {

        $total_price = $request->payable_amount + $request->total_vat ;
        //Point
        $sale_point = PointConfigure::where('start_price', '<=', $total_price)
            ->where('end_price', '>=', $total_price)
            ->first();
        $new_point = ($request->customer_point + $sale_point->point) ?? 0;

        // Refer
        $customer_refer = CustomerRefer::where('start_price', '<=', $total_price)
            ->where('end_price', '>=', $total_price)
            ->first();
        $refer_value = optional($customer_refer)->refer_point/100 * $total_price;

        isset($request->is_cod) ? $is_cod = 1 : $is_cod = 0 ;

        $this->sale = Sale::create([
            'customer_id'       => $request->customer_id,
            'refer_customer_id' => $request->refer_customer_id,
            'refer_id'          => optional($customer_refer)->id ?? null,
            'account_type_id'   => $request->account_type_id,
            'courier_id'        => $request->courier_id ?? null,
            'invoice_no'        => "#S-" . str_pad(rand(1,1000), 5, '0', 0),
            'payable_amount'    => $request->payable_amount,
            'previous_due'      => $request->previous_due,
            'discount'          => $request->discount,
            'paid_amount'       => $request->paid_amount ?? 0,
            'delivery_charge'   => $request->delivery_charge ?? 0,
            'due_amount'        => $request->due_amount,
            'change_amount'     => $request->change_amount,
            'total_vat'         => $request->total_vat ?? 0,
            'sales_by'          => auth()->id(),
            'point'             => 0-$request->point,
            'point_id'          => optional($sale_point)->id ?? null,
            'is_cod'            => $is_cod,
            'source'            => $request->source,
            'date'              => date('Y-m-d'),
        ]);

        if (($request->paid_amount ?? 0) > 0) {
            $this->transaction();
        }
        $this->customerDue($request, $refer_value);

        $this->customerPoint($request, $new_point);

        $this->saleAccountBalance($request);

    }

    public function saleDetails($request)
    {

        foreach ($request->product_ids ?? [] as $key => $product_id) {

            try {
                $details = $this->sale->sale_details()->create([
                    'product_id'    => $product_id,
                    'price'         => $request->product_price[$key] ?? 0,
                    'vat'           => $request->product_vat[$key] ?? 0,
                    'description'   => $request->description[$key],
                    'quantity'      => $request->product_qty[$key],
                    'total_amount'  => $request->subtotal[$key],
                ]);
            }catch (\Throwable $th){

                dd($th->getMessage());
            }


            $this->stockUpdateOrCreate($product_id, $request->product_qty[$key]);

            (new ProductLedgerService())->storeLedger(
                $product_id,
                $details->id,
                'Sale Details',
                'Out',
                $request->product_qty[$key]
            );
        }
    }


    public function stockUpdateOrCreate($product_id, $sold_qty, $destroy = null)
    {
//        $product_stock = ProductStock::where('product_id', $product_id)->dokani()->first();
        $product_stock = ProductStock::where('product_id', $product_id)->first();
        if ($product_stock) {
            if ($destroy) {
                $product_stock->increment('available_quantity', $sold_qty);
                $product_stock->decrement('sold_quantity', $sold_qty);
            } else {
                $product_stock->increment('sold_quantity', $sold_qty);
                $product_stock->decrement('available_quantity', $sold_qty);
            }
        } else {
            $this->product->stocks()->create([
                'opening_quantity'          => $sold_qty,
                'available_quantity'        => $available_qty ?? 0,
                'purchased_quantity'        => 0,
                'sold_quantity'             => $sold_qty,
                'wastage_quantity'          => 0,
                'sold_return_quantity'      => 0,
                'purchase_return_quantity'  => 0,
            ]);
        }
    }


    public function transaction()
    {
        (new CashFlowService())->transaction(
            $this->sale->id,
            'Sale',
            $this->sale->paid_amount,
            'In',
            'Sale Create'
        );
    }




    public function orderDelete($id)
    {
        $order     = Order::find($id);

        if ($order) {

            $order_details = OrderDetail::where('order_id', $order->id)->get();

            foreach ($order_details as $key => $item) {

                $item->delete();
            }
        }

        $order->delete();
    }

    public function customerDue($request, $refer_value)
    {


        if ($request->due_amount > 0) {
            (new LedgerService())->customerLedger($request->customer_id, $this->sale->id, 'Sale', $request->due_amount, 'Out',$request->account_type_id);
        }
        if (isset($request->refer_customer_id)){

            (new LedgerService())->customerReferBalance($request->refer_customer_id, $this->sale->id, 'Refer Sale', $refer_value, 'Out');
        }
    }


    public function saleAccountBalance($request){

        (new AccountService())->increaseBalance($request->account_type_id, $request->paid_amount);
    }


    public function customerPoint($request, $point){

        Customer::updateOrCreate([
            'id'           => $request->customer_id,
        ],[
            'point'        => $point,
        ]);
    }

}
