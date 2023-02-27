<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use Module\Dokani\Models\Sale;
use App\Traits\CheckPermission;
use Module\Dokani\Models\Branch;
use Module\Dokani\Models\Account;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Module\Dokani\Models\SaleDetail;
use Module\Dokani\Models\SaleReturn;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\SaleExchange;
use Module\Dokani\Models\ProductDamage;
use Module\Dokani\Models\ProductLedger;
use Module\Dokani\Services\AjaxService;
use Module\Dokani\Models\CustomerLedger;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Models\MultiAccountPay;
use Module\Dokani\Models\ProductStockLog;
use Module\Dokani\Services\AccountService;
use Module\Dokani\Models\SaleReturnExchange;
use Module\Dokani\Services\ProductStockService;
use Module\Dokani\Services\SaleReturnExchangeService;

class SaleReturnExchangeController extends Controller
{
    use CheckPermission;


    public $saleReturnExchangeService;
    public $productDamageService;
    public $stockService;
    public $productExchange;
    public $invoiceNumberService;



    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->saleReturnExchangeService    = new SaleReturnExchangeService;

    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        if(auth()->user()->type == 'owner'){
            $data['branches'] = $branches = Branch::dokani()->with('employee')->get();
        }else{
            $data['branches'] = $branches = Branch::dokani()
                                ->whereHas('users',fn($q)=>$q->where('user_id',auth()->user()->id))
                                ->with('employee')
                                ->get();
        }
        $data['saleReturnExchanges'] = SaleReturnExchange::dokani()
                                        ->dateFilter()
                                        ->searchByBranch('branch_id')
                                        ->searchFromRelation('customer','name')
                                        ->likeSearch('invoice_no')
                                        ->latest()
                                        ->paginate(25);

