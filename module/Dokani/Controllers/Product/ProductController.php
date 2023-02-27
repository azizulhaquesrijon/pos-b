<?php

namespace Module\Dokani\Controllers\Product;

use App\Traits\FileSaver;
use Illuminate\Http\Request;
use Module\Dokani\Models\Brand;
use Module\Dokani\Models\ProductStockLog;
use Module\Dokani\Models\Unit;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Category;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\ProductLedger;
use Module\Dokani\Services\ProductService;
use Module\Dokani\Import\ProductUploadCSV;
use Module\Dokani\Models\Purchase;

class ProductController extends Controller
{

    use FileSaver;

    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(ProductService $productService)
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

        $data['categories'] = Category::dokani()->pluck('name', 'id');
        $data['units']      = Unit::dokani()->pluck('name', 'id');
        $data['brands']      = Brand::dokani()->pluck('name', 'id');
        $data['products']   = Product::dokani()->with('category:id,name', 'unit:id,name' , 'brand:id,name')
                                                ->likeSearch('name')
                                                ->searchByFields(['category_id','brand_id','barcode'])
                                                ->latest()
                                                ->paginate(25);
        return view('products/product/index', $data);
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
        $data['brands']      = Brand::dokani()->pluck('name', 'id');

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

            if ($request->store_type == 'upload') {

                if(!$request->csv_file){
                    return redirect()->back()->withError('Please upload csv file!');
                }
                Excel::import(new ProductUploadCSV(), $request->file('csv_file'));
                return redirect()->route('dokani.product-uploads.index')->with('success', 'CSV file uploaded successfully!');
            } else {
                // for ($i = 0; $i < 100; $i++) {
                DB::transaction(function () use ($request) {
                    $this->service->storeOrUpdate($request);
                    if ($request->opening_stock > 0){
                        $this->service->stockUpdateOrCreate($request);
                    }
                });
                // }
            }
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
    public function show(Product $product)
    {
        return view('products.product.show', compact('product'));
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
        $data['brands']      = Brand::dokani()->pluck('name', 'id');
        $data['product']    = Product::with('unit:id,name', 'category:id,name', 'stocks')->find($id);

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
            $this->service->storeOrUpdate($request, $id);
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
            DB::transaction(function () use ($id) {

                $product = Product::find($id);
                if (file_exists($product->image)) {
                    unlink($product->image);
                }

                ProductStock::where('product_id', $product->id)->delete();

                ProductStockLog::where('product_id', $product->id)->delete();

                ProductLedger::where('product_id', $product->id)->delete();

                $product->delete();

            });
        } catch (\Throwable $th) {
            return redirect()->back()->withError('This product used another table');
        }

