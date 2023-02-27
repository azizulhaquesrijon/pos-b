<?php

namespace Module\Dokani\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\Payment;
use Module\Dokani\Models\Supplier;
use Module\Dokani\Models\SupplierLedger;
use Module\Dokani\Requests\PaymentRequest;
use Module\Dokani\Services\AccountService;

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
        $data['payments'] = Payment::dokani()->searchFromRelation('supplier','name')->paginate(25);
        return view('purchases/payment/index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data['suppliers'] = Supplier::dokani()->get();
        return view('purchases/payment/create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(PaymentRequest $request)
    {
        try {
            $request->store();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
        return redirect()->back()->with('message', 'Payment successfull');
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $data['payment'] = Payment::dokani()->with(['supplier','account'])->find($id);
        $data['business_settings'] = BusinessSetting::query()->where('user_id',dokanId())->first();

        return view('purchases.payment.show', $data);
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
        DB::transaction(function () use ($id) {

            $payment           = Payment::find($id);

            $supplier           = Supplier::find($payment->supplier_id);

            $supplier->increment('balance', $payment->paid_amount);
            (new AccountService())->increaseBalance($payment->account_id, $payment->paid_amount);

            $supplierLedger   = SupplierLedger::dokani()->where('source_type','Payment')->where('source_id', $id)->first();
            $cash_flow = CashFlow::dokani()->where('transactionable_type','Payment')->where('transactionable_id',$id)->first();
            $cash_flow->delete();
            $supplierLedger->delete();

            $payment->delete();

        });

        return redirect()->back()->with('message', 'Successfully Deleted');

    }
}
