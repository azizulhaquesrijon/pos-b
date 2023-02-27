<?php

namespace Module\Dokani\Services;

use App\Models\User;
use App\Traits\FileSaver;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\BankInformation;
use Module\Dokani\Models\BusinessSetting;

class BussinessSettingService
{
    use FileSaver;

    /*
     |--------------------------------------------------------------------------
     | BUSINESS PROFILE METHOD
     |--------------------------------------------------------------------------
    */
    public function business_profile($request)
    {
        $datas = $request->only(
            'shop_address',
            'shop_name',
            'has_expiry_date',
            'is_category_show',
            'shop_name_bn',
            'shop_type_id',
            'invoice_type',
            'business_email',
            'business_mobile',
            'nid_no',
            'trade_license',
            'vat',
            'is_mandatory_no_due'
        );

        $BusinessSetting = BusinessSetting::updateOrCreate([
            'user_id'   => auth()->id(),
        ],
        $datas);


        $this->upload_file($request->trade_license_image, $BusinessSetting, 'trade_license_image', 'profiles/business');
        $this->upload_file($request->cover_image, $BusinessSetting, 'cover_image', 'profiles/business');
        $this->upload_file($request->nid_front_image, $BusinessSetting, 'nid_front_image', 'profiles/business');
        $this->upload_file($request->nid_back_image, $BusinessSetting, 'nid_back_image', 'profiles/business');
    }













    /*
     |--------------------------------------------------------------------------
     | BANK ACCOUNT METHOD
     |--------------------------------------------------------------------------
    */
    public function bankAccount($request)
    {
        $bank_datas = $request->only('bank_name', 'branch_name', 'account_type', 'account_no');

        if ($request->filled('bank_name')) {
            BankInformation::updateOrCreate([
                'user_id'   => auth()->id(),
            ], $bank_datas);
        }
    }













    /*
     |--------------------------------------------------------------------------
     | PROFILE UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function profile($request)
    {
        $profile = User::find(auth()->id());
        $profile->update($request->only('name'));
        $this->upload_file($request->image, $profile, 'image', 'profiles');
    }
}
