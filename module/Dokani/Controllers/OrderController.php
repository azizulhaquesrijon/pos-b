<?php

namespace Module\Dokani\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Models\Courier;
use Module\Dokani\Models\CusArea;
use Module\Dokani\Models\CusCategory;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\Order;
use Module\Dokani\Models\PointSetting;
use Module\Dokani\Models\Product;
use Module\Dokani\Models\Sale;
use Module\Dokani\Services\OrderService;
use Module\Dokani\Services\SaleService;
use Module\Dokani\Models\Branch;

class OrderController extends Controller
{


    private $service;
    private $saleService;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->service = new OrderService();
        $this->saleService = new SaleService();
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['orders']  = Order::with('customer')->latest()->dokani()->paginate(25);
        return view('orders.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
//        $data['categories'] = Category::dokani()->pluck('name', 'id');
        $data['business_setting']    = BusinessSetting::query()->where('user_id',dokanId())->first();
        // dd($data);
        $data['customers']  = Customer::dokani()->getCustomer()->get();
        $data['users']      = User::whereDokanId(auth()->id())->get();
        $data['areas']      = CusArea::dokani()->get();
        $data['cus_categories'] = CusCategory::dokani()->get();
//        $data['products']   = Product::dokani()->latest()->select('sell_price as product_price', 'id', 'name', 'barcode', 'category_id', 'vat', 'image')->paginate(25);

        if(auth()->user()->type == 'owner'){
            $data['branches'] = $branches = Branch::dokani()->with('employee')->get();
        }else{
            $data['branches'] = $branches = Branch::dokani()->whereHas('users',fn($q)=>$q->where('user_id',auth()->user()->id))->with('employee')->get();
        }

        return view('orders/create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

    //    dd($request->all());

        try {
            DB::transaction(function () use ($request) {

                $this->service->store($request);
                $this->service->orderDetails($request);

            });
        } catch (\Throwable $th) {
            //dd($th->getMessage());
            return redirect()->route('dokani.orders.index')->withMessage($th->getMessage());
        }

        return redirect()->route('dokani.orders.show',$this->service->order->id)->withMessage('Order added successfully !');
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        try {

            $data['order'] = Order::query()->with('order_details')->find($id);

            $data['business_settings'] = BusinessSetting::query()->where('user_id',dokanId())->first();
            return view('orders.show', $data);
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
        $data['order'] = Order::query()
            ->where('id',$id)
            ->with('order_details.product')
            ->with('customer')
            ->first();
        $data['invoice']    = BusinessSetting::where('user_id', User::first()->id)->first();
        $data['customers']  = Customer::dokani()->getCustomer()->get();
        $data['point']      = PointSetting::dokani()->first();
        $data['users']      = User::whereDokanId(auth()->id())->get();
        $data['areas']      = CusArea::dokani()->get();
        $data['cus_categories'] = CusCategory::dokani()->get();
        $data['couriers']   = Courier::dokani()->get();

//        dd($data['order']);
        return view('orders.sale-order',$data);
    }













    /*
     |--------------------------------------------------------------------------
     | ORDER SALE METHOD
     |--------------------------------------------------------------------------
    */
    public function orderSale(Request $request)
    {
    //    dd($request->all());
        $request['inv_prefix'] = 'S';

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
