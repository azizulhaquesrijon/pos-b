<?php

namespace Module\Dokani\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Module\Dokani\Models\Brand;

class BrandController extends Controller
{




    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
   */
    public function __construct()
    {
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        try {
            $data['brands'] = Brand::latest()->dokani()->paginate(25);
           
            return response()->json([
                'status'   => 1,
                'message'  => 'Success',
                'data'     => $data
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status'   => 0,
                'message'  => "Error",
                'data'     => $th->getMessage(),
            ]);
        }
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data = [];
        return view('', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    =>  'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 0,
                'message'   => "Validation Error",
                'data'      => $validator->errors()->first(),
            ]);
        }

        try {
            $data['brand'] = $this->storeOrUpdate($request);

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
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        try{
            $data['brand'] = Brand::find($id);

            return response()->json([
                'status'   => 1,
                'message'  => 'Success',
                'data'     => $data
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status'   => 0,
                'message'  => "Error",
                'data'     => $th->getMessage(),
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
    public function updateBrand(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name'          => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 0,
                'message'   => "Validation Error",
                'data'      => $validator->errors()->first(),
            ]);
        }
        try {
            $data['brand'] = $this->storeOrUpdate($request, $id);

            if ($data['brand'] ) {
                return response()->json([
                    'status'    => 1,
                    'message'   => "Success",
                    'data'      => $data,
                ]);
            }
        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => "Server Error",
                'data'      => $th->getMessage(),
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
            $brand = Brand::find($id);

            $brand->delete();

            return response()->json([
                'status'    => 1,
                'message'   => "Success",
                'data'      => 'Brand delete success',
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => "Server Error",
                'data'      => $th->getMessage(),
            ]);
        }
    }



    /*
        |--------------------------------------------------------------------------
        | STORE/UPDATE METHOD
        |--------------------------------------------------------------------------
    */
    private function storeOrUpdate($request, $id = null)
    {
        $data  = $request->all();
        $brand = Brand::updateOrCreate([
                    'id'    => $id
                ], $data);

        return $brand ;
    }




}
