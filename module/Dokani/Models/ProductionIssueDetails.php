<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class ProductionIssueDetails extends Model
{

    // use AutoCreatedUpdated;

    protected $table = 'production_issue_details';

    protected $guarded = [];



    public function production_issue(){
        return $this->belongsTo(ProductionIssue::class);
    }



    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
