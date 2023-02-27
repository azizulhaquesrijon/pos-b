<?php

namespace Module\Dokani\Services;

use Carbon\Carbon;
use Module\Dokani\Models\Sale;
use Module\Dokani\Models\Order;
use Module\Dokani\Models\Product;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\SaleDetail;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\CustomerRefer;
use Module\Dokani\Models\PurchaseDetail;
use Module\Dokani\Models\PointConfigure;
use Module\Dokani\Models\CustomerLedger;
use Module\Dokani\Models\MultiAccountPay;
use Module\Dokani\Models\ProductStockLog;

class SaleService
{

    public $sale;
    public $sold_qty;
    public $product_qry;

    //Testing Sale Method
    public function againSale(){

        $sales = Sale::dokani()->with('sale_details')->get();

        foreach ($sales as $sale){


            foreach ($sale->sale_details ?? [] as $detail) {

                $product_stocks = ProductStock::dokani()->where('product_id', $detail->product_id)->get();

                $lotQtyArr = [];

                $getLotNumbers = $this->getLotNumbers($detail->product_id);


                foreach($getLotNumbers as $lotNumber) {

                    $availableQty = $this->checkAvailableQuantity($detail->product_id, $lotNumber->lot);

                    if ($availableQty > 0) {

                        $leftQty = $detail->quantity - array_sum($lotQtyArr);

                        $purchaseDetail = PurchaseDetail::where(['product_id' => $detail->product_id, 'lot' => $lotNumber->lot])->first();

                        $quantity = $lotNumber->available_quantity;

                        if ($leftQty <= $lotNumber->available_quantity) {
                            $quantity = $leftQty;
                        }

                        if ($detail->quantity > array_sum($lotQtyArr)) {


                            (new ProductStockService())->stockUpdateOrCreate(
                                $detail->product_id,
                                $purchaseDetail->expiry_at ?? null,
                                $lotNumber->lot,
                                'sale',
                                $purchaseDetail->quantity ?? 0,
                                $quantity);

                            (new ProductStockLogService())->stockLog(
                                $detail->id,
                                'Sale',
                                $detail->product_id,
                                $lotNumber->lot,
                                $purchaseDetail->expiry_at ?? null,
                                'Out',
                                $quantity,
                                -abs($quantity),
                                $detail->buy_price,
                                $detail->price,
                                Carbon::parse($detail->created_at)->format('Y-m-d')
                            );

                            (new ProductLedgerService())->storeLedger(
                                $detail->product_id,
                                $detail->id,
                                'Sale',
                                'Out',
                                $detail->quantity
                            );
                        }

                        array_push($lotQtyArr, $quantity);

                    }

                }
            }
        }
    }







