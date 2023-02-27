<?php


namespace Module\Dokani\Services;


use Module\Dokani\Models\Account;
use Module\Dokani\Models\MultiAccountPay;

class AccountService
{
    public $newBalance = 0;

    public function increaseBalance($account_id, $paid_amount)
    {

        $accountBalance = Account::query()->where('dokan_id', dokanId())->where('id',$account_id)->first();

        optional($accountBalance)->increment('balance', $paid_amount);

    }



    public function decreaseBalance($account_id, $paid_amount)
    {

        $accountBalance = Account::query()->where('dokan_id', dokanId())->where('id',$account_id)->first();
        optional($accountBalance)->decrement('balance', $paid_amount);

    }



    public function multiAccount($source_id,$source_type,$account_id, $amount, $check_no = null, $check_date = null)
    {

        MultiAccountPay::create([
            'dokan_id'          => dokanId(),
            'source_type'       => $source_type,
            'source_id'         => $source_id,
            'account_id'        => $account_id,
            'amount'            => $amount,
            'check_no'          => $check_no,
            'check_date'        => $check_date,
        ]);

    }


}
