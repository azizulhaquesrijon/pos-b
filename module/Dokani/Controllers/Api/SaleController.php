<?php

namespace Module\Dokani\Controllers\Api;

use Illuminate\Http\Request;
use Module\Dokani\Models\Sale;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Customer;
use App\Http\Controllers\Controller;
use Module\Dokani\Services\SaleService;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(SaleService $saleService)
    {
        $this->service = $saleService;
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data  = Sale::with('customer:id,name')->with('details.product')->latest()->dokani()->paginate(25);
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
        /*--------====== VALIDATION =======-------*/
        $validator = Validator::make($request->all(), [
            'customer_id'       => 'required',
            'payable_amount'    => 'required',
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
      /*--------------- END VALIDATION -------------*/




      /*-------======= MAIN TRANSACITON ======------*/
        try {
            DB::transaction(function () use ($request) {
                $this->service->store($request);
                $this->service->saleDetails($request);
            });

            $data['sale'] = $this->service->sale;

            return response()->json([
                'status'    => 1,
                'message'   => "Success",
                'data'      => $data,
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => "Server Error",
                'data'      => $th->getMessage(),
            ]);
        }
       /*------------- END MAIN TRANSACITON -----------*/

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
