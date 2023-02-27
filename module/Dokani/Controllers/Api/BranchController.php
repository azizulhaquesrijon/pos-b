<?php

namespace Module\Dokani\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Dokani\Services\BranchService;

class BranchController extends Controller
{
    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        
    }



    /*
     |--------------------------------------------------------------------------
     | BRANCH INFO API
     |--------------------------------------------------------------------------
    */
    public function branch()
    {
        try{
            $data          = (new BranchService())->branch();

            return response()->json([
                'status'   => 1,
                'message'  => 'Success',
                'data'     => $data
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status'   => 0,
                'message'  => "Error",
                'data'     => $th->getMessage(),
            ]);
        }
    }

}
