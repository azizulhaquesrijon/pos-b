<?php

namespace Module\Dokani\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\MultiAccountPay;
use Module\Dokani\Models\Product;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\ProductStockLog;
use Module\Dokani\Models\Purchase;
use Module\Dokani\Models\PurchaseDetail;
use Module\Dokani\Models\Supplier;
use Module\Dokani\Models\SupplierLedger;

class PurchaseService
{

    public $purchase;
    public $product;

    public function againPurchase(){

        $purchases = Purchase::dokani()->with('details')->get();
//dd($purchases);
        foreach ($purchases as $purchase){

//            $this->purchase = Purchase::create([
//                'supplier_id'       => $purchase->supplier_id,
//                'account_id'        => $purchase->account_id,
//                'invoice_no'        => $purchase->invoice_no,
//                'payable_amount'    => $purchase->payable_amount,
//                'previous_due'      => $purchase->previous_due,
//                'discount'          => $purchase->discount,
//                'paid_amount'       => $purchase->paid_amount ?? 0,
//                'due_amount'        => $purchase->due_amount,
//                'received_by'       => $purchase->received_by,
//                'date'              => $purchase->date,
//
//            ]);

//            if ($purchase->paid_amount > 0) {
//                (new CashFlowService())->transaction(
//                    $purchase->id,
//                    'Purchase',
//                    $purchase->paid_amount,
//                    'Out',
//                    'Purchase Create',
//                    $purchase->account_id
//                );
//            }
//
//            (new AccountService())->decreaseBalance($purchase->account_id, $purchase->paid_amount);
////        dd($this->purchase);
//            if ($purchase->due_amount > 0) {
//                (new LedgerService())->supplierLedger($purchase->supplier_id, $purchase->id, 'Purchase', $purchase->due_amount, 'Out',$purchase->account_id);
//            }



            foreach ($purchase->details ?? [] as $detail) {


//            $details = $this->purchase->details()->create([
//                'product_id'    => $detail->product_id,
//                'lot'           => $detail->lot,
//                'price'         => $detail->price ?? 0,
//                'expiry_at'     => $detail->expiry_at ?? null,
//                'quantity'      => $detail->quantity,
//                'total_amount'  => $detail->total_amount,
//            ]);

                (new ProductLedgerService())->storeLedger(
                    $detail->product_id,
                    $detail->id,
                    'Purchase',
                    'In',
                    $detail->quantity
                );

//            $this->stockUpdateOrCreate($product_id, $request->product_qty[$key]);

                (new ProductStockService())->stockUpdateOrCreate(
                    $detail->product_id,
                    $detail->expiry_at ?? null,
                    $detail->lot,
                    'purchase',
                    $detail->quantity);
//dd($request->expiry_at[$key] ?? null);
                (new ProductStockLogService())->stockLog(
                    $detail->id,
                    'Purchase',
                    $detail->product_id,
                    $detail->lot,
                    $detail->expiry_at ?? null,
                    'In',
                    $detail->quantity,
                    $detail->quantity,
                    $detail->price,
                    0,
                    Carbon::parse($detail->created_at)->format('Y-m-d')
                );
            }



        }




    }

    public function store($request)
    {

        $this->purchase = Purchase::create([
            'supplier_id'       => $request->supplier_id,
            'account_amount'    => $request->total_amount,
            // 'invoice_no'        => "#P-" . str_pad(rand(1,1000), 5, '0', 0),
            'reference_no'      => $request->reference_no,
            'payable_amount'    => $request->payable_amount,
            'previous_due'      => $request->previous_due,
            'discount'          => $request->discount,
            'paid_amount'       => $request->paid_amount ?? 0,
            'due_amount'        => $request->due_amount ?? 0,
            'received_by'       => auth()->id(),
            'date'              => date('Y-m-d'),

        ]);
        if($this->purchase){
            Purchase::find($this->purchase->id)->update([
                'invoice_no'=>"#P-00" . $this->purchase->id,
            ]);
        }

        if ($request->paid_amount > 0) {
            $this->transaction($request);
        }

        $this->purchaseAccountBalance($request);
        $this->supplierDue($request);
            //    dd($this->purchase);

        return $this->purchase;

    }

