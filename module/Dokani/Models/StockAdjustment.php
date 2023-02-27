<?php

namespace Module\Dokani\Models;

use App\Models\User;
use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class StockAdjustment extends Model
{
  use AutoCreatedUpdated;

  protected $table = 'stock_adjustments';
  protected $guarded = [];

  public function company()
  {
    return $this->belongsTo(User::class, 'dokan_id');
  }



  public function branch()
  {
    return $this->belongsTo(Branch::class, 'branch_id');
  }




  public function details()
  {
    return $this->hasMany(StockAdjustmentDetail::class, 'stock_adjustment_id');
  }

}
