<?php

namespace Module\Dokani\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Module\Dokani\Models\Sale;
use Module\Dokani\Models\Order;
use Module\Dokani\Models\Courier;
use Module\Dokani\Models\CusArea;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\Supplier;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\CusCategory;
use Module\Dokani\Models\PointSetting;
use Module\Dokani\Models\PurchaseOrder;
use Module\Dokani\Services\SaleService;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Services\PurchaseService;
use Module\Dokani\Services\PurchaseOrderService;

class PurchaseOrderController extends Controller
{


    private $service;
    private $saleService;
    private $purchaseService;



    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(PurchaseService $purchaseService)
    {
        $this->service = new PurchaseOrderService();
        $this->purchaseService = $purchaseService;
        $this->saleService = new SaleService();
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['orders']  = PurchaseOrder::with('supplier')->latest()->dokani()->paginate(25);
        return view('purchases/orders/index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
//        $data['categories'] = Category::dokani()->pluck('name', 'id');
        $data['invoice']        = BusinessSetting::where('user_id', User::first()->id)->first();
        $data['suppliers']      = Supplier::dokani()->get();
        $data['users']          = User::whereDokanId(auth()->id())->get();
        $data['areas']          = CusArea::dokani()->get();
        $data['cus_categories'] = CusCategory::dokani()->get();
//        $data['products']   = Product::dokani()->latest()->select('sell_price as product_price', 'id', 'name', 'barcode', 'category_id', 'vat', 'image')->paginate(25);
        return view('purchases/orders/create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {

                $this->service->store($request);
                $this->service->orderDetails($request);

            });

        } catch (\Throwable $th) {
            return redirect()->route('dokani.purchase-orders.index')->withError($th->getMessage());
        }

        return redirect()->route('dokani.purchase-orders.show', $this->service->order->id)->withMessage('Purchase Order added successfully !');
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        try {

            $data['order'] = PurchaseOrder::query()->with('purchsase_order_details')->find($id);

            $data['business_settings'] = BusinessSetting::query()->where('user_id',dokanId())->first();
            // if () {
            //     # code...
            // }
            return view('purchases.orders.show', $data);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage());
        }
    }













    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $data['order']      = PurchaseOrder::query()
                            ->where('id',$id)
                            ->with('purchsase_order_details')
                            ->with('supplier')
                            ->first();

        $data['invoice']    = BusinessSetting::where('user_id', User::first()->id)->first();
        $data['suppliers']  = Supplier::dokani()->get();
        $data['point']      = PointSetting::dokani()->first();
        $data['users']      = User::whereDokanId(auth()->id())->get();
        $data['areas']      = CusArea::dokani()->get();
        $data['cus_categories'] = CusCategory::dokani()->get();
        $data['couriers']   = Courier::dokani()->get();

        return view('purchases.orders.purchase-order',$data);
    }













    /*
     |--------------------------------------------------------------------------
     | ORDER SALE METHOD
     |--------------------------------------------------------------------------
    */
    public function approveSubmit(Request $request, $id){
        // dd($request->all(), $id);
        // try {
            DB::transaction(function () use($request, $id){


                $purchase = $this->purchaseService->store($request);

                $this->purchaseService->details($request);

                $order = PurchaseOrder::find($id)->update([
                    'purchase_id'       => $purchase->id
                ]);


            });
            return redirect()->route('dokani.purchase-orders.index')->with('success', "Purchase Order Approve successfully");
        // } catch (\Throwable $th) {
        //     return redirect()->back()->with('error', $th->getMessage());
        // }
    }









    /*
     |--------------------------------------------------------------------------
     | ORDER SALE METHOD
     |--------------------------------------------------------------------------
    */
    public function orderSale(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {

                $this->saleService->store($request);
                $this->saleService->saleDetails($request);

                Order::dokani()->find($request->order_id)->update([
                    'sale_id'       => $this->saleService->sale->id
                ]);
            });
        } catch (\Throwable $th) {

            return redirect()->route('dokani.sales.index')->withMessage('Something is wrong!');
        }

        $url = route('dokani.sales.show', $this->saleService->sale->id) . '?type=' . $request->invoice_type;

        return redirect($url);
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
                $this->service->orderDelete($id);
            });
        } catch (\Throwable $th) {

            redirectIfError($th);
        }

        return redirect()->route('dokani.orders.index')->withMessage('Order deleted successfully !');
    }
}
