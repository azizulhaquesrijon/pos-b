<?php


namespace Module\Dokani\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\Order;
use Module\Dokani\Models\Product;
use Module\Dokani\Services\OrderService;

class OrderController extends Controller
{


    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(OrderService $orderService)
    {
        $this->service = $orderService;
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data  = Order::with('customer:id,name')->with('order_details.product')->latest()->dokani()->paginate(25);
        return response()->json([
            'data'      => $data,
            'message'   => "Success",
            'status'    => 1,
        ]);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data['categories'] = [];
        $data['customers']  = Customer::dokani()->pluck('name', 'id');
        $data['products']   = Product::dokani()->latest()->select('sell_price as product_price', 'id', 'name', 'barcode', 'category_id')->paginate(25);
        return view('sales.sales.create-new', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'       => 'required',
            'payable_amount'    => 'required',
            'discount'          => 'required',
            'paid_amount'       => 'required',
            'due_amount'        => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([

                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }
        try {

            return DB::transaction(function () use ($request) {

                $this->service->store($request);
                $this->service->saleDetails($request);

                return response()->json([
                    'data'      => $this->service->sale,
                    'message'   => "Success",
                    'status'    => 1,
                ]);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 0,
            ]);
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

            $data = Sale::with('details')->find($id);
            return response()->json([
                'data'      => $data,
                'message'   => "Success",
                'status'    => 1,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 0,
            ]);
        }
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
            DB::transaction(function () use ($id) {
                $this->service->saleDelete($id);
            });
            return response()->json([
                'data'      => 'Sale Deleted',
                'message'   => "Success",
                'status'    => 1,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 0,
            ]);
        }
    }

}
