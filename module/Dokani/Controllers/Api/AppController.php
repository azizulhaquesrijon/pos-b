<?php

namespace Module\Dokani\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\AccountType;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\ShopType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Services\HelperService;
use Module\Dokani\Services\packageService;

class AppController extends Controller
{



    /*
     |--------------------------------------------------------------------------
     | cash FLOW
     |--------------------------------------------------------------------------
    */
    public function dashboardData()
    {
        return response()->json([
            'status'        => 1,
            'message'       => 'Successfully Fetched data',
            'data'          => (new HelperService())->dahboardData(),
        ]);
    }








    /*
     |--------------------------------------------------------------------------
     | cash FLOW
     |--------------------------------------------------------------------------
    */
    public function cashFlow(Request $request)
    {
        try {
            if ($request->account_type_id == 'all'){

                $data['reports'] = CashFlow::dokani()
                    ->with('account')->dateFilter('date')->orderBy('id')->paginate(30);
            }else {
                $data['reports'] = CashFlow::dokani()->where('account_type_id',$request->account_type_id)
                    ->with('account')->dateFilter('date')
                    ->searchByField('account_type_id')->orderBy('id')->paginate(30);
            }
            return response()->json([
                'status'         => true,
                'message'        => 'Success',
                'data'           => $data,
                'account_type'   => AccountType::query()->get(),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);
        }
    }




    /*
     |--------------------------------------------------------------------------
     | REGISTER
     |--------------------------------------------------------------------------
    */
    public function register(Request $request)
    {

        // check validation
        $validator = $this->checkValidation($request);

        if ($validator->fails()) {

            return response()->json([
                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }


        try {


            // check user is exist
            $existingUser = User::where('mobile', $request->mobile)->first();

            if ($existingUser) {

                return response()->json([
                    'data'      => "User already exist",
                    'message'   => "Error",
                    'status'    => 0,
                ]);
            }



            return DB::transaction(function () use ($request) {
                // register an user
                $user = User::create([
                    'type'          => 'owner',
                    'name'          => $request->name,
                    'mobile'        => $request->mobile,
                    'email'         => $request->email,
                    'pin'           => $request->pin,
                ]);

                (new packageService())->package($request, $user->id);

                BusinessSetting::create([
                    'user_id'       => $user->id,
                    'shop_name'     => $request->shop_name,
                    'shop_name_bn'  => $request->shop_name_bn,
                ]);

                return response()->json([

                    'data'          => $user->refresh(),
                    'status'        => 1,
                    'message'       => "Success"
                ]);
            });
        } catch (Exception $e) {

            return response()->json([

                'data'          => $e->getMessage(),
                'status'        => 0,
                'message'       => "Error"
            ]);
        }
    }







    /*
     |--------------------------------------------------------------------------
     | SHOP TYPE
     |--------------------------------------------------------------------------
    */
    public function shopType(Request $request)
    {

        return response()->json([

            'data'      => ShopType::pluck('name', 'id'),
            'message'   => "Success",
            'status'    => 1,
        ]);
    }








    /*
     |--------------------------------------------------------------------------
     | VALIDATION
     |--------------------------------------------------------------------------
    */
    public function checkValidation($request)
    {
        return Validator::make($request->all(), [

            'mobile'               =>  'required',
            'pin'                  =>  'required|size:4',
        ]);
    }
}