        return view('sales/return-exchanges/index', $data);
    }












    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        $data['saleInvoices']     = Sale::pluck('invoice_no');
        // $data['branches']         = Branch::dokani()->get();
        if(auth()->user()->type == 'owner'){
            $data['branches'] = $branches = Branch::dokani()->with('employee')->get();
        }else{
            $data['branches'] = $branches = Branch::dokani()
                                ->whereHas('users',fn($q)=>$q->where('user_id',auth()->user()->id))
                                ->with('employee')
                                ->get();
        }


        $data['sale']            = Sale::query()->dokani()
                                    ->with('customer','branch','sale_details.product_stock_log')     //,'sale_return_exchange.sale_return.saleReturnDetails'
                                    ->with(['sale_details'=> function($q){
                                        $q->withSum('sale_return_details', 'quantity');
                                    }] ?? [])
                                    ->when($request->filled('customer_id'), function ($q) use ($request) {
                                        $q->where('customer_id', $request->customer_id);
                                    })
                                    ->when($request->filled('invoice_no'), function ($q) use ($request) {
                                        $q->where('invoice_no', $request->invoice_no);
                                    })
                                    ->first();

        $data['customers']        = Customer::dokani()->with('sales:id,customer_id,invoice_no')->get(['id','name','mobile','discount']);
        $data['products']         = Product::dokani()->get(['id', 'name', 'barcode', 'category_id', 'unit_id']);
        $data['accounts']         = Account::dokani()->get();
        $data['business_setting'] = BusinessSetting::whereUserId(auth()->user()->type == "owner" ? auth()->user()->id : auth()->user()->dokan_id)->first();

        // dd($data['sale']);
        return view('sales/return-exchanges/create', $data);

    }












    /*
     |--------------------------------------------------------------------------
     | GET RETURN PRODUCT METHOD
     |--------------------------------------------------------------------------
    */
    public function getReturnProduct(Request $request)
    {

        $data['getReturnProduct']   = SaleDetail::where('id', !empty($getBarcode) ? $getBarcode->sale_detail_id : $request->sale_detail_id)
                                    ->with(['product' => function ($q) {
                                        $q->with('unit:id,name')
                                            ->with('category:id,name')
                                            ->select('id', 'name', 'barcode', 'category_id', 'unit_id');
                                    }])
                                    ->first();

        return $data;
    }












    /*
     |--------------------------------------------------------------------------
     | GET EXCHANGE PRODUCT METHOD
     |--------------------------------------------------------------------------
    */
    public function getExchangeProduct(Request $request)
    {

        $data['getExchangeProduct']   = Product::dokani()->where('barcode' , $request->search)
            ->with('unit:id,name')
            ->with('category:id,name')
            ->with('stocks')
            ->first();

        return $data;
    }












    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // dd($request->all());
        DB::transaction(function () use ($request) {

            $this->productExchange = $this->saleReturnExchangeService->storeSaleProductExchange($request);

        });

    //    if ($request->print_type == 'pos-print') {
    //        $url = route('dokani.sale-return-exchanges.pos-print', ['id' => $this->productExchange->id, 'print_type' => 'pos-print']);
    //    } else {
    //        $url = route('dokani.sale-return-exchanges.invoice.blade', ['id' => $this->productExchange->id, 'print_type' => 'normal-print']);
    //    }

        return redirect()->route('dokani.sale-return-exchanges.index')->withMessage('Sale Return & Exchange Create Successfully');
        // return redirect()->route($url)->withMessage('Sale Return & Exchange Create Successfully');
    }









    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show(Request $request, $id)
    {
        $business_settings  = BusinessSetting::query()->where('user_id',dokanId())->first();
        $saleReturnExchange = SaleReturnExchange::with('sale_return.saleReturnDetails','sale_exchage.saleExchangeDetail', 'created_user')
                            ->with(['sale' => function($q){
                                $q;
                            }])->find($id);
        if ($request->print_type == 'normal-print') {
            $customer = $saleReturnExchange->customer;

            return view('sales.return-exchanges.invoice', compact('saleReturnExchange', 'customer','business_settings'));
        }

        $customer = $saleReturnExchange->customer;

        return view('sales.return-exchanges.pos-print', compact('saleReturnExchange', 'customer','business_settings'));
    }






    /*
     |--------------------------------------------------------------------------
     | DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        // dd($id);
        // try {
            DB::transaction(function () use ($id) {

                $saleReturnExchange = SaleReturnExchange::dokani()
                                    ->with('sale_return.saleReturnDetails','sale_exchage.saleExchangeDetail','customer','sale')
                                    ->find($id);


                //    Sale Return Details
                foreach (optional($saleReturnExchange->sale_return)->saleReturnDetails ?? [] as $sateReturnDetail){
                    $stock_log = ProductStockLog::dokani()->where([
                                    ['sourceable_type', 'Sale Return Details'],
                                    ['sourceable_id', $sateReturnDetail->id],
                                    ['product_id', $sateReturnDetail->product_id],
                                    ['branch_id', $saleReturnExchange->branch_id]
                                ])->first();
                        // dd($stock_log);
                    $product_ledger = ProductLedger::dokani()
                                    ->where([
                                        ['product_id',$stock_log->product_id],
                                        // ['branch_id', $stock_log->branch_id],
                                        ['sourceable_type', 'Sale Return Details'],
                                        ['sourceable_id', $stock_log->sourceable_id]
                                        ]);
                    $stock = ProductStock::dokani()->where([['product_id', $stock_log->product_id],['branch_id', $stock_log->branch_id],['lot', $stock_log->lot ?? 'N/A'],['sold_return_quantity','>',0]])->first();
                    $stock->decrement('sold_return_quantity', $stock_log->quantity);

                    if ($sateReturnDetail->condition == 'damaged') {
                        $damage = ProductDamage::dokani()->where([
                            ['source_type', 'Sale Return Exchange'],
                            ['source_id', $saleReturnExchange->id]
                            ])->with('productDamageDetails_when_saleResultExchange')->first();

                            $stock = (new ProductStockService())->getStock(
                                $stock_log->product_id,
                                $stock_log->lot,
                                $stock_log->branch_id ?? null
                            );
                            $stock->decrement('wastage_quantity', $damage->productDamageDetails_when_saleResultExchange->quantity ?? 0);

                        $damage->productDamageDetails_when_saleResultExchange->delete();
                        $damage->delete();
                    }

                    $stock_log->delete();
                    $product_ledger->delete();
                    $sateReturnDetail->delete();

                }

                //       Sale Exchange Details
                foreach (optional($saleReturnExchange->sale_exchage)->saleExchangeDetail ?? [] as $sateExchangeDetail){
                    $stock_log = ProductStockLog::dokani()->where([
                                    ['sourceable_type', 'Sale Exchange Details'],
                                    ['sourceable_id', $sateExchangeDetail->id],
                                    ['product_id', $sateExchangeDetail->product_id],
                                    ['branch_id', $saleReturnExchange->branch_id]
                                ])->first();

                    $product_ledger = ProductLedger::dokani()
                                    ->where([
                                        ['product_id',$stock_log->product_id],
                                        // ['branch_id', $stock_log->branch_id],
                                        ['sourceable_type', 'Sale Exchange Details'],
                                        ['sourceable_id', $stock_log->sourceable_id]
                                        ]);

                    $stock = ProductStock::dokani()->where([['product_id', $stock_log->product_id],['branch_id', $stock_log->branch_id],['lot', $stock_log->lot],['sold_exchange_quantity','>',0]])->first();
                    $stock->decrement('sold_exchange_quantity', $stock_log->quantity);

                    $stock_log->delete();
                    $product_ledger->delete();
                    $sateExchangeDetail->delete();
                }

                /** CustLedger => In -> mean CustBalance + hoyeche | Out -> mean CustBalance - hoyeche  */
                //    CUSTOMER
                $customer_ledger = CustomerLedger::dokani()->where([
                                    ['customer_id', $saleReturnExchange->customer_id],
                                    ['source_type', 'Sale Return Exchange'],
                                    ['source_id', $saleReturnExchange->id]
                                ])->first();

                $cust = Customer::find($saleReturnExchange->customer_id);

                if($customer_ledger->balance_type == 'Out'){
                    $cust->increment('balance', $customer_ledger->amount);
                }else{
                    $cust->decrement('balance', $customer_ledger->amount);
                }
                $customer_ledger->delete();


                //        Accounts Services
                $multi_acc = MultiAccountPay::dokani()
                                        ->where([
                                            ['source_type', 'Sale Return Exchange'],
                                            ['source_id', $saleReturnExchange->id]
                                        ])->first();
                if($saleReturnExchange->paid_amount < 0){
                    (new AccountService())->increaseBalance($multi_acc->account_id, abs($multi_acc->amount));
                }else{
                    (new AccountService())->decreaseBalance($multi_acc->account_id, abs($multi_acc->amount));
                }

                $multi_acc->delete();




                optional($saleReturnExchange->sale_return)->delete();

                optional($saleReturnExchange->sale_exchage)->delete();

                $saleReturnExchange->delete();










            //     foreach($saleReturnExchange->saleReturnExchangeDetails ?? [] as $returnExchangeDetail) {

            //        SaleDetail::where('id', $returnExchangeDetail->sale_detail_id)->update(['return_id' => null]);

            //         $productDamage = ProductDamage::where('sale_id', $saleReturnExchange->sale_id)->with('productDamageDetails')->first();

            //         if ($productDamage != null) {

            //             foreach($productDamage->productDamageDetails ?? [] as $productDamageDetail) {
            //                 $productDamageDetail->delete();
            //             }

            //             $productDamage->delete();
            //         }


            //             $product_stock = ProductStock::where('sale_return_exchange_id', $saleReturnExchange->id)->first();
            //         if ($product_stock){
            //             //$available_quantity = $product_stock->available_quantity;
            //             $sold_return_quantity = $product_stock->sold_return_quantity;
            //             $product_stock->update([

            //                 // 'available_quantity'    => $available_quantity - $returnExchange->total_return_quantity,
            //                 'sold_return_quantity'  => $sold_return_quantity - $saleReturnExchange->total_return_quantity,
            //                 'sale_return_exchange_id'  => null,
            //             ]);
            //         }

            //         $stock_log = ProductStockLog::dokani()->where('sourceable_type','Sale Return')->where('sourceable_id',$returnExchangeDetail->sale_detail_id)->first();

            //         optional($stock_log)->delete();

            //         $saleReturn = SaleReturn::where('id', $returnExchangeDetail->sale_return_id)->first();
            //         $saleExchange = SaleExchange::where('id', $returnExchangeDetail->sale_exchange_id)->first();
            //         $returnExchangeDetail->delete();

            //         if ($saleReturn) {

            //             $saleReturn->delete();
            //         }

            //         if ($saleExchange) {

            //             $saleExchange->delete();
            //         }
            //     }


            //     $saleReturnExchange->delete();
            });


        // } catch (\Throwable $th) {
        //     return redirect()->back()->withError('Something went wrong');
        //     //return redirect()->back()->withError($th->getMessage());
        // }

        return redirect()->back()->withMessage('Sale Return & Exchange Deleted!');
    }
}
