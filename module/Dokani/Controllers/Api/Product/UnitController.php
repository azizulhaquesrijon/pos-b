<?php

namespace Module\Dokani\Controllers\Api\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Trycatch;
use Illuminate\Support\Facades\Validator;
use Module\Dokani\Models\Unit;

class UnitController extends Controller
{

    use Trycatch;





    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        return $this->load(Unit::dokani()->latest()->paginate(20));
    }











    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        /*--------======== VALIDATION ===========----------*/
        $validator = Validator::make($request->all(), [
            'name'    =>  'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status'    => 0,
                'message'   => "Validation Error",
                'data'      => $validator->errors()->first(),
            ]);
        }
        /*--------------- END VALIDATION ------------------*/




        /*--------======== STORE ACTION ===========---------*/
        try {
            $data['category'] = Unit::create($request->except('image'));

            if ($data['category']) {
                return response()->json([
                    'status'    => 1,
                    'message'   => "Success",
                    'data'      => $data,
                ]);
            }
        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => "Server Error",
                'data'      => $th->getMessage(),
            ]);
        }
        /*--------------- STORE ACTION ------------------*/

    }








    
    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        try{
            $data['unit'] = Unit::find($id);

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








    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        return $this->load($this->storeOrUpdate($request, $id));
    }








    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            $unit = Unit::find($id);
            $unit->delete();

            return response()->json([
                'status'    => 1,
                'message'   => "Success",
                'data'      => 'Unit deleted successfully !',
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status'   => 0,
                'message'  => "Error",
                'data'     => $th->getMessage(),
            ]);
        }
    }


    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    private function storeOrUpdate($request, $id = null)
    {
        $data = $request->validate([
            'name'          => 'required',
        ]);

        Unit::updateOrCreate([
            'id'    => $id
        ], $data);
    }
}
