<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\Brand;

class BrandController extends Controller
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
        $data['brands'] = Brand::latest()->dokani()->get();
        return view('products.brands.index', $data);
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

        return redirect()->route('dokani.brands.index')->withMessage('Product Brand added success !');
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

        return redirect()->route('dokani.brands.index')->withMessage('Product Brand update success !');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            $brand = Brand::find($id);
            if (file_exists($brand->image)) {
                unlink($brand->image);
            }
            $brand->delete();
        } catch (\Throwable $th) {
            redirectIfError($th);
        }

        return redirect()->route('dokani.brands.index')->withMessage('Product Brand delete success !');
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

        Brand::updateOrCreate([
            'id'    => $id,
        ], [
            'name'  => $request->name,
        ]);
    }




}
