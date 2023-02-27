<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class VoucherPaymentDetail extends Model
{


    public function chart_account(){

        return $this->belongsTo(GeneralAccount::class,'chart_of_account_id', 'id');
    }


    public function voucher_payment(){

        return $this->belongsTo(VoucherPayment::class,'voucher_payment_id', 'id');
    }

}
