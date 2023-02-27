<?php


namespace Module\Dokani\Services;



use Module\Dokani\Models\Sale;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Module\Dokani\Models\SaleDetail;
use Module\Dokani\Models\SaleReturn;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\SaleExchange;
use Module\Dokani\Models\ProductDamage;
use Module\Dokani\Models\CustomerLedger;
use Module\Dokani\Models\PurchaseDetail;
use Module\Dokani\Models\ProductStockLog;
use Module\Dokani\Models\SaleReturnDetail;
use Module\Dokani\Models\SaleReturnExchange;
use Module\Dokani\Models\ProductDamageDetail;
use Module\Dokani\Models\SaleExchangeDetails;
use Module\Dokani\Models\SaleReturnExchangeDetail;
use Module\Dokani\Models\SaleReturnExchangePayment;

class SaleReturnExchangeService
{
    public $saleReturnExchange;
    public $saleReturnExchangeDetail;
    public $saleReturn;
    public $saleReturnDetails;
    public $saleExchange;
    public $saleExchangeDetails;
    public $productBarcodeTrackingService;
    public $stockService;
    public $productDamageService;
    public $invoiceNumberService;

    public $saleReturnDamage;
    public $saleReturnDamageDetails;

    public $saleCreate;
    public $saleDetailsCreate;











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
    public function storeSaleReturns($request)
    {
        $this->saleReturn = SaleReturn::create([
            'branch_id'                   => $request->branch_id ?? null,
            'customer_id'                 => $request->customer_id,
            'total_amount'                => $request->return_total_amount ?? 0,
            'quantity'                    => $request->total_return_quantity ?? 0,
            'subtotal'                    => $request->total_return_subtotal ?? 0,
            'sale_return_exchange_id'     => $this->saleReturnExchange->id,
            'invoice_no'                  => "SR-". time(),
        ]);
        if($this->saleReturn){
            $sale_id = sprintf("%06d", $this->saleReturn->id);
            SaleReturn::find($this->saleReturn->id)->update([
                'invoice_no'    => 'SR' . '-' . date('y') . $sale_id,
            ]);
        }

        foreach ($request->return_product_id as $key => $product_id) {

            $this->saleReturnDetails = SaleReturnDetail::create([
                'sale_return_id'            => $this->saleReturn->id,
                'sale_details_id'           => $request->return_sale_detail_ids[$key],
                'lot'                       => $request->return_sale_detail_lot[$key],
                'product_id'                => $product_id,
                'quantity'                  => $request->return_quantity[$key],
                'price'                     => $request->return_sale_price[$key],
                'description'               => '',
                'condition'                 => $request->return_type[$key],
            ]);


            //     PORDUCT LEDGER, STOCK, STOCK LOGS
            (new ProductLedgerService())->storeLedger(
                $product_id,
                $this->saleReturnDetails->id,
                'Sale Return Details',
                'In',
                $request->return_quantity[$key] ?? 0
            );
            $stock_logs = ProductStockLog::where([
                ['sourceable_type', 'Sale'],
                ['sourceable_id', $request->return_sale_detail_ids[$key]],
                ['product_id', $product_id]
            ])->get();

            foreach ($stock_logs as $key => $stock_log) {

                $stock = (new ProductStockService())->getStock(
                    $product_id,
                    $stock_log->lot ?? null,
                    $request->branch_id ?? null
                );
                $stock->increment('sold_return_quantity', $request->return_quantity[$key]);

                (new ProductStockLogService())->stockLog(
                    $this->saleReturnDetails->id,
                    'Sale Return Details',
                    $product_id,
                    $stock_log->lot ?? null,
                    $request->expiry_at[$key] ?? null,
                    'In',
                    $request->return_quantity[$key],
                    $request->return_quantity[$key],
                    0,
                    $request->return_sale_price[$key]
                );
            }





            //    Return Product as Damaged
            if($request->return_type[$key] == 'Damaged'){

                $this->saleReturnDamage = ProductDamage::create([
                    'amount'             => $request->return_sale_price[$key] ?? 0,
                    'date'               => $request->date ?? date('Y-m-d'),
                    'sale_id'            => $request->sale_id,
                    'source_type'        => 'Sale Return Exchange',
                    'source_id'          => $this->saleReturnExchange->id,
                ]);

                $this->saleReturnDamageDetails = ProductDamageDetail::create([
                    'product_damage_id'         => $this->saleReturnDamage->id,
                    'product_id'                => $product_id,
                    'lot'                       => $request->return_sale_detail_lot[$key],
                    'condition'                 => $request->return_type[$key],
                    'quantity'                  => $request->return_quantity[$key],
                    'purchase_price'            => $request->return_product_purchase_price[$key] ?? 0,
                    'purchase_total_amount'     => $request->return_product_purchase_price[$key] ?? 0,
                    'sale_price'                => $request->return_sale_price[$key],
                    'discount_percent'          => 0,
                    'discount_amount'           => 0,
                ]);


                //     PORDUCT LEDGER, STOCK, STOCK LOGS
                (new ProductLedgerService())->storeLedger(
                    $product_id,
                    $this->saleReturnDamageDetails->id,
                    'Sale Return Damaged',
                    'Out',
                    $request->return_quantity[$key] ?? 0
                );

                $stock = (new ProductStockService())->getStock(
                    $product_id,
                    $request->return_sale_detail_lot[$key] ?? null,
                    $request->branch_id ?? null
                );
                $stock->increment('wastage_quantity', $request->return_quantity[$key]);

                (new ProductStockLogService())->stockLog(
                    $this->saleReturnDamageDetails->id,
                    'Sale Return Damaged',
                    $product_id,
                    $request->return_sale_detail_lot[$key],
                    $request->expiry_at[$key] ?? null,
                    'Out',
                    $request->return_quantity[$key],
                    $request->return_quantity[$key],
                    0,
                    $request->return_sale_price[$key]
                );


            }


        }

    }



