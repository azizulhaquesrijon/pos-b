<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FileSaver;
use Module\Dokani\Models\ShopType;
use Module\Dokani\Services\BussinessSettingService;

class BusinessSettingController extends Controller
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
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['profile']    = User::with('businessProfile', 'bankAccount')->find(auth()->id());
        $data['shop_types'] = ShopType::pluck('name', 'id');
        // $data['profile'] = User::find(auth()->id());

        return view('settings/business/index', $data);
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
    public function update(Request $request)
    {
        try {
            $service = new BussinessSettingService();
            $service->profile($request);
            $service->business_profile($request);
            $service->bankAccount($request);


            return back()->withMessage('Business profile update success !');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->withError($th->getMessage());
        }
    }
}