    /*
     |--------------------------------------------------------------------------
     |          SALE  STORE   METHOD
     |--------------------------------------------------------------------------
    */
    public function store($request)
    {

        //Point
        $total_price = $request->payable_amount + $request->total_vat ;

        $sale_point = PointConfigure::where('start_price', '<=', $total_price)
            ->where('end_price', '>=', $total_price)
            ->first();

        $new_point = ($request->customer_point + optional($sale_point)->point) ?? 0;

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
            'branch_id'         => $request->branch_id ?? null,
            'account_amount'    => $request->payable_amount,
            'note'              => $request->note,
            'courier_id'        => $request->courier_id ?? null,
            // 'invoice_no'        => "#S-" . ,
            'payable_amount'    => $request->payable_amount,
            'sub_total'         => $request->sub_total,
            'previous_due'      => $request->previous_due,
            'discount'          => $request->discount,
            'discount_type'     => $request->sale_discount_type,
            'paid_amount'       => $request->paid_amount ?? 0,
            'delivery_charge'   => $request->delivery_charge ?? 0,
            'due_amount'        => $request->due_amount,
            'change_amount'     => $request->change_amount,
            'total_vat'         => $request->total_vat ?? 0,
            'sales_by'          => auth()->id(),
            'employee_id'       => $request->employee_id,
            'is_cod'            => $is_cod,
            'point'             => -abs($request->point) ?? 0,
            'point_id'          => optional($sale_point)->id ?? null,
            'source'            => $request->source,
            'date'              => date('Y-m-d'),
            'sale_from'         => $request->sale_from,
        ]);

        if($this->sale){
            $sale_id = sprintf("%06d", $this->sale->id);
            Sale::find($this->sale->id)->update([
                'invoice_no'    => $request->inv_prefix . '-' . date('y') . $sale_id,
            ]);
        }


        if (($request->paid_amount ?? 0) > 0) {

            $this->transaction($request);
        }


        $this->customerDue($request);

        $this->customerRefer($request, $refer_value);

        $this->customerPoint($request, $new_point);

        $this->saleAccountBalance($request);

        return $this->sale;

    }


    public function saleEdit($request,$id)
    {

        //Point
        $total_price = $request->payable_amount + $request->total_vat ;

        $sale_point = PointConfigure::where('start_price', '<=', $total_price)
            ->where('end_price', '>=', $total_price)
            ->first();

        //        $new_point = ($request->customer_point + optional($sale_point)->point) ?? 0;

        // Refer
        $customer_refer = CustomerRefer::where('start_price', '<=', $total_price)
            ->where('end_price', '>=', $total_price)
            ->first();

        $refer_value = optional($customer_refer)->refer_point/100 * $total_price;

        isset($request->is_cod) ? $is_cod = 1 : $is_cod = 0 ;


        $this->sale = Sale::updateOrCreate(
            [
                'id'            => $id,
            ],
            [
                'customer_id'       => $request->customer_id,
                'refer_customer_id' => $request->refer_customer_id,
                'refer_id'          => optional($customer_refer)->id ?? null,
                'account_id'        => $request->account_id,
                'note'              => $request->note,
                'courier_id'        => $request->courier_id ?? null,
                'payable_amount'    => $request->payable_amount,
                'previous_due'      => $request->previous_due,
                'discount'          => $request->discount,
                'discount_type'     => $request->sale_discount_type,
                'paid_amount'       => $request->paid_amount ?? 0,
                'delivery_charge'   => $request->delivery_charge ?? 0,
                'due_amount'        => $request->due_amount,
                'change_amount'     => $request->change_amount,
                'total_vat'         => $request->total_vat ?? 0,
                'sales_by'          => auth()->id(),
                'is_cod'            => $is_cod,
                'point_id'          => optional($sale_point)->id ?? null,
                'source'            => $request->source,
                'date'              => date('Y-m-d'),
        ]);

        if (($request->paid_amount ?? 0) > 0) {

            // $this->transaction();
        }

            //        $this->customerDue($request, $refer_value);

            //        $this->customerPoint($request, $new_point);

            //        $this->saleAccountBalance($request);

        return $this->sale;

    }




    /*
     |--------------------------------------------------------------------------
     |          SALE  DETAILS  METHOD
     |--------------------------------------------------------------------------
    */
    public function saleDetails($request)
    {
        foreach ($request->product_ids ?? [] as $key => $product_id) {

            $details = $this->sale->sale_details()->create([
                'product_id'    => $product_id,
                'price'         => $request->product_price[$key] ?? 0,
                'vat'           => $request->product_vat[$key] ?? 0,
                'description'   => $request->description[$key] ?? null,
                'discount'      => $request->product_discount[$key] ?? 0,
                'discount_type' => $request->discount_type[$key] ?? null,
                'quantity'      => $request->product_qty[$key],
                'total_amount'  => $request->subtotal[$key],
            ]);

            $product_stocks = ProductStock::dokani()->where('product_id', $product_id)->get();

            $lotQtyArr = [];


            $getLotNumbers = $this->getLotNumbers($product_id);


            foreach($getLotNumbers as $lotNumber) {

                $availableQty = $this->checkAvailableQuantity($product_id, $lotNumber->lot);

                if ($availableQty > 0) {

                    $leftQty = $details->quantity - array_sum($lotQtyArr);

                    $purchaseDetail = PurchaseDetail::where(['product_id' => $product_id, 'lot' => $lotNumber->lot])->first();
                    $product = Product::where('id',$product_id)->where('opening_stock','>',0)->first();
                    $details->update([
                        'buy_price'  => $purchaseDetail->price ?? $product->purchase_price
                    ]);

                    $quantity = $lotNumber->available_quantity;

                    if ($leftQty <= $lotNumber->available_quantity) {
                        $quantity = $leftQty;
                    }

                    if ($details->quantity > array_sum($lotQtyArr)) {

                        (new ProductStockService())->stockUpdateOrCreate(
                            $product_id,
                            $purchaseDetail->expiry_at ?? null,
                            $lotNumber->lot,
                            'sale',
                            $purchaseDetail->quantity ?? 0,
                            $quantity);

                        (new ProductStockLogService())->stockLog(
                            $details->id,
                            'Sale',
                            $product_id,
                            $lotNumber->lot,
                            $purchaseDetail->expiry_at ?? null,
                            'Out',
                            $quantity,
                            -abs($quantity),
                            $details->buy_price,
                            $request->product_price[$key]
                        );

                        (new ProductLedgerService())->storeLedger(
                            $product_id,
                            $details->id,
                            'Sale',
                            'Out',
                            $details->quantity
                        );
                    }

                    array_push($lotQtyArr, $quantity);

                }

            }

            //            $this->stockUpdateOrCreate($product_id, $request->product_qty[$key]);

        }



    }



    public function getLotNo($product_id,$qty)
    {
        return  ProductStock::dokani()
            ->where([
                'product_id'            => $product_id,
            ])
            ->where('available_quantity', '>=', $qty)
            ->orderBy('id', 'ASC')
            ->first();
    }



    public function checkAvailableQuantity($product_id, $lot)
    {
        return  ProductStock::dokani()
            ->where([

                'product_id'            => $product_id,
                'lot'                   => $lot,
            ])
            ->orderBy('id', 'ASC')
            ->sum('available_quantity');
    }


    public function getLotNumbers($product_id)
    {
        return  ProductStock::dokani()
            ->where([
                'product_id'            => $product_id,
            ])
            ->orderBy('id', 'ASC')
            ->get();
    }






    public function saleDetailsEdit($request, $id)
    {

dd($request->all());
        foreach ($request->product_ids ?? [] as $key => $product_id) {

            $details = $this->sale->sale_details()->create(
                [
                'product_id'    => $product_id,
                'price'         => $request->product_price[$key] ?? 0,
                'buy_price'     => $request->buy_price[$key] ?? 0,
                'vat'           => $request->product_vat[$key] ?? 0,
                'description'   => $request->description[$key] ?? null,
                'discount'      => $request->product_discount[$key] ?? 0,
                'discount_type' => $request->discount_type[$key] ?? null,
                'quantity'      => $request->product_qty[$key],
                'total_amount'  => $request->subtotal[$key],
            ]);

            dd($details);


//            $product_stocks = ProductStock::dokani()->where('product_id', $product_id)->orderByRaw('ISNULL(expiry_at), expiry_at ASC')->get();
//            $product_stocks = ProductStock::dokani()->where('product_id', $product_id)->get();

            $lotQtyArr = [];

            $getLotNumbers = $this->getLotNumbers($product_id);


            foreach($getLotNumbers as $lotNumber) {

                $availableQty = $this->checkAvailableQuantity($product_id, $lotNumber->lot);

                if ($availableQty > 0) {

                    $leftQty = $details->quantity - array_sum($lotQtyArr);

                    $purchaseDetail = PurchaseDetail::where(['product_id' => $product_id, 'lot' => $lotNumber->lot])->first();

                    $quantity = $lotNumber->available_quantity;

                    if ($leftQty <= $lotNumber->available_quantity) {
                        $quantity = $leftQty;
                    }

                    if ($details->quantity > array_sum($lotQtyArr)) {


                        (new ProductStockService())->stockUpdateOrCreate(
                            $product_id,
                            $purchaseDetail->expiry_at ?? null,
                            $lotNumber->lot,
                            'sale',
                            $purchaseDetail->quantity ?? 0,
                            $quantity);

                        (new ProductStockLogService())->stockLog(
                            $details->id,
                            'Sale Details',
                            $product_id,
                            $lotNumber->lot,
                            $purchaseDetail->expiry_at ?? null,
                            'Out',
                            $quantity,
                            -abs($quantity),
                            $details->buy_price,
                            $request->product_price[$key]
                        );


                        (new ProductLedgerService())->storeLedger(
                            $product_id,
                            $details->id,
                            'Sale Details',
                            'Out',
                            $details->quantity
                        );
                    }

                    array_push($lotQtyArr, $quantity);

                }

            }

        }

    }