    /*
     |--------------------------------------------------------------------------
     | STORE SALE EXCHANGES (METHOD)
     |--------------------------------------------------------------------------
    */
    public function storeSaleExchanges($request)
    {

        $this->saleExchange = SaleExchange::create([
            'total_quantity'                => $request->total_exchange_quantity ?? 0,
            'total_purchase_price'          => $request->total_exchange_cost ?? 0,
            'total_sale_price'              => $request->total_exchange_subtotal ?? 0,
            'discount_percent'              => $request->total_exchange_discount_percent ?? 0,
            'discount_amount'               => $request->total_exchange_discount_amount ?? 0,
            'sale_return_exchange_id'       => $this->saleReturnExchange->id,
        ]);
        if($this->saleExchange){
            $sale_id = sprintf("%06d", $this->saleExchange->id);
            SaleExchange::find($this->saleExchange->id)->update([
                'invoice_no'    => 'SXN' . '-' . date('y') . $sale_id,
            ]);
        }


        foreach ($request->product_ids ?? [] as $key => $product_id) {
            $lot = $this->getAvailableLotQty($product_id, $request->branch_id, $request->product_qty[$key]);

            $this->saleExchangeDetails = SaleExchangeDetails::create([
                'sale_exchange_id'          => $this->saleExchange->id,
                'product_id'                => $product_id,
                'lot'                       => $lot->lot,
                'previous_qty'              => $request->previous_quantity[$key] ?? 0,
                'quantity'                  => $request->product_qty[$key],
                'unit_price'                => $request->product_price[$key],
                'product_cost'              => $request->product_cost[$key],
                'comment'                   => '',
            ]);


            //     PORDUCT LEDGER, STOCK, STOCK LOGS
            (new ProductLedgerService())->storeLedger(
                $product_id,
                $this->saleExchangeDetails->id,
                'Sale Exchange Details',
                'Out',
                $request->product_qty[$key] ?? 0
            );

            $stock = (new ProductStockService())->getStock(
                $product_id,
                $lot->lot,
                $request->branch_id ?? null
            );
            $stock->increment('sold_exchange_quantity', $request->product_qty[$key]);

            (new ProductStockLogService())->stockLog(
                $this->saleExchangeDetails->id,
                'Sale Exchange Details',
                $product_id,
                $lot->lot,
                $request->expiry_at[$key] ?? null,
                'Out',
                $request->product_qty[$key],
                $request->product_qty[$key],
                0,
                $request->product_price[$key]
            );


        }


    }

