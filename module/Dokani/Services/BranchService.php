<?php

namespace Module\Dokani\Services;

use Carbon\Carbon;
use Module\Dokani\Models\Branch;


class BranchService
{

   public function branch(){

    $data['has_branch'] = hasBranchSystem();

    if($data['has_branch'] == true){
        if(auth()->user()->type == 'owner'){
            $data['branches'] = $branches = Branch::dokani()->with('employee')->get();
        }else{
            $data['branches'] = $branches = Branch::dokani()->whereHas('users',fn($q)=>$q->where('user_id',auth()->user()->id))->with('employee')->get();
        }
    }
    return $data;
   }

}
