<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;
use Module\Dokani\Models\ProductionIssueDetails;

class ProductionIssue extends Model
{

    use AutoCreatedUpdated;

    protected $table = 'production_issue';

    protected $guarded = [];




    public function production_issue_details(){
        return $this->hasMany(ProductionIssueDetails::class, 'production_issue_id', 'id');
    }



}