    public function createSales($request){
        $this->saleCreate = Sale::create([
            'customer_id'       => $request->customer_id,
            'branch_id'         => $request->branch_id ?? null,
            'account_amount'    => $request->total_exchange_amount,
            'note'              => $request->note ?? null,
            'courier_id'        => $request->courier_id ?? null,
            'payable_amount'    => $request->total_exchange_amount,
            'sub_total'         => $request->total_exchange_subtotal,
            'previous_due'      => $request->previous_due ?? 0,
            'discount'          => $request->total_exchange_discount_amount ?? 0,
            'discount_type'     => $request->sale_discount_type ?? null,
            'paid_amount'       => $request->total_exchange_amount ?? 0,
            'delivery_charge'   => $request->delivery_charge ?? 0,
            'due_amount'        => $request->due_amount ?? 0,
            'change_amount'     => $request->change_amount ?? 0,
            'total_vat'         => $request->total_exchange_vat_amount ?? 0,
            'sales_by'          => auth()->id(),
            'employee_id'       => $request->employee_id ?? null,
            'source'            => $request->source,
            'date'              => date('Y-m-d'),
            'sale_from'         => 'Sale Exchange',
        ]);

        if($this->saleCreate){
            $sale_id = sprintf("%06d", $this->saleCreate->id);
            Sale::find($this->saleCreate->id)->update([
                'invoice_no'    => $request->inv_prefix . '-' . date('y') . $sale_id,
            ]);
        }


        foreach ($request->product_ids ?? [] as $key => $product_id) {
            $details = $this->saleCreate->sale_details()->create([
                'product_id'    => $product_id,
                'price'         => $request->product_price[$key] ?? 0,
                'vat'           => $request->product_vat[$key] ?? 0,
                'description'   => $request->description[$key] ?? null,
                'discount'      => $request->product_discount[$key] ?? 0,
                'discount_type' => $request->discount_type[$key] ?? null,
                'quantity'      => $request->product_qty[$key],
                'total_amount'  => $request->subtotal[$key],
            ]);

            $lotQtyArr = [];
            $getLotNumbers = $this->getLotNumbers($product_id);

            foreach($getLotNumbers as $lotNumber) {

                $availableQty = $this->checkAvailableQuantity($product_id, $lotNumber->lot);

                if ($availableQty > 0) {

                    // $leftQty = $details->quantity - array_sum($lotQtyArr);

                    $purchaseDetail = PurchaseDetail::where(['product_id' => $product_id, 'lot' => $lotNumber->lot])->first();
                    $product = Product::where('id',$product_id)->where('opening_stock','>',0)->first();
                    $details->update([
                        'buy_price'  => $purchaseDetail->price ?? $product->purchase_price
                    ]);

                    // $quantity = $lotNumber->available_quantity;

                    // if ($leftQty <= $lotNumber->available_quantity) {
                    //     $quantity = $leftQty;
                    // }

                    // if ($details->quantity > array_sum($lotQtyArr)) {

                    //     (new ProductStockService())->stockUpdateOrCreate(
                    //         $product_id,
                    //         $purchaseDetail->expiry_at ?? null,
                    //         $lotNumber->lot,
                    //         'sale',
                    //         $purchaseDetail->quantity ?? 0,
                    //         $quantity);

                    //     (new ProductStockLogService())->stockLog(
                    //         $details->id,
                    //         'Sale',
                    //         $product_id,
                    //         $lotNumber->lot,
                    //         $purchaseDetail->expiry_at ?? null,
                    //         'Out',
                    //         $quantity,
                    //         -abs($quantity),
                    //         $details->buy_price,
                    //         $request->product_price[$key]
                    //     );

                    //     (new ProductLedgerService())->storeLedger(
                    //         $product_id,
                    //         $details->id,
                    //         'Sale',
                    //         'Out',
                    //         $details->quantity
                    //     );
                    // }

                    // array_push($lotQtyArr, $quantity);

                }

            }
        }

    }






    public function getAvailableLotQty($product_id, $branch_id, $qty){
        return ProductStock::dokani()
                    ->where([
                        ['product_id', $product_id],
                        ['branch_id', $branch_id],
                        ['available_quantity', '>=', $qty],
                    ])
                    ->first();
    }










