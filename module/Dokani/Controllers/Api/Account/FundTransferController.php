<?php


namespace Module\Dokani\Controllers\Api\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\Account;
use Module\Dokani\Models\FundTransfer;
use Module\Dokani\Services\CashFlowService;

class FundTransferController extends Controller
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
            'data'      => FundTransfer::latest()->dokani()->with(['from_account','to_account'])->get(),
        ]);
    }





    /*
    |--------------------------------------------------------------------------
    | CREATE METHOD
    |--------------------------------------------------------------------------
   */
    public function create()
    {
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
//        try {
//            $data = $this->storeOrUpdate($request, $id);
//
//            return response()->json([
//                'data'      => $data,
//                'message'   => "Success",
//                'status'    => 1,
//            ]);
//        } catch (\Throwable $th) {
//            return response()->json([
//                'data'      => $th->getMessage(),
//                'message'   => "Server Error",
//                'status'    => 0,
//            ]);
//        }
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

            $from_accountBalance->increment('balance', $fundTransfer->amount);
            $to_accountBalance->decrement('balance', $fundTransfer->amount);

            $fundTransfer->delete();

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

        return $fundTransfer ;

    }
}