    public function details($request)
    {
        // dd($this->purchase);

        foreach ($request->product_ids ?? [] as $key => $product_id) {

            $lot[$key] = strtoupper(Str::random(5));
//            $expiry_date[$key] = Carbon::parse($request->expity_at[$key])->format('Y-m-d');
            $details = $this->purchase->details()->create([
                'product_id'    => $product_id,
                'lot'           => $lot[$key],
                'price'         => $request->product_price[$key] ?? 0,
                'expiry_at'     => $request->expiry_at[$key] ?? null,
                'quantity'      => $request->product_qty[$key],
                'total_amount'  => $request->subtotal[$key],
            ]);

            (new ProductLedgerService())->storeLedger(
                $product_id,
                $details->id,
                'Purchase',
                'In',
                $request->product_qty[$key],
                $branch_id = null
            );

//            $this->stockUpdateOrCreate($product_id, $request->product_qty[$key]);

            (new ProductStockService())->stockUpdateOrCreate(
                $product_id,
                $request->expiry_at[$key] ?? null,
                $lot[$key],
                'purchase',
                $request->product_qty[$key]);
//dd($request->expiry_at[$key] ?? null);
            (new ProductStockLogService())->stockLog(
                $details->id,
                'Purchase',
                $product_id,
                $lot[$key],
                $request->expiry_at[$key] ?? null,
                'In',
                $request->product_qty[$key],
                $request->product_qty[$key],
                $request->product_price[$key]
            );
        }
    }


//    public function stockUpdateOrCreate($product_id, $_qty)
//    {
//
//        $product_stock = ProductStock::where('product_id', $product_id)->first();
//
//        if ($product_stock) {
//            $product_stock->increment('purchased_quantity', $_qty);
//            $product_stock->increment('available_quantity', $_qty);
//        } else {
//            ProductStock::create([
//                'product_id'                => $product_id,
//                'opening_quantity'          => $_qty,
//                'available_quantity'        => $available_qty ?? 0,
//                'purchased_quantity'        => $_qty,
//                'sold_quantity'             => 0,
//                'wastage_quantity'          => 0,
//                'sold_return_quantity'      => 0,
//                'purchase_return_quantity'  => 0,
//            ]);
//        }
//    }



    public function transaction($request)
    {

        foreach($request->account_ids as $key => $account_id){

            (new AccountService())->multiAccount(
                $this->purchase->id,
                'Purchase',
                $account_id,
                $request->amount[$key],
                $request->check_no[$key],
                $request->check_date[$key]
            );

            (new CashFlowService())->transaction(
                $this->purchase->id,
                'Purchase',
                $request->amount[$key],
                'Out',
                'Purchase',
                $account_id
            );
        }

    }

    public function purchaseDelete($id)
    {
        $purchase = Purchase::find($id);
        $supplier = Supplier::find($purchase->supplier_id);
        $supplier->decrement('balance', $purchase->previous_due ?? 0);

        if ($purchase) {

            $purchase_details = PurchaseDetail::where('purchase_id', $purchase->id)->get();

            foreach ($purchase_details as $key => $item) {

                $productStocks = ProductStock::dokani()->where('product_id',$item->product_id)->where('lot',$item->lot)->get();
                foreach ($productStocks as $productStock){

                    if ($productStock->sold_quantity <= 0 || $productStock->sold_exchange_quantity <=0){

                        $productStock->delete();
                    }
                    else {
                        return redirect()->back()->withError('This lot have already sale');
                    }
                }


                $productStockLogs = ProductStockLog::dokani()->where('product_id',$item->product_id)->where('lot',$item->lot)->get();

                foreach ($productStockLogs as $productStockLog){
                        $productStockLog->delete();
                }

                (new ProductLedgerService())->storeLedger(
                    $item->product_id,
                    $item->id,
                    'Purchase',
                    'Out',
                    $item->quantity
                );

                $item->delete();
            }


            $cashFlows = CashFlow::dokani()->where('transactionable_type','Purchase')->where('transactionable_id', $purchase->id)->get();
            foreach ($cashFlows as $cashFlow){
                $cashFlow->delete();
            }

            $ledgers = SupplierLedger::dokani()->where('source_type','Purchase')->where('source_id', $purchase->id)->first();
            optional($ledgers)->delete();

        }

        $multiAccounts = MultiAccountPay::dokani()->where('source_type','Purchase')->where('source_id', $purchase->id)->get();

        foreach ($multiAccounts as $multiAccount){

            (new AccountService())->increaseBalance($multiAccount->account_id, $multiAccount->amount);

            $multiAccount->delete();

        }

//        if ($purchase->paid_amount > 0) {
//            (new CashFlowService())->transaction(
//                $purchase->id,
//                'Purchase',
//                $purchase->paid_amount,
//                'In',
//                'Purchase',
//                $purchase->account_type_id
//            );
//        }

        $purchase->delete();

    }



    public function supplierDue($request)
    {
        (new LedgerService())->supplierLedger($request->supplier_id, $this->purchase->id, 'Purchase', $request->due_amount, 'Out',$request->account_id);
    }


    public function purchaseAccountBalance($request){

        foreach($request->account_ids as $key => $account_id) {
            (new AccountService())->decreaseBalance($account_id, $request->amount[$key]);
        }
    }

}
