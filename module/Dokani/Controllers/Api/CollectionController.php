<?php

namespace Module\Dokani\Controllers\Api;

use Illuminate\Http\Request;
use Module\Dokani\Models\Customer;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\Collection;
use Module\Dokani\Requests\CollectionRequest;

class CollectionController extends Controller
{




    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        return Collection::with('customer:id,name,mobile')->dokani()->paginate(25);
    }







    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(CollectionRequest $request)
    {
        try {
            $data = $request->store();
        } catch (\Throwable $th) {
            return response()->json([

                'data'      => $th->getMessage(),
                'message'   => "Server Error",
                'status'    => 0,
            ]);
        }
        return response()->json([

            'data'      => $data,
            'message'   => "Success",
            'status'    => 0,
        ]);
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        # code...
    }









    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        # code...
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        # code...
    }
}
