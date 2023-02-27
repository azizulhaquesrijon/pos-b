<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Models\GParty;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\GeneralAccount;
use Module\Dokani\Models\VoucherPayment;
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
        $data['voucherPayments'] = VoucherPayment::dokani()->where('type', $request->type)->latest()->paginate(25);
        return view('general-accounts.voucher-payments.index', $data);
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
        try {
            DB::transaction(function () use ($request) {
                $this->service->store($request);
                $this->service->details($request);
            });
        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }

        $url = route('dokani.voucher-payments.show',$this->service->voucherPayment->id);
        return redirect($url);

//        return redirect()->route('dokani.voucher-payments.index', ['type' => $request->type])->withMessage('Added successfully !');
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $data['voucher_payment'] = VoucherPayment::dokani()->with(['details.chart_account', 'party'])->find($id);

        $data['business_settings'] = BusinessSetting::query()->where('user_id',dokanId())->first();

        return view('general-accounts.voucher-payments.show', $data);
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
        } catch (\Throwable $th) {
            return redirect()->back()->withError($th->getMessage());
        }

        return redirect()->back()->withMessage('Deleted successfully !');
    }
}
