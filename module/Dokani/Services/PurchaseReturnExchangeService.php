<?php


namespace Module\Dokani\Services;



use Auth;
use Illuminate\Support\Str;
use Module\Dokani\Models\Account;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\MultiAccountPay;
use Module\Dokani\Models\Supplier;
use Module\Dokani\Models\SaleDetail;
use Module\Dokani\Models\SaleReturn;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\SaleExchange;
use Module\Dokani\Models\ProductLedger;
use Module\Dokani\Models\PurchaseDetail;
use Module\Dokani\Models\PurchaseReturn;
use Module\Dokani\Models\SupplierLedger;
use Module\Dokani\Models\ProductStockLog;
use Module\Dokani\Models\PurchaseExchange;
use Module\Dokani\Models\SaleReturnExchange;
use Module\Dokani\Models\ProductDamageDetail;
use Module\Dokani\Models\PurchaseReturnExchange;
use Module\Dokani\Models\SaleReturnExchangeDetail;
use Module\Dokani\Models\SaleReturnExchangePayment;

class PurchaseReturnExchangeService
{
    public $purchaseReturnExchange;
    public $purchaseReturn;













    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
//        $this->productBarcodeTrackingService    = new ProductBarcodeTrackingService;
//        $this->stockService                     = new StockService;
//        $this->productDamageService             = new ProductDamageService;
//        $this->invoiceNumberService             = new InvoiceNumberService;
    }




    /*
     |--------------------------------------------------------------------------
     | STORE SALE RETURNS (METHOD)
     |--------------------------------------------------------------------------
    */
    public function store($request)
    {
        $this->purchaseReturnExchange = PurchaseReturnExchange::create([
            'company_id'                     => 1,
            'dokan_id'                       => auth()->user()->dokan_id,
            'purchase_id'                    => $request->purchase_id,
            'supplier_id'                    => $request->supplier_id ,
            'date'                           => $request->date ?? date('Y-m-d'),
            'invoice_no'                     => "#PRE-" . str_pad(rand(1,1000), 5, '0', 0),
            'total_return_quantity'          => $request->total_return_quantity,
            'return_subtotal'                => $request->total_return_subtotal,
            'total_return_discount_percent'  => $request->total_return_discount_percent ?? 0,
            'total_return_discount_amount'   => $request->total_return_discount_amount ?? 0,
            'total_exchange_quantity'        => $request->total_exchange_quantity,
            'exchange_subtotal'              => $request->total_exchange_subtotal,
            'total_exchange_discount_percent'=> $request->total_exchange_discount_percent ?? 0,
            'total_exchange_discount_amount' => $request->total_exchange_discount_amount ?? 0,
            'rounding'                       => $request->rounding ?? 0,
            'paid_amount'                    => $request->paid_amount ?? 0,
            'due_amount'                     => $request->due_amount ?? 0,
            'change_amount'                  => $request->change_amount ?? 0,
        ]);
        if($this->purchaseReturnExchange){
            PurchaseReturnExchange::find($this->purchaseReturnExchange->id)->update([
                'invoice_no'=>"#PRE-00" . $this->purchaseReturnExchange->id,
            ]);
        }




        $this->storePurchaseReturns($request);
        $this->storePurchaseExchanges($request);
        $this->storeSupplierDue($request);

        $this->transaction($request);


        //         INCREMENT ACCOUNT BALANCE
        if($request->paid_amount < 0){
            (new AccountService())->increaseBalance($request->account_ids, abs($request->paid_amount));
        }else{
            (new AccountService())->decreaseBalance($request->account_ids, abs($request->paid_amount));
        }


        return $this->purchaseReturnExchange;

    }


  /*
     |--------------------------------------------------------------------------
     | STORE SALE RETURNS (METHOD)
     |--------------------------------------------------------------------------
    */
    public function storeSupplierDue($request)
    {
        SupplierLedger::create([
            'supplier_id'       => $request->supplier_id,
            'source_type'       => "Purchase Return",
            'source_id'         => $this->purchaseReturnExchange->id,
            'account_id'        => $request->account_ids,
            // 'amount'            => abs($request->due_amount),
            'amount'            => $request->due_amount,
            'balance_type'      => $request->due_amount > 0 ? 'In' : "Out",
            'date'              => date('Y-m-d'),
        ]);

        $supplier = Supplier::find($request->supplier_id);
        $supplier->increment('balance', $request->due_amount);

    }



    /*
     |--------------------------------------------------------------------------
     | STORE SALE RETURNS (METHOD)
     |--------------------------------------------------------------------------
    */
    public function storePurchaseReturns($request)
    {
        // dd("purchase returns");
        foreach($request->return_product_id as $key => $value){
            $this->purchaseReturn = PurchaseReturn::create([
                'product_id'                  => $value,
                'purchase_return_exchange_id' => $this->purchaseReturnExchange->id,
                'purchase_detail_id'          => $request->return_purchase_detail_id[$key] ?? 0,
                'purchase_price'              => $request->return_purchase_price[$key] ?? 0,
                'quantity'                    => $request->return_quantity[$key] ?? 0,
                'subtotal'                    => $request->return_subtotal[$key] ?? 0,
                'return_type'                 => $request->return_type[$key] ?? 0,
            ]);


            (new ProductLedgerService())->storeLedger(
                $value,
                $this->purchaseReturn->id,
                'Purchase Return',
                'Out',
                $request->return_quantity[$key] ?? 0
            );

            (new ProductStockService())->stockUpdateOrCreate(
                $value,
                $request->expiry_at[$key] ?? null,
                $request->return_purchase_detail_lot[$key],
                'Purchase Return',
                0,
                0,
                0,
                0,
                0,
                $request->return_quantity[$key] ?? 0,
                0
            );

            (new ProductStockLogService())->stockLog(
                $this->purchaseReturn->id,
                'Purchase Return',
                $value,
                $request->return_purchase_detail_lot[$key],
                $request->expiry_at[$key] ?? null,
                'out',
                $request->return_quantity[$key],
                $request->return_quantity[$key],
                $request->return_purchase_price[$key]
            );



        }

    }









    /*
     |--------------------------------------------------------------------------
     | STORE SALE EXCHANGES (METHOD)
     |--------------------------------------------------------------------------
    */
    public function storePurchaseExchanges($request)
    {


        // dd("purchase Exchange");

        foreach($request->product_ids ?? [] as $key => $value){
            $lot[$key] = strtoupper(Str::random(5));
            $this->purchaseReturn = PurchaseExchange::create([
                'product_id'                  => $value,
                'purchase_return_exchange_id' => $this->purchaseReturnExchange->id,
                'price'                       => $request->product_price[$key] ?? 0,
                'quantity'                    => $request->product_qty[$key] ?? 0,
                'total_amount'                => $request->product_qty[$key] * $request->product_price[$key],
                'lot'                         => $lot[$key],
                'expire_at'                   => $request->expiry_at[$key],
            ]);

            (new ProductLedgerService())->storeLedger(
                $value,
                $this->purchaseReturn->id,
                'Purchase Exchange',
                'In',
                $request->product_qty[$key] ?? 0
            );

            (new ProductStockService())->stockUpdateOrCreate(
                $value,
                $request->expiry_at[$key] ?? null,
                $lot[$key],
                'Purchase Exchange',
                0,
                0,
                0,
                0,
                0,
                0,
                $request->product_qty[$key] ?? 0
            );

            (new ProductStockLogService())->stockLog(
                $this->purchaseReturn->id,
                'Purchase Exchange',
                $value,
                $lot[$key],
                $request->expiry_at[$key] ?? null,
                'In',
                $request->product_qty[$key],
                $request->product_qty[$key],
                $request->product_price[$key]
            );

        }

    }



    public function transaction($request)
    {
        if($request->account_ids){

            (new AccountService())->multiAccount(
                $this->purchaseReturnExchange->id,
                'Purchase Return Exchange',
                $request->account_ids,
                abs($request->paid_amount),
                $request->check_no ?? null,
                $request->check_date ?? null
            );

            (new CashFlowService())->transaction(
                $this->purchaseReturnExchange->id,
                'Purchase Return Exchange',
                abs($request->paid_amount),
                $request->paid_amount < 0 ? 'In' : 'Out',
                'Purchase Return Exchange',
                $request->account_ids
            );
        }

    }










    /*
     |--------------------------------------------------------------------------
     | STORE SALE PRODUCT EXCHANGE (METHOD)
     |--------------------------------------------------------------------------
    */
    public function storeSaleProductExchange($request)
    {
        if ($request->payable_amount < 0 ){
            if ($request->paid_amount > 0 ){

                $paid_amount = -$request->paid_amount;
            }
            else{

                $paid_amount = $request->paid_amount;
            }
        }
        else{
            $paid_amount = $request->paid_amount;
        }

        return $this->saleReturnExchange = SaleReturnExchange::create([

            'company_id'                            => Auth::user()->id,
            'sale_id'                               => $request->sale_id,
            'customer_id'                           => $request->customer_id,
            'date'                                  => $request->date ?? date('Y-m-d'),
            'total_return_quantity'                 => array_sum($request->return_quantity),
            'return_subtotal'                       => $request->total_return_subtotal ?? 0,
            'total_return_discount_percent'         => $request->total_return_discount_percent ?? 0,
            'total_return_discount_amount'          => $request->total_return_discount_amount ?? 0,
            'total_exchange_quantity'               => $request->exchange_quantity != [] ? array_sum($request->exchange_quantity) : 0,
            'total_exchange_cost'                   => $request->total_exchange_cost ?? 0,
            'exchange_subtotal'                     => $request->total_exchange_subtotal ?? 0,
            'total_exchange_discount_percent'       => $request->total_exchange_discount_percent ?? 0,
            'total_exchange_discount_amount'        => $request->total_exchange_discount_amount ?? 0,
            'rounding'                              => $request->rounding ?? 0,
            'paid_amount'                           => $paid_amount ?? 0,
            'due_amount'                            => $request->due_amount ?? 0,
            'change_amount'                         => $request->change_amount ?? 0,
            'dokan_id'                              => dokanId(),
        ]);

    }








    public function againReturnProduct(){


        $returnProducts = SaleReturn::dokani()->get();

        foreach ($returnProducts as $returnProduct){

            $stock_log = ProductStockLog::where('sourceable_id',$returnProduct->sale_detail_id)->where('sourceable_type','Sale')->first();
            $sale_detail = SaleDetail::where('id',$returnProduct->sale_detail_id)->first();

            $product_stock = ProductStock::where('product_id',$returnProduct->product_id)
                ->where('lot',$stock_log->lot)
                ->first();
//            dd($stock_log, $returnProduct->product_id);
//                $available_quantity = $product_stock->available_quantity;
            $sold_return_quantity = $product_stock->sold_return_quantity;
            $product_stock->update([

//                    'available_quantity'        => $request->quantity + $available_quantity,
                'sold_return_quantity'      => $returnProduct->quantity + $sold_return_quantity,
                'sale_return_exchange_id'   => $returnProduct->id,
            ]);

            (new ProductStockLogService())->stockLog(
                $returnProduct->sale_detail_id,
                'Sale Return',
                $returnProduct->product_id,
                $product_stock->lot,
                null,
                'In',
                $returnProduct->quantity,
                -abs($returnProduct->quantity),
                $sale_detail->buy_price,
                $returnProduct->sale_price
            );

            (new ProductLedgerService())->storeLedger(
                $returnProduct->product_id,
                $returnProduct->sale_detail_id,
                'Sale',
                'In',
                $returnProduct->quantity
            );
        }

    }







    public function deletePurchaseReturn($purchaseReturns){

        foreach ($purchaseReturns as $key => $purchaseReturn) {
            $qty = $purchaseReturn->quantity;
            $product_id = $purchaseReturn->product_id;
            $lot = optional($purchaseReturn->purchase_details)->lot;

            $product_stock = ProductStock::dokani()->where([['product_id', $product_id],['lot', $lot]])->first();

            $product_stock->decrement('purchase_return_quantity', $qty);




            $product_ledger = ProductLedger::dokani()
                                            ->where([
                                                ['sourceable_type', 'Purchase Return'],
                                                ['sourceable_id', $purchaseReturn->id],
                                                ['product_id', $purchaseReturn->product_id]
                                            ])
                                            ->delete();


            $product_ledger = ProductStockLog::dokani()
                                            ->where([
                                                ['sourceable_type', 'Purchase Return'],
                                                ['sourceable_id', $purchaseReturn->id],
                                                ['product_id', $purchaseReturn->product_id]
                                            ])
                                            ->delete();

            $purchaseReturn->delete();
        }
    }




    public function deletePurchaseExchanges($purchaseExchanges){

        foreach ($purchaseExchanges as $key => $purchaseExchange) {
            $qty = $purchaseExchange->quantity;
            $lot = $purchaseExchange->lot;
            $product_id = $purchaseExchange->product_id;

            $product_stock = ProductStock::dokani()->where([['product_id', $product_id],['lot', $lot]])->first();

            $product_stock->decrement('purchase_exchange_quantity', $qty);




            $product_ledger = ProductLedger::dokani()
                                            ->where([
                                                ['sourceable_type', 'Purchase Exchange'],
                                                ['sourceable_id', $purchaseExchange->id],
                                                ['product_id', $purchaseExchange->product_id]
                                            ])
                                            ->delete();


            $product_ledger = ProductStockLog::dokani()
                                            ->where([
                                                ['sourceable_type', 'Purchase Exchange'],
                                                ['sourceable_id', $purchaseExchange->id],
                                                ['product_id', $purchaseExchange->product_id]
                                            ])
                                            ->delete();

            $purchaseExchange->delete();
        }
    }




    public function deleteSupplierDue($purchaseReturnExchange){

        $supplierLedger = SupplierLedger::dokani()
                                        ->where([
                                            ['source_type', 'Purchase Return'],
                                            ['source_id', $purchaseReturnExchange->id]
                                            ])
                                        ->with('supplier')
                                        ->first();

        $balance = number_format($purchaseReturnExchange->payable_amount,2,'.','') - number_format($purchaseReturnExchange->paid_amount,2,'.','');

        optional($supplierLedger->supplier)->decrement('balance', $balance);

        $cash_flow = CashFlow::dokani()
                                ->where('transactionable_type', 'Purchase Return Exchange')
                                ->where('transactionable_id', $purchaseReturnExchange->id)
                                ->first();

        if($cash_flow->balance_type == 'In'){
            (new AccountService())->decreaseBalance($cash_flow->account_type_id, $cash_flow->amount);
        }else{
            (new AccountService())->increaseBalance($cash_flow->account_type_id, $cash_flow->amount);
        }

        MultiAccountPay::dokani()
                        ->where('source_type', 'Purchase Return Exchange')
                        ->where('source_id', $purchaseReturnExchange->id)
                        ->first()
                        ->delete();


        $cash_flow->delete();

        $supplierLedger->delete();
    }






    /*
     |--------------------------------------------------------------------------
     | STORE SALE RETURN EXCHANGE DETAILS (METHOD)
     |--------------------------------------------------------------------------
    */
    public function storeSaleReturnExchangeDetails($request, $damage = null)
    {
//dd($request->all());
        foreach($request->return_product_id ?? [] as $key => $return_product_id) {

            $this->saleReturn = $this->storeSaleReturns($request, $key);
            $this->saleExchange = $this->storeSaleExchanges($request, $key);

            $this->saleReturnExchangeDetail = SaleReturnExchangeDetail::create([

                'sale_return_exchange_id'       => $this->saleReturnExchange->id,
                'sale_detail_id'                => $request->return_sale_detail_id[$key],
                'sale_return_id'                => $this->saleReturn->id,
                'sale_exchange_id'              => $this->saleExchange ? $this->saleExchange->id : null,
            ]);

            $sale_detail = SaleDetail::where('id',$request->return_sale_detail_id[$key])->first();
            $stock_log = ProductStockLog::where('sourceable_id',$request->return_sale_detail_id[$key])->where('sourceable_type','Sale')->first();
//            dd($sale_detail);
            $sale_detail->update([

                'return_id' => $this->saleReturn->id,
            ]);


//            SaleDetail::where('id', $request->return_sale_detail_id[$key])->update([
//
//                'return_id' => $this->saleReturn->id,
//            ]);

            if($request->return_type[$key] == 'Damaged') {

                for ($i = 0 ; $i < count($request->return_product_id) ; $i++){

                    $this->productDamageService = ProductDamageDetail::create([

                        'product_damage_id'     => $damage->id,
                        'product_id'            => $request->return_product_id[$i],
                        'lot'                   => $stock_log->lot,
                        'condition'             => $request->return_type[$i],
                        'quantity'              => $request->return_quantity[$i],
                        'sale_price'            => $request->return_sale_price[$i],
                    ]);


                    $product_stock = ProductStock::where('product_id',$request->return_product_id[$i])
                        ->where('lot',$stock_log->lot)
                        ->first();
//                dd($stock_log->lot);
//                $available_quantity = $product_stock->available_quantity;
                    $wastage_quantity = $product_stock->wastage_quantity;
                    $product_stock->update([

//                    'available_quantity'        => $request->quantity + $available_quantity,
                        'wastage_quantity'      => $request->return_quantity[$i] + $wastage_quantity,
                    ]);

                    (new ProductStockLogService())->stockLog(
                        $request->return_sale_detail_id[$key],
                        'Sale Return Damaged',
                        $request->return_product_id[$i],
                        $product_stock->lot,
                        null,
                        'In',
                        $request->return_quantity[$i],
                        -abs($request->return_quantity[$i]),
                        $sale_detail->buy_price,
                        $request->return_sale_price[$i]
                    );

                    (new ProductLedgerService())->storeLedger(
                        $request->return_product_id[$i],
                        $request->return_sale_detail_id[$key],
                        'Sale Return Damaged',
                        'In',
                        $request->return_quantity[$i]
                    );


                }

//                $this->productDamageService->storeProductDamageDetail($damage, $request->return_product_id[$key], $request->return_type[$key],
//                    $request->return_quantity[$key], $request->return_purchase_price[$key], $request->return_sale_price[$key],
//                    $request->return_discount_percent[$key], $request->return_discount_amount[$key]);

                //$this->productDamageService->storeProductDamageBarcode($request->return_barcode_id[$key]);

            }
            if($request->return_type[$key] != 'Damaged') {
                $product_stock = ProductStock::where('product_id',$request->return_product_id[$key])
                    ->where('lot',$stock_log->lot)
                    ->first();
//                dd($stock_log->lot);
//                $available_quantity = $product_stock->available_quantity;
                $sold_return_quantity = $product_stock->sold_return_quantity;
                $product_stock->update([

//                    'available_quantity'        => $request->quantity + $available_quantity,
                    'sold_return_quantity'      => $request->return_quantity[$key] + $sold_return_quantity,
                    'sale_return_exchange_id'   => $this->saleReturnExchange->id,
                ]);

                (new ProductStockLogService())->stockLog(
                    $request->return_sale_detail_id[$key],
                    'Sale Return',
                    $request->return_product_id[$key],
                    $product_stock->lot,
                    null,
                    'In',
                    $request->return_quantity[$key],
                    -abs($request->return_quantity[$key]),
                    $sale_detail->buy_price,
                    $request->return_sale_price[$key]
                );

                (new ProductLedgerService())->storeLedger(
                    $request->return_product_id[$key],
                    $request->return_sale_detail_id[$key],
                    'Sale Return',
                    'In',
                    $request->return_quantity[$key]
                );

            }


            if ($request->exchange_product_id != []) {

                for ($key = 0; $key < count($request->exchange_product_id); $key++){

//                    $product_stock = ProductStock::where('product_id',$request->exchange_product_id[$key]);
//                    $available_quantity = $product_stock->available_quantity;
//
//                    $product_stock->update([
//
//                        'available_quantity' => $available_quantity - $request->exchange_quantity[$key],
//                    ]);

                    $lotQtyArr = [];

//            $getLotNo = $this->getLotNo($product_id, $details->quantity);



                    $getLotNumbers = $this->getLotNumbers($request->exchange_product_id[$key]);


                    foreach($getLotNumbers as $lotNumber) {

                        $availableQty = $this->checkAvailableQuantity($request->exchange_product_id[$key], $lotNumber->lot);

                        if ($availableQty > 0) {

                            $leftQty = $request->exchange_quantity[$key] - array_sum($lotQtyArr);

                            $purchaseDetail = PurchaseDetail::where(['product_id' => $request->exchange_product_id[$key], 'lot' => $lotNumber->lot])->first();
                            $product = Product::where('id',$request->exchange_product_id[$key])->first();
//                    dd($product_id, $lotNumber->lot);
                            $quantity = $lotNumber->available_quantity;

                            if ($leftQty <= $lotNumber->available_quantity) {
                                $quantity = $leftQty;
                            }

                            if ($request->exchange_quantity[$key] > array_sum($lotQtyArr)) {


                                (new ProductStockService())->stockUpdateOrCreate(
                                    $request->exchange_product_id[$key],
                                    $purchaseDetail->expiry_at ?? null,
                                    $lotNumber->lot,
                                    'sale',
                                    $purchaseDetail->quantity ?? 0,
                                    0,
                                    $quantity);

                                (new ProductStockLogService())->stockLog(
                                    $request->exchange_product_id[$key],
                                    'Sale Exchange',
                                    $request->exchange_product_id[$key],
                                    $lotNumber->lot,
                                    $purchaseDetail->expiry_at ?? null,
                                    'Out',
                                    $quantity,
                                    -abs($quantity),
                                    $product->purchase_price,
                                    $product->sell_price
                                );

                                (new ProductLedgerService())->storeLedger(
                                    $request->exchange_product_id[$key],
                                    $request->exchange_product_id[$key],
                                    'Sale Exchange',
                                    'Out',
                                    $quantity
                                );
                            }

                            array_push($lotQtyArr, $quantity);

                        }

                    }

                }


            }
        }
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




    /*
     |--------------------------------------------------------------------------
     | STORE SALE RETURN EXCHANGE PAYMENT (METHOD)
     |--------------------------------------------------------------------------
    */
//    public function storeSaleReturnExchangePayment($request)
//    {
//        foreach (array_filter($request->pos_account_id) as $key => $account_id) {
//
//            $last_key = array_key_last($request->pos_account_id);
//
//            if ($request->payment_amount[$key] ?? 0 > 0) {
//
//                $balance = (float) $request->payment_amount[$key];
//
//                if ($key == $last_key) {
//
//                    $balance = $balance - $this->saleReturnExchange->change_amount;
//                }
//
//                SaleReturnExchangePayment::create([
//
//                    'sale_id'                       => $request->sale_id,
//                    'sale_return_exchange_id'       => $this->saleReturnExchange->id,
//                    'pos_account_id'                => $account_id,
//                    'amount'                        => $balance,
//                ]);
//
//                PosAccount::find($account_id)->increment('balance', $balance ?? 0);
//            }
//        }
//    }
}
