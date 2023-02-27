<?php

namespace Module\Dokani\Models;

use App\Models\User;
use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class StockTransfer extends Model
{
  use AutoCreatedUpdated;


  public function company()
  {
    return $this->belongsTo(User::class, 'dokan_id');
  }



  public function from_branch()
  {
    return $this->belongsTo(Branch::class, 'from_branch_id');
  }




  public function to_branch()
  {
    return $this->belongsTo(Branch::class, 'to_branch_id');
  }




  public function details()
  {
    return $this->hasMany(StockTransferDetail::class, 'stock_transfer_id');
  }



     public function  getInvoiceIdAttribute()
   {
       return "#Inv-" . str_pad($this->id, 5, '0', 0);
   }
}
