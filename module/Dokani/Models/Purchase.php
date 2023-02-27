<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Models\User;
use App\Traits\AutoCreatedUpdated;

class Purchase extends Model
{
    use AutoCreatedUpdated;






    public function  getInvoiceIdAttribute()
    {
        return "#P-" . str_pad($this->id, 5, '0', 0);
    }


    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'dokan_id');
    }



    public function supplier_ledgers()
    {
        return $this->morphMany(SupplierLedger::class, 'source');
    }



    public function multi_accounts()
    {
        return $this->morphMany(MultiAccountPay::class, 'source');
    }





}
