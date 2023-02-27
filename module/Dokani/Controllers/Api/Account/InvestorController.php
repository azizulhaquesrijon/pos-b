<?php

namespace Module\Dokani\Controllers\Api\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\GParty;
use Module\Dokani\Models\Investor;
use Module\Dokani\Models\InvestorLedger;
use Module\Dokani\Models\InvWithdraw;
use Module\Dokani\Services\AccountService;
use Module\Dokani\Services\CashFlowService;
use Module\Dokani\Services\InvestorLedgerService;

class InvestorController extends Controller
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
        return response()->json([
            'status'    => 1,
            'message'   => 'Success',
            'data'      => Investor::latest()->dokani()->with('g_party','account')->likeSearch('name')->paginate(25)
        ]);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {

        [];
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        try {
            $data = $this->storeOrUpdate($request);
            return response()->json([
                'data'      => $data,
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
        # code...
    }












    /*
     |--------------------------------------------------------------------------
     | BALANCE ADD METHOD
     |--------------------------------------------------------------------------
    */
    public function balanceAdd($id)
    {

        $data['investor'] = Investor::dokani()->with('g_party')->find($id);

        return view('general-accounts.investor.balance-add', $data);
    }










    /*
     |--------------------------------------------------------------------------
     | BALANCE STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function balanceStore($id,Request $request)
    {

        DB::transaction(function () use ($request, $id) {

            Investor::dokani()->find($id)->update([
                'note'          => $request->note,
                'check_no'      => $request->check_no,
                'check_date'    => $request->check_date,
            ]);

            if ($request->amount > 0){

                (new InvestorLedgerService())->investorLedger(
                    $id,
                    $id,
                    'Investor',
                    $request->amount,
                    'In',
                    'Invest Amount Add',
                    $request->date,
                    $request->account_id
                );


                (new CashFlowService())->transaction(
                    $id,
                    'Investor',
                    $request->amount,
                    'In',
                    'Add Invest Amount',
                    $request->account_id,
                    null,
                    $request->date
                );

                (new AccountService())->increaseBalance($request->account_id, $request->amount);

            }

        });

        return response()->json([
            'message'   => "Success",
            'status'    => 1,
        ]);

    }










    /*
     |--------------------------------------------------------------------------
     | DISBURSEMENT METHOD
     |--------------------------------------------------------------------------
    */
    public function disbursement()
    {

        $data['investors'] = Investor::dokani()->with('g_party')->latest()->get();

        return view('general-accounts.investor.disbursement', $data);
    }










    /*
     |--------------------------------------------------------------------------
     | DISBURSEMENT UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function disbursementUpdate(Request $request)
    {
        foreach ($request->investor_ids as $key => $investor_id){

            (new InvestorLedgerService())->investorLedger(
                $investor_id,
                $investor_id,
                'Investor',
                $request->profit_amount[$key],
                'In',
                'Profit Disbursement',
                $request->note,
                null
            );
        }

        return response()->json([
            'message'   => "Success",
            'status'    => 1,
        ]);

    }











    /*
     |--------------------------------------------------------------------------
     | WITHDRAW LIST METHOD
     |--------------------------------------------------------------------------
    */
    public function withdrawList()
    {

        $data['withdraws'] = InvWithdraw::dokani()
            ->searchByField('investor_id')
            ->with('investor.g_party')
            ->latest()->paginate(25);
        $data['investors'] = Investor::dokani()->latest()->with('g_party')->get();

        return response()->json([
            'data'      => $data,
            'message'   => "Success",
            'status'    => 1,
        ]);

    }








    /*
     |--------------------------------------------------------------------------
     | WITHDRAW METHOD
     |--------------------------------------------------------------------------
    */
    public function withdraw()
    {

        $data['investors'] = Investor::dokani()->latest()->with('g_party')->get();

        return view('general-accounts.investor.withdraw', $data);
    }



    /*
     |--------------------------------------------------------------------------
     | WITHDRAW SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function withdrawShow($id)
    {

        $data['withdraw'] = InvWithdraw::dokani()->with(['investor.g_party','account'])->find($id);
        $data['business_settings'] = BusinessSetting::query()->where('user_id',dokanId())->first();

        return response()->json([
            'data'      => $data,
            'message'   => "Success",
            'status'    => 1,
        ]);
    }








    /*
     |--------------------------------------------------------------------------
     | WITHDRAW STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function withdrawCreate(Request $request)
    {

        $withdraw = InvWithdraw::create([
            'investor_id'   => $request->investor_id,
            'account_id'    => $request->account_id,
            'amount'        => $request->amount
        ]);

        (new InvestorLedgerService())->investorLedger(
            $request->investor_id,
            $withdraw->id,
            'Withdraw',
            $request->amount,
            'Out',
            'Withdraw Balance',
            date('Y-m-d'),
            $request->account_id
        );

        (new CashFlowService())->transaction(
            $withdraw->id,
            'Withdraw',
            $request->amount,
            'Out',
            'Withdraw Invest Amount',
            $request->account_id
        );

        (new AccountService())->decreaseBalance($request->account_id, $request->amount);

        return response()->json([
            'data'      => $withdraw,
            'message'   => "Success",
            'status'    => 1,
        ]);
    }












    /*
     |--------------------------------------------------------------------------
     | WITHDRAW DELETE METHOD
     |--------------------------------------------------------------------------
    */
    public function withdrawDelete($id)
    {

        $withdraw = InvWithdraw::dokani()->find($id);
        $investorLedger = InvestorLedger::dokani()->where('source_type','Withdraw')->where('source_id',$id)->first();
        $cashFlow = CashFlow::dokani()->where('transactionable_type','Withdraw')->where('transactionable_id',$id)->first();
        $investor= Investor::where('id', $withdraw->investor_id)->first();

        $investor->increment('balance', $withdraw->amount);

        (new AccountService())->increaseBalance($withdraw->account_id, $withdraw->amount);

        optional($investorLedger)->delete();
        optional($cashFlow)->delete();
        $withdraw->delete();

        return response()->json([
            'message'   => "Success",
            'status'    => 1,
        ]);
    }














    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $data['investor'] = Investor::find($id);
        return view('general-accounts.investor.edit', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        try {
            $this->storeOrUpdate($request, $id);
        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }

        return redirect()->route('dokani.ac.investors.index')->withMessage('Investor edited success !');
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

                $investor = Investor::find($id);

                $investor->investorLedgers->each->delete();

                $cashFlows = CashFlow::dokani()->where('transactionable_type','Investor')
                    ->where('transactionable_id',$investor->id)->get();

                foreach ($cashFlows as $cashFlow){

                    (new AccountService())->decreaseBalance($cashFlow->account_type_id, $cashFlow->amount);
                    optional($cashFlow)->delete();
                }

                $investor->delete();

            });

            return response()->json([
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
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    private function storeOrUpdate($request, $id = null)
    {
        $data = $request->validate([
            'g_party_id'    => 'required',
        ]);

        $data['note']               = $request->note;
        $data['account_id']         = $request->account_id;
        $data['check_no']           = $request->check_no ?? null;
        $data['check_date']         = $request->check_date ?? null;
        $data['amount']             = $request->amount ?? 0;
//        $data['balance']           = $request->balance;
//        if (!$id){
//            $data['balance'] = $request->amount;
//        }

        $investor = Investor::updateOrCreate([
            'id'    => $id
        ], $data);


        if (!$id){
            if ($request->amount > 0){
                (new InvestorLedgerService())->investorLedger(
                    $investor->id,
                    $investor->id,
                    'Investor',
                    $request->amount,
                    'In',
                    'Opening Amount',
                    date('Y-m-d'),
                    $request->account_id
                );

                (new CashFlowService())->transaction(
                    $investor->id,
                    'Investor',
                    $request->amount,
                    'In',
                    'Opening Invest Amount',
                    $request->account_id
                );

                (new AccountService())->increaseBalance($request->account_id, $request->amount);
            }
        }

        return $investor;

    }
}