//    public function stockUpdateOrCreate($product_id, $sold_qty, $destroy = null)
//    {
////        $product_stock = ProductStock::where('product_id', $product_id)->dokani()->first();
//        $product_stock = ProductStock::where('product_id', $product_id)->first();
//        if ($product_stock) {
//            if ($destroy) {
////                $product_stock->increment('available_quantity', $sold_qty);
//                $product_stock->decrement('sold_quantity', $sold_qty);
//            } else {
//                $product_stock->increment('sold_quantity', $sold_qty);
////                $product_stock->decrement('available_quantity', $sold_qty);
//            }
//        } else {
//            ProductStock::create([
//                'product_id'                => $product_id,
//                'opening_quantity'          => 0,
////                'available_quantity'        => $available_qty ?? 0,
//                'purchased_quantity'        => 0,
//                'sold_quantity'             => $sold_qty,
//                'wastage_quantity'          => 0,
//                'sold_return_quantity'      => 0,
//                'purchase_return_quantity'  => 0,
//            ]);
//        }
//    }


    public function transaction($request)
    {
        $change_account_id = $request->change_account_id;
        $change_amount = $request->change_amount;

        foreach($request->account_ids as $key => $account_id){

            $amount = $request->amount[$key];
            if($account_id == $change_account_id){
                $amount = $request->amount[$key] - $change_amount;
            }

            (new AccountService())->multiAccount(
                $this->sale->id,
                'Sale',
                $account_id,
                $amount,
                $request->check_no[$key],
                $request->check_date[$key]
            );

            (new CashFlowService())->transaction(
                $this->sale->id,
                'Sale',
                $amount,
                'In',
                'Sale',
                $account_id
            );
        }


    }




    public function saleDelete($id)
    {

        $sale     = Sale::where('id',$id)->with(['customer_point','customer_refer'])->first();

        $customer = Customer::find($sale->customer_id);

        // dd($sale->previous_due);
        // $customer->decrement('balance', $sale->paid_amount);
        // $customer->decrement('balance', ($sale->payable_amount - $sale->discount));
        // $customer->decrement('balance', $sale->previous_due ?? 0);
        $customer->decrement('balance', $sale->due_amount ?? 0);

        //Point Delete
        if ($sale->point_id) {
            $new_point = ($customer->point - $sale->point) - optional($sale->customer_point)->point;
            $customer->point = $new_point;
            $customer->update();
        }


        //Refer Delete

        if ($sale->refer_customer_id) {

            $refer_customer = Customer::find($sale->refer_customer_id);
            $update_refer = optional($sale->customer_refer)->refer_point / 100 * $sale->payable_amount;
            $new_refer = $refer_customer->refer_balance - $update_refer ;
            $refer_customer->refer_balance = $new_refer;
            $refer_customer->update();
        }




       if ($sale) {

           $sale_details = SaleDetail::where('sale_id', $sale->id)->get();

           foreach ($sale_details as $key => $item) {

               $productStockLogs = ProductStockLog::dokani()
                                                    ->where('product_id',$item->product_id)
                                                    ->where('sourceable_type','Sale')
                                                    ->where('sourceable_id',$item->id)
                                                    ->get();
               foreach ($productStockLogs as $productStockLog){

                   $productStock = ProductStock::dokani()
                                                ->where('product_id',$productStockLog->product_id)
                                                ->where('lot',$productStockLog->lot)
                                                ->where('branch_id',$productStockLog->branch_id)
                                                ->first();
                   $productStock->update(
                       [
                            'sold_quantity'     => $productStock->sold_quantity - $productStockLog->quantity
                       ]);
                   $productStockLog->delete();
               }

               (new ProductLedgerService())->storeLedger(
                   $item->product_id,
                   $item->id,
                   'Sale',
                   'In',
                   $item->quantity
               );


               $item->delete();
           }

           $cashFlows = CashFlow::dokani()
                                ->where('transactionable_type','Sale')
                                ->where('transactionable_id', $sale->id)
                                ->get();

           foreach ($cashFlows as $cashFlow){
               $cashFlow->delete();
           }


           $ledger = CustomerLedger::dokani()->where('source_type','Sale')->where('source_id', $sale->id)->first();

           optional($ledger)->delete();

       }

       $multiAccounts = MultiAccountPay::dokani()->where('source_type','Sale')->where('source_id', $sale->id)->get();

       foreach ($multiAccounts as $multiAccount){

           (new AccountService())->decreaseBalance($multiAccount->account_id, $multiAccount->amount);

           $multiAccount->delete();

       }


//       if ($sale->paid_amount > 0) {
//           (new CashFlowService())->transaction(
//               $sale->id,
//               'Sale',
//               $sale->paid_amount,
//               'Out',
//               'Sale',
//               $sale->account_type_id
//           );
//       }

       $order = Order::query()->where('sale_id', $id)->first();

       optional($order)->sale_id = null;
       optional($order)->update();

       $sale->delete();
    }

    public function customerDue($request)
    {
       if ($request->due_amount != 0) {
            (new LedgerService())->customerLedger($request->customer_id, $this->sale->id, 'Sale',
                $request->due_amount, 'Out',$request->account_id,$request->pos_method);
       }
    }


    public function customerRefer($request, $refer_value)
    {

        if (isset($request->refer_customer_id)){

            (new LedgerService())->customerReferBalance($request->refer_customer_id, $this->sale->id, 'Refer Sale',
                $refer_value, 'Out');
        }
    }


    public function customerPoint($request, $point){

        Customer::updateOrCreate([
            'id'           => $request->customer_id,
        ],[
            'point'        => $point,
        ]);
    }



    public function saleAccountBalance($request){

        $change_account_id = $request->change_account_id;
        $change_amount = $request->change_amount;

        foreach($request->account_ids as $key => $account_id) {
            $amount = $request->amount[$key];
            if($account_id == $change_account_id){
                $amount = $request->amount[$key] - $change_amount;
            }
            (new AccountService())->increaseBalance($account_id, $amount);
        }
    }

}
