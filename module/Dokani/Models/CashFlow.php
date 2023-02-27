<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([

    'Sale'          => Sale::class,
    'Purchase'      => Purchase::class,
    'Income'        => VoucherPayment::class,
    'Expense'       => VoucherPayment::class,
    'Fund Transfer' => FundTransfer::class,
    'Collection'    => Collection::class,
    'Payment'       => Payment::class,
    'Account'       => Account::class,
    'Investor'      => Investor::class,
    'Withdraw'      => InvWithdraw::class,

]);


class CashFlow extends Model
{
    public function account(){

        return $this->belongsTo(Account::class,'account_type_id','id');
    }



    public function sales()
    {
        return $this->morphToMany(Sale::class,'transactionable');
    }


    public function transactionable()
    {
        return $this->morphTo();
    }




    public function scopeDateFilterGroupByReport($query, $filed_name = 'date')
    {
        $query->when(request()->filled('from') | request()->filled('from_date'), function ($qr) use ($filed_name) {
            $qr->where($filed_name, '>=', (request('from') ?? request('from_date')))
                ->selectRaw('date,SUM(amount) as amount,transactionable_type,balance_type');
            })->groupBy('date')->groupBy('transactionable_type')->groupBy('balance_type')
                ->when(request()->filled('to') | request()->filled('to_date'), function ($qr) use ($filed_name) {
                $qr->where($filed_name, '<=', (request('to') ?? request('to_date')));
            });
    }



}