    /*
     |--------------------------------------------------------------------------
     |              STORE SALE RETURN EXCHANGE (METHOD)
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

        $this->saleReturnExchange = SaleReturnExchange::create([
            'company_id'                            => Auth::user()->id,
            'sale_id'                               => $request->sale_id,
            'customer_id'                           => $request->customer_id,
            'date'                                  => $request->date ?? date('Y-m-d'),
            'total_return_quantity'                 => $request->total_return_quantity ?? array_sum($request->return_quantity),
            'total_return_cost'                     => $request->total_return_cost ?? 0,
            'return_subtotal'                       => $request->total_return_subtotal ?? 0,
            'total_return_discount_percent'         => $request->total_return_discount_percent ?? 0,
            'total_return_discount_amount'          => $request->total_return_discount_amount ?? 0,
            'total_exchange_quantity'               => $request->total_exchange_quantity ?? array_sum($request->product_qty),
            'total_exchange_cost'                   => $request->total_exchange_cost ?? 0,
            'exchange_subtotal'                     => $request->total_exchange_subtotal ?? 0,
            'total_exchange_discount_percent'       => $request->total_exchange_discount_percent ?? 0,
            'total_exchange_discount_amount'        => $request->total_exchange_discount_amount ?? 0,
            'total_return_vat_percent'              => $request->total_return_vat_percent ?? 0,
            'total_return_vat_amount'               => $request->total_return_vat_amount ?? 0,
            'total_exchange_vat_percent'            => $request->total_exchange_vat_percent ?? 0,
            'total_exchange_vat_amount'             => $request->total_exchange_vat_amount ?? 0,
            'rounding'                              => floor($paid_amount) ?? 0,
            'paid_amount'                           => $paid_amount ?? 0,
            'due_amount'                            => $request->due_amount ?? 0,
            'change_amount'                         => $request->change_amount ?? 0,
            'dokan_id'                              => dokanId(),
            'branch_id'                             => $request->branch_id ?? null,
        ]);
        if($this->saleReturnExchange){
            $sale_id = sprintf("%06d", $this->saleReturnExchange->id);
            SaleReturnExchange::find($this->saleReturnExchange->id)->update([
                'invoice_no'    => 'SREX' . '-' . date('y') . $sale_id,
            ]);
        }

        $this->saleReturn = $this->storeSaleReturns($request);
        if($request->product_ids){
            $this->saleExchange = $this->storeSaleExchanges($request);
            $this->createSales($request);
        }

        $this->transaction($request);

        //         INCREMENT / DECREMENT ACCOUNT BALANCE
        if($request->paid_amount < 0){
            (new AccountService())->decreaseBalance($request->account_ids, abs($request->paid_amount));
        }else{
            (new AccountService())->increaseBalance($request->account_ids, abs($request->paid_amount));
        }

        //         INCREMENT / DECREMENT CUSTOMER ACC BALANCE
        if($request->due_amount < 0){
            $this->cutomerTransaction($request->customer_id, abs($request->due_amount), "In");
        }else{
            $this->cutomerTransaction($request->customer_id, abs($request->due_amount), "Out");
        }





        return $this->saleReturnExchange;

    }



    public function transaction($request){

        if($request->account_ids){

            (new AccountService())->multiAccount(
                $this->saleReturnExchange->id,
                'Sale Return Exchange',
                $request->account_ids,
                abs($request->paid_amount),
                $request->check_no ?? null,
                $request->check_date ?? null
            );

            (new CashFlowService())->transaction(
                $this->saleReturnExchange->id,
                'Sale Return Exchange',
                abs($request->paid_amount),
                $request->paid_amount < 0 ? 'Out' : 'In',
                'Sale Return Exchange',
                $request->account_ids
            );
        }

    }



    public function cutomerTransaction($customer_id, $due_amount, $type){
        $customer = Customer::find($customer_id);
        if($type == 'In'){
            $customer->increment('balance', $due_amount);
        }else{
            $customer->decrement('balance', $due_amount);
        }

        $cust_ledger = CustomerLedger::create([
            'customer_id'   => $customer_id,
            'balance_type'  => $type,
            'source_type'   => 'Sale Return Exchange',
            'source_id'     => $this->saleReturnExchange->id,
            'amount'        => $due_amount,
            // 'date'          => date('Y-m-d')
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






    /*
     |--------------------------------------------------------------------------
     | STORE SALE RETURN EXCHANGE DETAILS (METHOD)
     |--------------------------------------------------------------------------
    */
    public function storeSaleReturnExchangeDetails($request, $damage = null)
    {
        //dd($request->all());
        foreach($request->return_product_id ?? [] as $key => $return_product_id) {


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
