<?php

namespace Module\Dokani\Controllers\Api\Product;

use App\Traits\Trycatch;
use Illuminate\Http\Request;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Category;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\ProductLedger;
use Illuminate\Support\Facades\Validator;
use Module\Dokani\Models\ProductStockLog;
use Module\Dokani\Services\ProductService;

class ProductController extends Controller
{

    use Trycatch;


    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(ProductService $productService)
    {
        $this->service = $productService;
    }




    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        return $this->load(Product::dokani()
        ->with('category:id,name', 'unit:id,name', 'stocks:id,product_id,available_quantity')
        ->searchByField('barcode')
        ->likeSearch('name')
        ->latest()
        ->paginate(30));
    }











    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [

                'name'              =>  'required',
                'purchase_price'    =>  'required',
                'sell_price'        =>  'required',
                'unit_id'           =>  'required',
                'barcode'           =>  'required',
            ]);


            if ($validator->fails()) {

                return response()->json([

                    'data'      => $validator->errors()->first(),
                    'message'   => "Validation Error",
                    'status'    => 0,
                ]);
            }

            return DB::transaction(function () use ($request) {

                $this->service->storeOrUpdate($request);
                if ($request->opening_stock > 0){
                    $this->service->stockUpdateOrCreate($request);
                }

                return response()->json([

                    'data'      => $this->service->product,
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
    public function show($id)
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
        try {
            $validator = Validator::make($request->all(), [

                'name'              =>  'required',
                'purchase_price'    =>  'required',
                'sell_price'        =>  'required',
                'unit'              =>  'required',
                'barcode'           =>  'required',
            ]);

            if ($validator->fails()) {

                return response()->json([

                    'data'      => $validator->errors()->first(),
                    'message'   => "Validation Error",
                    'status'    => 0,
                ]);
            }

            return DB::transaction(function () use ($request, $id) {

                $this->service->storeOrUpdate($request, $id);

                return response()->json([
                    'data'      => $this->service->product,
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
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {

        try {
            DB::transaction(function () use ($id) {

                $product = Product::find($id);
                if (file_exists($product->image)) {
                    unlink($product->image);
                }

                ProductStock::where('product_id', $product->id)->delete();

                ProductStockLog::where('product_id', $product->id)->delete();

                ProductLedger::where('product_id', $product->id)->delete();

                $product->delete();


            });


            return response()->json([
                'data'      => [],
                'message'   => 'Success',
                'status'    => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data'      => $th->getMessage(),
                'message'   => 'Server Error',
                'status'    => true,
            ]);
        }
    }


}
