<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\ManageSmsApi;
use Module\Dokani\Models\Supplier;

class SMSManageController extends Controller
{



    public function index(Request $request){


        $data['check'] = $request->is_check;

        if ($request->is_check == 1){
            if ($request->sms_to == 'customer'){
                $data['mobiles'] = Customer::dokani()->getCustomer()->where('mobile', '!=', null)->take(500)->pluck('mobile');
            }elseif ($request->sms_to == 'client'){
                $data['mobiles'] = Customer::dokani()->getClient()->where('mobile', '!=', null)->take(500)->pluck('mobile');
            }elseif ($request->sms_to == 'supplier'){
                $data['mobiles'] = Supplier::dokani()->where('mobile', '!=', null)->take(500)->pluck('mobile');
            }

            return view('SMS.index',$data);
        }
        else{

            $data['mobiles'] = json_decode($request->alldata);
//            dd($customers);
            return view('SMS.index',$data);
        }
    }






    public function send(Request $request)
    {

        $phone = implode(',',$request->mobiles);
        $message = $request->message;

        $sms_api = ManageSmsApi::where('dokan_id', dokanId())->first();

        if ($sms_api){
            $this->sendSmsNotification($message, $phone, $sms_api);

            return redirect()->back()->withMessage('SMS Send Success');
        }
        else{
            return redirect()->back()->withError('SMS Api Not Configure');
        }


    }




    public function sendSmsNotification($message, $phone_numbers, $sms_api)
    {


        $client = new \GuzzleHttp\Client();
        $phone = $phone_numbers;
        $url = "$sms_api->base_url?apikey=$sms_api->api_key&secretkey=$sms_api->secret_key&callerID=$sms_api->caller_id&toUser=$phone&messageContent=$message";
        $data = $client->request('get',$url);
        $success = (object)$data->getBody()->getContents();

    }



    public function getAPIBalance()
    {

        $sms_api = ManageSmsApi::where('dokan_id', dokanId())->first();
        if ($sms_api){
            $client = new \GuzzleHttp\Client();
            $url = $sms_api->balance_url;
            $data = $client->request('get',$url);
            $success = $data->getBody();
            return json_decode($success)->Balance;
        }
        else {
            return 0;
        }

    }


    public function manage(){

        $data['customers']  = Customer::dokani()->getCustomer()->where('mobile','!=',null)->pluck('mobile');
        $data['clients']    = Customer::dokani()->getClient()->where('mobile','!=',null)->pluck('mobile');
        $data['suppliers']  = Supplier::dokani()->where('mobile','!=',null)->pluck('mobile');
        $data['balance']    = $this->getAPIBalance();

        return view('SMS.manage', $data);
    }







    public function manualSms(Request $request){


        $sms_api = ManageSmsApi::where('dokan_id', dokanId())->first();

        if ($sms_api){

            $this->sendSmsNotification($request->message, $request->mobiles, $sms_api);

            return redirect()->back()->withMessage('SMS Send Success');
        }
        else{
            return redirect()->back()->withError('SMS Api Not Configure');
        }
    }





    public function sendSms(Request $request){


        $mobiles = implode(',',$request->mobiles);
        $sms_api = ManageSmsApi::where('dokan_id', dokanId())->first();

        if ($sms_api){
            $this->sendSmsNotification($request->message, $mobiles, $sms_api);

            return redirect()->back()->withMessage('SMS Send Success');
        }
        else{
            return redirect()->back()->withError('SMS Api Not Configure');
        }
    }







}
