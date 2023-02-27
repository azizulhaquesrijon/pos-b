<?php

namespace Module\Dokani\Controllers\Product;

use App\Traits\FileSaver;
use Illuminate\Http\Request;
use Module\Dokani\Models\Unit;
use Module\Dokani\Models\Branch;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Category;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\ProductLedger;
use Module\Dokani\Models\ProductUpload;
use Module\Dokani\Import\ProductUploadCSV;
use Module\Dokani\Services\ProductUploadService;
use Module\Dokani\Services\Reports\SampleProductExportService;
use Module\Dokani\Services\ProductUploadWithSingleBrnachService;

class ProductUploadController extends Controller
{

    use FileSaver;

    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(ProductUploadService $productService)
    {
        $this->service = $productService;
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data['products']   = ProductUpload::dokani()->latest()->paginate(50);
        $data['branches']   = Branch::dokani()->pluck('name', 'id');
        return view('products/product-uploads/index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $data['categories'] = Category::dokani()->pluck('name', 'id');
        $data['units']      = Unit::dokani()->pluck('name', 'id');

        return view('products/product/create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {

            $this->service->store();
        } catch (\Throwable $th) {
            redirectIfError($th, 1);
        }

        return redirect()->route('dokani.products.index')->withMessage('Product added successfully !');
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
        $data['categories'] = Category::dokani()->pluck('name', 'id');
        $data['units']      = Unit::dokani()->pluck('name', 'id');
        $data['product']    = Product::with('unit:id,name', 'category:id,name')->find($id);

        return view('products.product.edit', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        try {
        } catch (\Throwable $th) {
            redirectIfError($th, 1);
        }

        return redirect()->route('dokani.products.index')->withMessage('Product edit successfully !');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            ProductUpload::find($id)->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->withError($th->getMessage());
        }

        return redirect()->route('dokani.product-uploads.index')->withMessage('Product delete successfully !');
    }




    /*
    |--------------------------------------------------------------------------
    |           PRODUCT UPDALOD WITH SINGLE BRNAHC  STORE   METHOD
    |--------------------------------------------------------------------------
    */
    public function productUploadWithSingleBranchStore(Request $request){
        try {

            (new ProductUploadWithSingleBrnachService())->store($request);

            return redirect()->back()->with('success', 'Branch Wise Upload SUccess');
        } catch (\Throwable $th) {
            return redirect()->back()->withError($th->getMessage());
        }
    }
}
