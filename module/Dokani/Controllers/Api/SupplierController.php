<?php

namespace Module\Dokani\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\FileSaver;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\Supplier;

class SupplierController extends Controller
{

    use FileSaver;

    private $service;


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
        try{
            $data['suppliers'] = Supplier::dokani()
                                ->likeSearch('name')
                                ->latest()
                                ->paginate(25);

            return response()->json([
                'status'       => 1,
                'message'      => 'Success',
                'data'         => $data
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
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {
            $data['supplier']   = $this->storeOrUpdate($request);
            
            if ($data['supplier']) {
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
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        try{
            $data['suppliers'] = Supplier::find($id);

            return response()->json([
                'status'       => 1,
                'message'      => 'Success',
                'data'         => $data
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
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function updateSupplier($id, Request $request)
    {
        try {
            $this->storeOrUpdate($request, $id);

            return response()->json([
                'status'    => 1,
                'message'   => "Success",
                'data'      => 'Supplier update success',
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
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            $supplier = Supplier::find($id);

            if (file_exists($supplier->image)) {
                unlink($supplier->image);
            }
            $supplier->delete();

            return response()->json([
                'status'    => 1,
                'message'   => "Success",
                'data'      => 'Supplier delete success',
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
        $data = $request->validate([
            'name'          => 'required',
            'mobile'        => 'nullable',
            'address'       => 'nullable',
        ]);

        $data['opening_due'] =  $request->opening_due ?? 0;

        $supplier = Supplier::updateOrCreate([
            'id'    => $id
        ], $data);

        $this->upload_file($request->image, $supplier, 'image', 'suppliers');
        return $supplier;
    }
}
