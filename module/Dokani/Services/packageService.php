<?php

namespace Module\Dokani\Services;

use Carbon\Carbon;
use Module\Dokani\Models\Package;

class packageService
{


    public function package($request, $user_id)
    {
        $start_date = Carbon::now()->format('Y/m/d');
        $end_date = Carbon::now()->addMonths(1)->format('Y/m/d');
        $package_data = [
            'start_date' => $start_date,
            'package_type' => 3,
            'end_date' => $end_date,
        ];
        Package::updateOrCreate([
            'user_id'   => $user_id,
        ], $package_data);
    }
}
