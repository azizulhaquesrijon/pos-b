<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Models\User;
use App\Traits\AutoCreatedUpdated;
use Module\HRM\Models\Employee;

class Sale extends Model
{
    use AutoCreatedUpdated;



//    public function  getInvoiceIdAttribute()
//    {
//        return "#S-" . str_pad($this->id, 5, '0', 0);
//    }


    public function sale_details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }


    public function sale_return_exchange()
    {
        return $this->hasMany(SaleReturnExchange::class);
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class,'account_id','id');
    }


    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function customer_point(){

        return $this->belongsTo(PointConfigure::class,'point_id','id');
    }


    public function customer_refer(){

        return $this->belongsTo(CustomerRefer::class,'refer_id','id');
    }


    public function cashFlows(){

        return $this->morphMany(CashFlow::class, 'transactionable');
    }


    public function customer_ledgers()
    {
        return $this->morphMany(CustomerLedger::class, 'source');
    }

    public function multi_accounts()
    {
        return $this->morphMany(MultiAccountPay::class, 'source');
    }



    public function customer_ledger(){

        return $this->morphTo(Sale::class);
    }


}
