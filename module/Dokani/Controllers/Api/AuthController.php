<?php

namespace Module\Dokani\Controllers\Api;

use Exception;
use App\Models\User;
use App\Traits\FileSaver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Module\Dokani\Models\BankInformation;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Services\BranchService;
use Module\Dokani\Services\packageService;
use Module\Dokani\Services\BussinessSettingService;

class AuthController extends Controller
{

    use FileSaver;

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
     | CHECK USER EXISTS
     |--------------------------------------------------------------------------
    */
    public function isUserIdExists(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'mobile'               =>  'required',
        ]);

        if ($validator->fails()) {

            return response()->json([

                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }

        $user = User::where('mobile', $request->mobile)->first();

        // value present in db
        if ($user) {

            return response()->json([

                'data'      => "User Exist",
                'message'   => "Success",
                'status'    => 1,
            ]);
        }


        return response()->json([

            'data'      => "User Not Found",
            'message'   => "Error",
            'status'    => 0,
        ]);
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



            return DB::transaction(function () use ($request, $existingUser) {

                if ($existingUser){

                    $existingUser->update([
                        'type'          => 'owner',
                        'name'          => $request->name,
                        'mobile'        => $request->mobile,
                        'email'         => $request->email,
                        'pin'           => $request->pin,
                        'status'        => 1,
                    ]);

                    return response()->json([

                        'data'          => $existingUser,
                        'status'        => 1,
                        'message'       => "Success"
                    ]);
                }else{

                    // register an user
                    $user = User::create([
                        'type'          => 'owner',
                        'name'          => $request->name,
                        'mobile'        => $request->mobile,
                        'email'         => $request->email,
                        'pin'           => $request->pin,
                    ]);

                    (new packageService())->package($request, $user->id);

                    BusinessSetting::firstOrCreate([
                        'user_id'       => $user->id,
                        'shop_name'     => $request->shop_name,
                        'shop_name_bn'  => $request->shop_name_bn,
                        'shop_address'  => $request->shop_address,
                        'shop_type_id'  => $request->shop_type_id,
                        'trade_license' => $request->trade_license,
                        'nid_no'        => $request->nid_no,
                    ]);

                    BankInformation::firstOrCreate([
                        'user_id'       => $user->id,
                        'bank_name'     => $request->bank_name,
                        'account_type'  => $request->account_type,
                        'account_no'    => $request->account_no,
                        'branch_name'   => $request->branch_name,
                    ]);

                    return response()->json([

                        'data'          => $user->refresh()->with('businessProfile'),
                        'status'        => 1,
                        'message'       => "Success"
                    ]);
                }


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
     | LOGIN
     |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'mobile'    =>  'required',
            'pin'       =>  'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }



        // check phone
        $user = User::where('mobile', $request->mobile)->where('pin', $request->pin)->where('status',1)->first();

        // check user is exist or not
        if (!$user) {
            return response()->json([
                'data'      => "Mobile Number Not Found",
                'message'   => "Error",
                'status'    => 0,
            ]);
        }

        Auth::login($user);
        // create bearer token for authentication
        $data          = (new BranchService())->branch();
        $data['token'] = $user->createToken('myapptoken')->plainTextToken;
        $data['user']  = $user;


        return response()->json([

            'data'      => $data,
            'message'   => "Success",
            'status'    => 1,
        ]);
    }

    /*
     |--------------------------------------------------------------------------
     | PROFILE
     |--------------------------------------------------------------------------
    */
    public function profile()
    {
        try{
            $data            = (new BranchService())->branch();
            $data['profile'] = User::with('businessProfile', 'bankAccount')->find(auth()->id());

            return response()->json([
                'status'  => 1,
                'message' => 'Success',
                'data'    => $data
            ]);
        } catch (\Throwable $th) {
            
            return response()->json([
                'status'   => 0,
                'message'  => $th->getMessage(),
            ]);
        }
    }


    /*
     |--------------------------------------------------------------------------
     | UPDATE PROFILE
     |--------------------------------------------------------------------------
    */
    public function profileUpdate(Request $request)
    {
        try {
            $service = new BussinessSettingService();
            $service->profile($request);
            $service->business_profile($request);
            $service->bankAccount($request);


            return response()->json([
                'data'      => 'Business profile update success !',
                'message'   => 'Success',
                'status'    => 1,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data'      => $th->getMessage(),
                'message'   => 'Server Error',
                'status'    => 0,
            ]);
        }
    }






    /*
     |--------------------------------------------------------------------------
     | CHECK USER EXISTS
     |--------------------------------------------------------------------------
    */
    public function updatePin(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'old_pin'               =>  'required',
            'new_pin'               =>  'required|different:old_pin',
        ]);

        if ($validator->fails()) {

            return response()->json([

                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }

        $user = User::where('mobile', auth()->user()->mobile)->first();

        if (!$user) {

            return response()->json([

                'data'      => "User Not Found",
                'message'   => "Error",
                'status'    => 0,
            ]);
        }

        if ($user->pin != $request->old_pin) {
            return response()->json([

                'data'      => "User Old Pin Not Match",
                'message'   => "Error",
                'status'    => 0,
            ]);
        }

        $user->update([
            'pin'   => $request->new_pin,
        ]);

        return response()->json([

            'data'      => "User Pin Successfully Update",
            'message'   => "Success",
            'status'    => 1,
        ]);
    }






    /*
    |--------------------------------------------------------------------------
    | FORGOT PIN
    |--------------------------------------------------------------------------
   */
    public function forgotPin(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'mobile'               =>  'required',
        ]);

        if ($validator->fails()) {

            return response()->json([

                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }

        $user = User::query()->where('mobile',$request->mobile)->first();

        if (!$user) {

            return response()->json([

                'data'      => "User Not Found",
                'message'   => "Error",
                'status'    => 0,
            ]);
        }

        $user->update([
            'pin'   => $request->pin,
        ]);

        return response()->json([

            'data'      => $user,
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
            'pin'                  =>  'required',
        ]);
    }








    /*
     |--------------------------------------------------------------------------
     | Delete
     |--------------------------------------------------------------------------
    */
    public function dokanDelete()
    {


        $dokan = User::where('id',auth()->id())->first();

        $dokan->update([
            'status'    => 0
        ]);

        return response()->json([

            'data'      => 'Delete Successful',
            'message'   => "Success",
            'status'    => 1,
        ]);

    }






}
