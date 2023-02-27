<?php

namespace Module\dokani\Controllers;

use Illuminate\Http\Request;
use Module\Dokani\Models\Product;
use Module\Dokani\Models\Purchase;
use Module\Dokani\Models\Supplier;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\SaleDetail;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Models\PurchaseReturnExchange;
use Module\Dokani\Services\PurchaseReturnExchangeService;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Account;

class PurchasesReturnExchangeController extends Controller
{
    private $service;
    public $purchaseReturnExchangeService;
    public $purchaseReturnExchange;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->purchaseReturnExchangeService    = new PurchaseReturnExchangeService;

    }











    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $purchaseReturnExchanges = PurchaseReturnExchange::latest()->paginate(25);
        return view('purchases.return-exchanges.index', compact('purchaseReturnExchanges'));
    }












    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        $data['purchaseInvoices']   = Purchase::dokani()->pluck('invoice_no');
        $data['purchaseReferences']   = Purchase::dokani()->where('reference_no','!=', null)->pluck('reference_no');

        if($request->filled('invoice_no')){
            $data['purchase'] = Purchase::query()->dokani()
                                ->with(['details' => function($q){
                                    $q->with('stock:lot,available_quantity');
                                }])
                                ->when($request->filled('supplier_id'), function ($q) use ($request) {
                                    $q->where('supplier_id', $request->supplier_id);
                                })
                                ->when($request->filled('invoice_no'), function ($q) use ($request) {
                                    $q->where('invoice_no', $request->invoice_no);
                                })
                                ->first();
        }else{
            $data['purchase'] = [];
        }
        $data['suppliers']      = Supplier::dokani()->with('purchases:id,supplier_id,invoice_no,reference_no')->get(['id','name','mobile']);
        $data['products']       = Product::dokani()->get(['id', 'name', 'barcode', 'category_id', 'unit_id']);

        $data['accounts']       = Account::dokani()->get();

        return view('purchases.return-exchanges.create', $data);

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
        try {
            DB::transaction(function () use ($request) {

                $this->purchaseReturnExchange = $this->purchaseReturnExchangeService->store($request);

            });
            // $url = route('dokani.purchase-return-exchanges.show', $this->purchaseReturnExchange->id) . '?print_type='.$request->print_type;
            $url = route('dokani.purchase-return-exchanges.show', $this->purchaseReturnExchange->id) . '?print_type=normal-print';
            return redirect($url);

        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id, Request $request)
    {
        $purchaseReturnExchange = PurchaseReturnExchange::with('purchaseReturns', 'purchaseExchanges')->find($id);
        $business_settings = BusinessSetting::query()->where('user_id',dokanId())->first();
        if ($request->print_type == 'normal-print') {

            $supplier = $purchaseReturnExchange->supplier;

            return view('purchases.return-exchanges.invoice', compact('purchaseReturnExchange', 'supplier','business_settings'));
        }

        $supplier = $purchaseReturnExchange->supplier;

        return view('purchases.return-exchanges.pos-print', compact('purchaseReturnExchange', 'supplier','business_settings'));
    }













    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        # code...
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        # code...
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {

            $purchaseReturnExchange = PurchaseReturnExchange::with('purchaseReturns.purchase_details','purchaseExchanges')->find($id);

            DB::transaction(function () use($purchaseReturnExchange){

                $this->purchaseReturnExchangeService->deletePurchaseReturn($purchaseReturnExchange->purchaseReturns);

                if($purchaseReturnExchange->purchaseExchanges){
                    $this->purchaseReturnExchangeService->deletePurchaseExchanges($purchaseReturnExchange->purchaseExchanges);
                }

                $this->purchaseReturnExchangeService->deleteSupplierDue($purchaseReturnExchange);



                $purchaseReturnExchange->delete();
            });

            return redirect()->back()->with('success', 'Purcahse Return Exchange has been deleted');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