        return redirect()->route('dokani.products.index')->withMessage('Product delete successfully !');
    }



    public function barcode()
    {
        try {
            $products = Product::dokani()->select('id', 'name', 'barcode')->paginate(25);

            return view('products/product/barcode/index', compact('products'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function barcodePrint($id)
    {
        try {

            $product = Product::find($id);;

            return view('products.product.barcode.label-print', compact('product'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }









    /**
     * -----------------------------------------------------------------------------------
     * PRODUCT GET VIA AJAX
     * -----------------------------------------------------------------------------------
     */
    public function getProduct(Request $request)
    {
        // dd($request->all());
        $products = Product::dokani()->with('category:id,name', 'unit:id,name')
                    ->where('product_type', 1)
                    ->searchByField('category_id')
                    ->withCount(['stocks as available_qty' => function ($query) use($request) {
                        $query
                            ->where('branch_id', $request->branch_id)
                            ->select(DB::raw('SUM(available_quantity)'));
                    }])
                    ->latest()
                    ->paginate(25);

        // return $products;
        return view('partials._card', compact('products'))->render();
    }






    /**
     * -----------------------------------------------------------------------------------
     * PRODUCT GET VIA AJAX
     * -----------------------------------------------------------------------------------
     */
    public function getPurchaseProduct(Request $request)
    {

        $products = Product::dokani()->with('category:id,name', 'unit:id,name')
                            ->searchByField('category_id')
                            ->latest()
                            ->paginate(25);

        return view('partials._purchase-card', compact('products'))->render();
    }





    /**
     * -----------------------------------------------------------------------------------
     * CREATE BRAND VIA AJAX
     * -----------------------------------------------------------------------------------
     */
    public function createBrand(Request $request)
    {

        Brand::create([
            'name' => $request->name,
        ]);


        return response()->json(['data'=>Brand::dokani()->latest()->get()]);
    }







    /**
     * -----------------------------------------------------------------------------------
     * PRODUCT SEARCH VIA AJAX
     * -----------------------------------------------------------------------------------
     */
    public function getSearchableProduct(Request $request)
    {
        // return $request->all();
        $branch_id = $request->branch_id == 'null' ? NULL : $request->branch_id;

        return Product::dokani()
            ->where(function($query) use($request){

                $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('barcode', 'LIKE', '%' . $request->search . '%');

            })
            ->when($request->filled('category_id'), function($q) use($request) {
                $q->where('category_id', $request->category_id);
            })
            ->when($request->filled('source'), function($q){
                $q->where('product_type', 1);
            })

            ->whereHas('stocks', fn($q) => $q->where('branch_id', $branch_id))

            ->withCount(['stocks as available_qty' => function ($query) use($branch_id) {
                $query->where('branch_id', $branch_id)->select(DB::raw('SUM(available_quantity)'));
            }])
            ->dokani()->paginate(50)->map(function ($item) {
                return [
                    'id'                    => $item->id,
                    'name'                  => $item->name,
                    'image'                 => file_exists($item->image) && $item->image ? asset($item->image) : '/assets/images/default.png',
                    'product_code'          => $item->barcode,
                    'vat'                   => $item->vat,
                    'unit'                  => optional($item->unit)->name,
                    'product_price'         => $item->sell_price,
                    'product_cost'          => $item->purchase_price,
                    'product_description'   => $item->description,
                    'stock'                 => $item->available_qty ?? 0,
                ];
            });
    }




     /**
     * -----------------------------------------------------------------------------------
     * PRODUCT SEARCH VIA AJAX
     * -----------------------------------------------------------------------------------
     */
    public function getSearchableProductForPurchase(Request $request)
    {
        return Product::dokani()
            ->where(function($query) use($request){

                $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('barcode', 'LIKE', '%' . $request->search . '%');

            })
            ->when($request->filled('category_id'), function($q) use($request) {
                $q->where('category_id', $request->category_id);
            })
            ->withCount(['stocks as available_qty' => function ($query) {
                $query->where('branch_id', null)->select(DB::raw('SUM(available_quantity)'));
            }])
            ->dokani()->paginate(50)->map(function ($item) {
                return [
                    'id'                    => $item->id,
                    'name'                  => $item->name,
                    'image'                 => file_exists($item->image) && $item->image ? asset($item->image) : '/assets/images/default.png',
                    'product_code'          => $item->barcode,
                    'vat'                   => $item->vat,
                    'unit'                  => optional($item->unit)->name,
                    'product_price'         => $item->sell_price,
                    'product_cost'          => $item->purchase_price,
                    'product_description'   => $item->description,
                    'stock'                 => $item->available_qty ?? 0,
                ];
            });
    }



     /**
     * -----------------------------------------------------------------------------------
     *              get Searchable Purchase Invoice --- AJAX
     * -----------------------------------------------------------------------------------
     */
    public function getSearchablePurchaseInvoice(Request $request)
    {
        return Purchase::dokani()->with('details.product.category','details.product.unit')
                        ->with(['details' => function($q){
                            $q->with('stock:lot,available_quantity');
                        }])
                        ->withSum('details', 'quantity')
                        ->where('supplier_id', $request->supplier_id)
                        ->where('reference_no', 'LIKE', '%'.$request->reference_no)
                        ->get();
    }





    /**
     * -----------------------------------------------------------------------------------
     * PRODUCT SEARCH VIA AJAX
     * -----------------------------------------------------------------------------------
     */
    public function getRawMaterialsProduct(Request $request)
    {
        $branch_id = $request->branch_id == 'null' ? NULL : $request->branch_id;

        return Product::dokani()
            ->where('product_type', 2)
            ->where(function($query) use($request){

                $query->where('name', 'like', $request->search . '%')
                      ->orWhere('barcode', 'like', $request->search . '%');

            })
            ->whereHas('stocks', fn($q) => $q->where('branch_id', $branch_id))

            ->withCount(['stocks as available_qty' => function ($query) use($branch_id) {
                $query->where('branch_id', $branch_id)->select(DB::raw('SUM(available_quantity)'));
            }])
            ->dokani()->paginate(50)->map(function ($item) {
                return [
                    'id'                    => $item->id,
                    'name'                  => $item->name,
                    'image'                 => file_exists($item->image) && $item->image ? asset($item->image) : '/assets/images/default.png',
                    'product_code'          => $item->barcode,
                    'vat'                   => $item->vat,
                    'unit'                  => optional($item->unit)->name,
                    'product_price'         => $item->sell_price,
                    'product_cost'          => $item->purchase_price,
                    'product_description'   => $item->description,
                    'stock'                 => $item->available_qty ?? 0,
                ];
            });
    }




    /**
     * -----------------------------------------------------------------------------------
     * PRODUCT SEARCH VIA AJAX
     * -----------------------------------------------------------------------------------
     */
    public function getFinishProduct(Request $request)
    {
        $branch_id = $request->branch_id == 'null' ? NULL : $request->branch_id;

        return Product::dokani()
            ->where('product_type',1)
            ->where(function($query) use($request){

                $query->where('name', 'like', $request->search . '%')
                      ->orWhere('barcode', 'like', $request->search . '%');

            })
            ->whereHas('stocks', fn($q) => $q->where('branch_id', $branch_id))

            ->withCount(['stocks as available_qty' => function ($query) use($branch_id) {
                $query->where('branch_id', $branch_id)->select(DB::raw('SUM(available_quantity)'));
            }])
            ->dokani()->paginate(50)->map(function ($item) {
                return [
                    'id'                    => $item->id,
                    'name'                  => $item->name,
                    'image'                 => file_exists($item->image) && $item->image ? asset($item->image) : '/assets/images/default.png',
                    'product_code'          => $item->barcode,
                    'vat'                   => $item->vat,
                    'unit'                  => optional($item->unit)->name,
                    'product_price'         => $item->sell_price,
                    'product_cost'          => $item->purchase_price,
                    'product_description'   => $item->description,
                    'stock'                 => $item->available_qty ?? 0,
                ];
            });
    }




    /*
     |-----------------------------------------------------------------------------------
     |          MAKE ALL NON-TYPE PRODUCT TO FINISH GOODS
     |-----------------------------------------------------------------------------------
     */
    public function productTypeFinishGoods(){
        // return 'OK';
        try {
            Product::dokani()->where('product_type', null)->update([
                'product_type' => 1
            ]);
            return redirect()->back()->with('success', 'Non-Type Product update to Finish Goods');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
