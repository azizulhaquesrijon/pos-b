<?php

namespace Module\Dokani\Controllers\Api;

use Illuminate\Http\Request;
use Module\Dokani\Models\GParty;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\GeneralAccount;
use Module\Dokani\Models\VoucherPayment;
use Illuminate\Support\Facades\Validator;
use Module\Dokani\Services\VoucherPaymentService;

class VoucherPaymentController extends Controller
{
    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(VoucherPaymentService $voucherPaymentService)
    {
        $this->service = $voucherPaymentService;
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {

        return response()->json([
            'status'    => 1,
            'message'   => 'Success',
            'data'      => VoucherPayment::dokani()->with('party:id,name','account:id,name')->where('type', $request->type)->latest()->paginate(25),
        ]);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        $data['parties']            = GParty::dokani()->get(['name', 'id', 'mobile']);
        $data['general_accounts']   = GeneralAccount::dokani()->where('type', $request->type)->get();


        return view('general-accounts.voucher-payments.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'party_id'              => 'required',
            'account_id'            => 'required',
            'date'                  => 'required',
            'amount'                => 'required',
            'chart_of_account_ids'  => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([

                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }

        try {
            DB::transaction(function () use ($request) {
                $this->service->store($request);
                $this->service->details($request);
            });

            return response()->json([
                'data'      => $this->service->voucherPayment,
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
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        return response()->json([
            'status'    => 1,
            'message'   => 'Success',
            'data'      => VoucherPayment::dokani()->with('party:id,name','account:id,name')->find($id),
        ]);
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
                $this->service->vocuherDelete($id);
            });
            return response()->json([
                'data'      => 'Deleted',
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
