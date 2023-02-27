<?php

namespace Module\Dokani\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Purchase;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
        $data  = Purchase::with('supplier')->with('details.product')->latest()->dokani()->paginate(25);
        return response()->json([
            'data'      => $data,
            'message'   => "Success",
            'status'    => 1,
        ]);
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
                'supplier_id'       => 'required',
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
            DB::transaction(function () use ($request) {

                $this->service->store($request);
                $this->service->details($request);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 0,
            ]);
        }
        return response()->json([
            'data'      => $this->service->purchase,
            'message'   => "Success",
            'status'    => 1,
        ]);
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

            $data['purchase'] = Purchase::with('details')->find($id);
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
            return response()->json([
                'data'      => 'Purchase Deleted',
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
