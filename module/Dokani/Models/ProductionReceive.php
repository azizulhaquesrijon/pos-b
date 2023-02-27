<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class ProductionReceive extends Model
{

    use AutoCreatedUpdated;

    protected $table = 'production_receive';

    protected $guarded = [];



    public function production_receive_details(){
        return $this->hasMany(ProductionReceiveDetails::class, 'production_receive_id', 'id');
    }





}
