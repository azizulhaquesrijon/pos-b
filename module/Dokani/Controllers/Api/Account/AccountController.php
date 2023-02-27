<?php


namespace Module\Dokani\Controllers\Api\Account;


use Illuminate\Http\Request;
use Module\Dokani\Models\Account;
use Module\Dokani\Models\CashFlow;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\AccountType;
use Module\Dokani\Models\FundTransfer;
use Module\Dokani\Models\MultiAccountPay;
use Module\Dokani\Services\CashFlowService;

class AccountController extends Controller
{



    public function index(){

        $data['accounts'] = Account::dokani()->get();

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
        return 111;

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

            return response()->json([

                'data'      => $account,
                'message'   => "Success",
                'status'    => 1,
            ]);
        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 1,
            ]);
        }

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

            return response()->json([

                'data'      => $account,
                'message'   => "Success",
                'status'    => 1,
            ]);

        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 1,
            ]);
        }

    }




//    public function update(Request $request, $id) {
//
//
//
//        $account = Account::where('id', $id)->first();
//
//
//
//        if ($account){
//            $account->opening_balance = $request->opening_balance;
//            $account->balance = $account->balance + $request->opening_balance;
//            $account->status = 1 ;
//            $account->update();
//        }
//        else{
//
//            Account::create([
//
//                'dokan_id'              => dokanId(),
//                'account_type_id'       => $request->account_type_id,
//                'opening_balance'       => $request->opening_balance,
//                'balance'               => $request->opening_balance,
//                'status'                => 1,
//            ]);
//        }
////            ->update(['opening_balance' => $request->opening_balance, 'status' => 1]);
//
//        return response()->json([
//            'message'   => "Success",
//            'status'    => 1,
//        ]);
//    }




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


            return response()->json([
                'message'   => "Success",
                'status'    => 1,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 1,
            ]);        }

    }


}
