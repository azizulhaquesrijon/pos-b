<?php

namespace Module\Dokani\Controllers\Api;

use App\Traits\FileSaver;
use Illuminate\Http\Request;
use Module\Dokani\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
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
        try {
            $data['customers'] = Customer::dokani()
                                ->likeSearch('name')
                                ->latest()
                                ->paginate(25);
                               
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

                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }
        try {
            $data['customer'] = Customer::create($request->except('image'));

            if ($data['customer'] ) {
                return response()->json([
                    'data'      => $data['customer'] ,
                    'message'   => "Success",
                    'status'    => 1,
                ]);
            }
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
        try{
            $data['customer'] = Customer::find($id);

            return response()->json([
                'data'      => Customer::find($id),
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
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name'          => 'required',
            'mobile'        => 'nullable',
            'address'       => 'nullable',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }
        try {
            $data['customer'] = $this->storeOrUpdate($request, $id);

            if ($data['customer']) {
                return response()->json([
                    'data'      => $data,
                    'message'   => "Success",
                    'status'    => 1,
                ]);
            }
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
            $customer = Customer::find($id);
            if (file_exists($customer->image)) {
                unlink($customer->image);
            }
            $customer->delete();
            return response()->json([

                'data'      => 'Customer delete success',
                'message'   => "Success",
                'status'    => 1,
            ]);
        } catch (\Throwable $th) {
            return response()->json([

                'data'      => 'Customer was used another table',
                'message'   => "Server Error",
                'status'    => 0,
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
        $data = $request->all();

        $data['opening_due'] =  $request->opening_due ?? 0;



        $customer = Customer::updateOrCreate([
            'id'    => $id
        ], $data);

        $this->upload_file($request->image, $customer, 'image', 'customers');
    }
}
