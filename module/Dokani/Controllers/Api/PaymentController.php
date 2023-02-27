<?php

namespace Module\Dokani\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\Payment;
use Module\Dokani\Models\Supplier;
use Module\Dokani\Requests\PaymentRequest;

class PaymentController extends Controller
{
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
        return Payment::with('supplier:id,name,mobile')->dokani()->paginate(25);
    }










    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(PaymentRequest $request)
    {
        try {
            $data = $request->store();
        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 0,
            ]);
        }
        return response()->json([

            'data'      => $data,
            'message'   => "Success",
            'status'    => 0,
        ]);
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
        # code...
    }
}
