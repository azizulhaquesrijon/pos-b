<?php

namespace Module\Dokani\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Dokani\Models\Account;
use Module\Dokani\Models\AccountType;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\FundTransfer;
use Module\Dokani\Models\MultiAccountPay;
use Module\Dokani\Models\Sale;
use Module\Dokani\Services\CashFlowService;

class AccountController extends Controller
{






    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index(){

        $data['accounts'] = Account::dokani()->get();

        return view('accounts.account.index', $data);
    }










    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create(){

        return view('accounts.account.create');
    }







    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        try {
            $account = Account::create([
                'dokan_id'          => dokanId(),
                'name'              => $request->name,
                'opening_balance'   => $request->opening_balance,
                'account_type_id'   => 1,
                'balance'           => $request->opening_balance,
            ]);

            if ($request->opening_balance > 0){
                (new CashFlowService())->transaction(
                    $account->id,
                    'Account',
                    $request->opening_balance,
                    'In',
                    'Account Opening Balance',
                    $account->id
                );
            }
        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }

        return redirect()->route('dokani.ac.accounts.index')->withMessage('Account added success !');
    }





    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id){

        $data['account'] = Account::dokani()->find($id);

        return view('accounts.account.edit', $data);
    }







    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id) {

        try {
            $account = Account::updateOrCreate(['id'=>$id],[
                'dokan_id'          => dokanId(),
                'name'              => $request->name,
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }

        return redirect()->route('dokani.ac.accounts.index')->withMessage('Account update success !');
    }











    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id) {

        try {

            $account = Account::dokani()->find($id);
            $usedAccount = MultiAccountPay::dokani()->where('account_id', $account->id)->first();
            $fundAccount = FundTransfer::dokani()->where('from_account_id', $account->id)->orWhere('to_account_id',$account->id)->first();

            if ($usedAccount || $fundAccount){
                return redirect()->back()->withMessage('This account is used another table');
            }else{

                $cashFlow = CashFlow::dokani()->where('transactionable_id',$id)->where('transactionable_type','Account')->first();

                optional($cashFlow)->delete();
                $account->delete();
            }


            return redirect()->route('dokani.ac.accounts.index')->withMessage('Account Delete success !');

        } catch (\Throwable $th) {
            return redirect()->back()->withError('This account is used another table');
        }

    }














    //AJAX

    public function getBalance(Request $request){

        $account = Account::where('id', $request->account_id)
            ->where('dokan_id', dokanId())
            ->first();

        return response()->json($account);
    }

}
