<?php

namespace Module\Dokani\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\Dokani\Models\CusArea;

class CusAreaController extends Controller
{






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
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['areas'] = CusArea::latest()->dokani()->get();
        return view('customers.areas.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data = [];
        return view('', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {
            $this->storeOrUpdate($request);
        } catch (\Throwable $th) {
            redirectIfError($th, 1);
        }

        return redirect()->back()->withMessage('Customer area added success !');
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
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
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
        try {
            $this->storeOrUpdate($request, $id);
        } catch (\Throwable $th) {
            redirectIfError($th, 1);
        }

        return redirect()->back()->withMessage('Customer area update success !');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            $area = CusArea::find($id);
            if (file_exists($area->image)) {
                unlink($area->image);
            }
            $area->delete();
        } catch (\Throwable $th) {
            redirectIfError($th);
        }

        return redirect()->back()->withMessage('Customer area delete success !');
    }




    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    private function storeOrUpdate($request, $id = null)
    {
        $request->validate([

            'name'      => 'required',
        ]);

        CusArea::updateOrCreate([
            'id'    => $id,
        ], [
            'name'  => $request->name,
        ]);
    }






}
