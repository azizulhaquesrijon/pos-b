<?php

namespace Module\Dokani\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\Category;

class CategoryController extends Controller
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
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['categories'] = Category::latest()->dokani()->with('products')->get();
        return view('products.categories.index', $data);
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

        return redirect()->route('dokani.categories.index')->withMessage('Product Category added success !');
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

        return redirect()->route('dokani.categories.index')->withMessage('Product Category update success !');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            $category = Category::find($id);
            if (file_exists($category->image)) {
                unlink($category->image);
            }
            $category->delete();
        } catch (\Throwable $th) {
            redirectIfError($th);
        }

        return redirect()->route('dokani.categories.index')->withMessage('Product category delete success !');
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

        Category::updateOrCreate([
            'id'    => $id,
        ], [
            'name'  => $request->name,
        ]);
    }
}
