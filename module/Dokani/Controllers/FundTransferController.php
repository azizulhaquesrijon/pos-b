<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\Account;
use Module\Dokani\Models\FundTransfer;
use Module\Dokani\Services\CashFlowService;

class FundTransferController extends Controller
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
        $data['fundTransfers'] = FundTransfer::dokani()->latest()->paginate(25);
        return view('general-accounts.fund-transfer.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {

        return view('general-accounts.fund-transfer.create');
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
//        dd($request->all());
        $this->validator($request);
        try {
            $this->storeOrUpdate($request);
        } catch (\Throwable $th) {
            redirectIfError($th, 1);
        }
        return redirect()->route('dokani.ac.fund-transfers.index')->with('message', 'Fund Transfer Successfull.');
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
        $this->validator($request);
        try {
            $this->storeOrUpdate($request, $id);
        } catch (\Throwable $th) {
            redirectIfError($th);
        }
        return redirect()->route('dokani.ac.fund-transfers.index')->with('message', 'Fund Transfer Update Successfull.');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            $fundTransfer = FundTransfer::find($id);

            (new CashFlowService())->transaction($fundTransfer->id, 'Fund Transfer', $fundTransfer->amount, 'Out', 'Fund Transfer Delete',$fundTransfer->from_account_id);
            (new CashFlowService())->transaction($fundTransfer->id, 'Fund Transfer', $fundTransfer->amount, 'In', 'Fund Transfer Delete',$fundTransfer->to_account_id);

            $from_accountBalance = Account::query()->where('dokan_id', dokanId())->where('id',$fundTransfer->from_account_id)->first();
            $to_accountBalance = Account::query()->where('dokan_id', dokanId())->where('id',$fundTransfer->to_account_id)->first();

            optional($from_accountBalance)->increment('balance', $fundTransfer->amount);
            optional($to_accountBalance)->decrement('balance', $fundTransfer->amount);

            $fundTransfer->delete();
        } catch (\Throwable $th) {
            redirectIfError($th);
        }
        return redirect()->route('dokani.ac.fund-transfers.index')->with('message', 'Fund Transfer Successfull.');
    }







    /*
     |--------------------------------------------------------------------------
     | CREATED/UPDATED METHOD
     |--------------------------------------------------------------------------
    */
    public function storeOrUpdate($request, $id = null)
    {


        $fundTransfer = FundTransfer::updateOrCreate([
            'id'        => $id,
        ], [
            'from_account_id'   => $request->from_account_id,
            'from_check_no'     => $request->from_check_no,
            'from_check_date'   => $request->from_check_date,
            'to_account_id'     => $request->to_account_id,
            'to_check_no'       => $request->to_check_no,
            'to_check_date'     => $request->to_check_date,
            'amount'            => $request->amount,
            'description'       => $request->description,
        ]);

        $from_accountBalance = Account::query()->where('dokan_id', dokanId())->where('id',$request->from_account_id)->first();
        $to_accountBalance = Account::query()->where('dokan_id', dokanId())->where('id',$request->to_account_id)->first();

        $from_accountBalance->decrement('balance', $request->amount);
        $to_accountBalance->increment('balance', $request->amount);

        (new CashFlowService())->transaction($fundTransfer->id, 'Fund Transfer', $fundTransfer->amount, 'Out', 'Fund Transfer',$fundTransfer->from_account_id);
        (new CashFlowService())->transaction($fundTransfer->id, 'Fund Transfer', $fundTransfer->amount, 'In', 'Fund Transfer',$fundTransfer->to_account_id);
    }






    /*
     |--------------------------------------------------------------------------
     | VALIDATION METHOD
     |--------------------------------------------------------------------------
    */
    private function validator($request)
    {
        return $request->validate([
            'from_account_id'       => 'required',
            'to_account_id'         => 'required|different:from_account_id',
            'amount'                => 'required|numeric',
            'description'           => 'nullable',
        ]);
    }
}
