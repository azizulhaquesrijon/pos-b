<?php

namespace Module\Dokani\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\Package;
use Shipu\Aamarpay\Facades\Aamarpay;

class SubscriptionController extends Controller
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

        $payment = new \Shipu\Aamarpay\Aamarpay(config('aamarpay'));

        $profile = User::where('id',auth()->user()->id)->first();
        $packagePrice = optional(auth()->user()->package)->type->price;

        return view('subscriptions.gateway',compact('profile','packagePrice'));
    }








    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data = [];
        return view('', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        # code...
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
        # code...
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        # code...
    }



    public function paymentSuccessOrFailed(Request $request)
    {
        if ($request->get('pay_status') == 'Failed') {
            return redirect()->back();
        }

        $amount = 1;
        $valid  = Aamarpay::valid($request, $amount);
        // status_code=2 success
        // status_code=7 failed
        if ($valid) {
            // $this->UpdatePackage();
            // return redirect()->route('dashboard');
        } else {
             //dd($request);
            return redirect()->route('dokani.subscription.index')->with('warning', 'Payment didnot go through.');
        }
        return redirect()->back();
    }

    public function paymentSuccess(Request $request)
    {
        try {
            if ($request->get('pay_status') == 'Successful') {
                $this->UpdatePackage();
                return redirect()->route('home');
            } else {
                return redirect()->route('dokani.subscription.index')->with('warning', 'Payment didnot go through.');
            }
        } catch (\Exception $ex) {
            return redirect()->route('dokani.subscription.index')->with('warning', 'Payment didnot go through.');
        }
    }




    public function UpdatePackage()
    {
        $user_mobile = auth()->user()->mobile;
        // add payment_history
//        $trans = rand(10000000000, 9999999999);
//        $this->transaction(1, $trans, 3);
        // end payment

        // start package update
        $date = optional(auth()->user()->package)->end_date;
        $months = optional(auth()->user()->package)->type->months;
        $start_date = Carbon::parse($date)->format('Y-m-d');
        $end_date   = Carbon::parse($date)->addMonth($months)->format('Y-m-d');

        Package::updateOrCreate(
            [
                'id' => auth()->user()->package->id,
            ],
            [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

    }


    public function paymentAlert(){

        if(auth()->user()->type == 'owner'){

            $end_date = Carbon::parse(optional(auth()->user()->package)->end_date) ?? now();
        }
        else{

            $pack = Package::where('user_id', auth()->user()->dokan_id)->first();
            $end_date = Carbon::parse($pack->end_date) ?? now();
        }

        $start_date = now();

       $different_days = $end_date->diffInDays($start_date);
        $different_days = $different_days+1;
        $success = false;

        if ($different_days!='null' && ($different_days<=3)){
            $success = true;
        }

        return response()->json(
            [
                'success'           => $success,
                'date'              => $different_days,
            ]
        );
    }
}
