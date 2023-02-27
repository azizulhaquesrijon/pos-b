<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusUpdateController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | STATUS UPDATE (METHOD)
     |--------------------------------------------------------------------------
    */
    public function statusUpdate(Request $request)
    {
        $status = $request->status == 1 ? 0 : 1;

        DB::table($request->table_name)->where('id', $request->id)->update(['status' => $status]);

        return response()->json(['status' => $status]);
    }
}

