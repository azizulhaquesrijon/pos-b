<?php

namespace Module\Dokani\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Category;
use Module\Dokani\Models\Purchase;
use Module\Dokani\Models\Supplier;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\PurchaseOrder;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Services\PurchaseService;

class PurchaseController extends Controller
{
    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(PurchaseService $purchaseService)
    {
        $this->service = $purchaseService;
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['purchases']  = Purchase::with('supplier')->latest()->dokani()
            ->dateFilter()
            ->searchFromRelation('supplier','name')
            ->likeSearch('invoice_no')
            ->paginate(25);

        $data['grans_purchases'] = Purchase::dokani()->with('details')->get();
        return view('purchases.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data['categories'] = Category::dokani()->pluck('name', 'id');
        $data['suppliers']  = Supplier::dokani()->get();
        $data['invoice']    = BusinessSetting::where('user_id', User::first()->id)->first();
        $data['products']   = Product::dokani()->latest()->select('purchase_price as product_price', 'id', 'name', 'barcode', 'category_id', 'image')->paginate(25);

        return view('purchases/create', $data);
    }



    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function createPurchase()
    {
        $data['suppliers']  = Supplier::dokani()->get();
        $data['invoice']    = BusinessSetting::where('user_id', User::first()->id)->first();

        return view('purchases/create-purchases', $data);
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
            if($request->product_ids){

                DB::transaction(function () use ($request) {

                    $this->service->store($request);
                    $this->service->details($request);

                    if($request->purchase_order_id){
                        PurchaseOrder::dokani()->find($request->purchase_order_id)->update([
                            'purchase_id'       => $this->service->purchase->id
                        ]);

                    }
                });

            }else{
                return redirect()->route('dokani.purchases.create')->with('error','Select Product First');
            }


        } catch (\Throwable $th) {
            dd($th->getMessage());
            redirectIfError($th, 1);
        }
        if($this->service->purchase){
            $url = route('dokani.purchases.show', $this->service->purchase->id) . '?type=' . $request->invoice_type;
            return redirect($url);
        }else{
            return redirect()->route('dokani.purchases.create')->with('error','Purchase did\'t Created');
        }

    }








    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id, Request $request)
    {
        try {
            $view = $request->type == 'pos-invoice' ? 'purchases.pos-invoice' : 'purchases.show';

            $data['purchase'] = Purchase::with('details','multi_accounts.account')->where('id',$id)->first();
            $data['business_settings'] = BusinessSetting::query()->where('user_id',dokanId())->first();

            return view($view, $data);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage());
        }
    }













    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id, Request $request)
    {
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
            DB::transaction(function () use ($id) {
                $this->service->purchaseDelete($id);

            });
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()->withError($th->getMessage());
        }
        return redirect()->route('dokani.purchases.index')->withMessage('Purchase deleted successfully !');

    }









    /*
     |--------------------------------------------------------------------------
     |          CHECH PURCHASE REFERENCE NO UNIQUE     METHOD
     |--------------------------------------------------------------------------
    */
    public function checkReferenceNoUnique(Request $request){
        // return response()->json($request->all());
        $purchase_count = Purchase::dokani()->where('reference_no', $request->value)->get()->count();
        if($purchase_count > 0){
            $status = 0;
        }else{
            $status = 1;
        }
        return response()->json([
            'status'   => $status,
        ]);
    }
}
