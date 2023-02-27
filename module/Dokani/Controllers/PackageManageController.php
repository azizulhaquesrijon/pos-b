<?php

namespace Module\Dokani\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\ManageSmsApi;
use Module\Dokani\Models\Package;
use Module\Dokani\Models\PackageType;

class PackageManageController extends Controller
{
    public function index(){

        $data['dokans'] = User::latest()->searchByFields(['name','mobile'])->where('type','owner')->with(['businessProfile','package.type'])->paginate(25);
        $data['packages'] = PackageType::all();
        //dd($data);
        return view('package-manage.index',$data);
    }


    public function create(){
        $data['packages'] = PackageType::all();
        return view('package-manage.dokan_create', $data);
    }




    public function getPackage(){
        $data['packages'] = PackageType::latest()->paginate(25);
        return view('package-manage.package', $data);
    }


    public function smsList(){

        $data['dokans'] = User::latest()->where('type','owner')->get();
        $data['sms_apis'] = ManageSmsApi::latest()
            ->with('user')
            ->paginate(25);

        return view('package-manage.SMS-Manage.index', $data);
    }



    public function smsManage(){

        $data['dokans'] = User::latest()->with('smsApi')->where('type','owner')->get();
//        dd($data);
        return view('package-manage.SMS-Manage.create', $data);
    }




    public function smsStore(Request $request){

        ManageSmsApi::create([

            'dokan_id'              => $request->dokan_id,
            'base_url'              => $request->base_url,
            'api_key'               => $request->api_key,
            'secret_key'            => $request->secret_key,
            'caller_id'             => $request->caller_id,
            'balance_url'           => $request->balance_url,
        ]);

        return redirect()->route('dokani.shop.sms.list')->withMessage('SMS Api add Successfully');

    }


    public function smsEdit($id){

        $data['dokans'] = User::latest()->where('type','owner')->get();
        $data['sms_api'] = ManageSmsApi::find($id);
        return view('package-manage.SMS-Manage.edit', $data);
    }

    public function smsUpdate(Request $request,$id){

        $sms_api = ManageSmsApi::find($id);
        $sms_api->update([

            'dokan_id'              => $request->dokan_id,
            'base_url'              => $request->base_url,
            'api_key'               => $request->api_key,
            'secret_key'            => $request->secret_key,
            'caller_id'             => $request->caller_id,
            'balance_url'           => $request->balance_url,
        ]);

        return redirect()->route('dokani.shop.sms.list')->withMessage('SMS Api update Successfully');

    }



    public function smsDelete($id){

        $sms_api = ManageSmsApi::find($id);
        $sms_api->delete();

        return redirect()->route('dokani.shop.sms.list')->withMessage('SMS Api delete Successfully');

    }




    public function edit( $id = null){
        $data['packages'] = PackageType::all();
        $data['package'] = Package::where('user_id', $id)->first();
        $data['user'] = User::find($id);
        return view('package-manage.dokan_edit', $data);
    }




    public function store(Request $request){

        DB::transaction(function () use ($request){
            $data = $request->validate([
                'name'                  => 'required',
                'mobile'                => 'required',
                'pin'                   => 'required',
            ]);

            $data['type']               =  'owner';
            $data['is_multiple_branch'] =  $request->is_multiple_branch ?? 0;


            $user = User::create($data);

            Package::create([

                'user_id'           => $user->id,
                'start_date'        => $request->start_date,
                'end_date'          => $request->end_date,
                'package_type'      => $request->package_type,
            ]);
        });



        return redirect()->route('dokani.shop.index')->withMessage('Shop add Successfully');
    }





    public function update(Request $request, $id = null){

        DB::transaction(function () use ($request, $id){
            $data = $request->validate([
                'name'                  => 'required',
                'mobile'                => 'required',
                'pin'                   => 'required',
            ]);

            $data['type']               =  'owner';
            $data['is_multiple_branch'] =  $request->is_multiple_branch ?? 0;


            $user = User::updateOrCreate([
                'id'        => $id
            ],$data);

            Package::updateOrCreate([
                'user_id'   => $id,
            ],[

                'user_id'           => $user->id,
                'start_date'        => $request->start_date,
                'end_date'          => $request->end_date,
                'package_type'      => $request->package_type,
            ]);
        });



        return redirect()->route('dokani.shop.index')->withMessage('Shop add Successfully');
    }




    public function endDateChange(Request $request, $id) {

        Package::where('user_id', $id)->update(['end_date' => $request->end_date]);

        return redirect()->back();
    }

    public function startDateChange(Request $request, $id) {

        Package::where('user_id', $id)->update(['start_date' => $request->start_date]);

        return redirect()->back();
    }


    public function monthChange(Request $request, $type) {

        PackageType::where('name',$type)->update(['months' => $request->months]);

        return redirect()->back();
    }


    public function packageMonth(Request $request, $id) {

        PackageType::find($id)->update(['months' => $request->months]);

        return redirect()->back();
    }

    public function packagePrice(Request $request, $id) {

        PackageType::find($id)->update(['price' => $request->price]);

        return redirect()->back();
    }


}
