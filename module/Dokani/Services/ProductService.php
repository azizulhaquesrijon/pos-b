<?php

namespace Module\Dokani\Services;

use App\Traits\FileSaver;
use Illuminate\Support\Str;
use Module\Dokani\Models\Unit;
use Illuminate\Validation\Rule;
use Module\Dokani\Models\Brand;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Category;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Services\ProductLedgerService;
use Module\Dokani\Services\ProductStockLogService;


class ProductService
{
    use FileSaver;

    public $product;





    /*
     |--------------------------------------------------------------------------
     | VALIDATION METHOD
     |--------------------------------------------------------------------------
    */
    public function validation($request)
    {
        return $request->validate([
            'name'              => 'required',
            'unit_id'           => 'nullable',
            'category_id'       => 'nullable',
            'barcode'           => [
                                    'required'
                                   ],
            'purchase_price'    => 'required',
            'sell_price'        => 'required',
        ]);
    }





    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function storeOrUpdate($request, $id = null)
    {

        $data = $this->validation($request);

        $data['brand_id']       = $request->brand_id;
        $data['description']    = $request->description;
        $data['opening_stock']  = $request->opening_stock ?? 0;
        $data['alert_qty']      = $request->alert_qty ?? 0;
        $data['vat']            = $request->vat;
        $data['category_id']    = $this->categoryId($request->category_id);
        $data['brand_id']       = $this->brandId($request->brand_id);
        $data['unit_id']        = $this->unitId($request->unit_id);
        $data['product_type']   = $request->product_type;


        $this->product = Product::updateOrCreate([
            'id'    => $id,
        ], $data);

        $this->upload_file($request->image, $this->product, 'image', 'products');


        if ($id == null) {
            (new ProductLedgerService())->storeLedger(
                $this->product->id,
                $this->product->id,
                'Product Add',
                'In',
                $request->opening_stock
            );
        }
    }


    /**
     * --------------------------------------------------------
     * WHAT IS THIS AGAIN ADD PRODUCT ?
     * --------------------------------------------------------
     */
    public function againAddProduct(){

        $products = Product::dokani()->get();

        foreach ($products as $product){

            if ($product->opening_stock > 0){

                $product_stock = ProductStock::where('product_id', $product->id)->first();

                $lot = strtoupper(Str::random(5));

                if ($product_stock) {
                    $product_stock->increment('opening_quantity', $product->opening_stock);

                } else {

                    $stock_in_value = ($product->opening_stock * $product->purchase_price);
                    $stock_out_value = 0 ;

                    $product->stocks()->create([
                        'dokan_id'                  => dokanId(),
                        'expiry_at'                 => $product->expiry_at ?? null,
                        'lot'                       => $lot,
                        'opening_quantity'          => $product->opening_stock,
                        'purchased_quantity'        => $product->purchased_quantity ?? 0,
                        'sold_quantity'             => $product->sold_quantity ?? 0,
                        'wastage_quantity'          => $product->wastage_quantity ?? 0,
                        'sold_return_quantity'      => $product->sold_return_quantity ?? 0,
                        'purchase_return_quantity'  => $product->purchase_return_quantity ?? 0,
                        'stock_in_value'            => $stock_in_value,
                        'stock_out_value'           => $stock_out_value,
                    ]);

                    (new ProductStockLogService())->stockLog(
                        $product->id,
                        'Product Add',
                        $product->id,
                        $lot,
                        $product->expiry_at,
                        'In',
                        $product->opening_stock,
                        $product->opening_stock,
                        $product->purchase_price,
                        $product->sell_price
                    );
                }
            }
        }
    }


    /**
     * ---------------------------------------------------------
     * STOCK UPDATE OR CREATE METHOD
     * ---------------------------------------------------------
     */
    public function stockUpdateOrCreate($request)
    {
        $product_stock = ProductStock::where('product_id', $this->product->id)->first();

        $lot = strtoupper(Str::random(5));

        if ($product_stock) {
            $product_stock->increment('opening_quantity', $request->opening_stock);

        } else {

            $stock_in_value = ($request->opening_stock * $request->purchase_price);
            $stock_out_value = 0 ;

            $this->product->stocks()->create([
                'dokan_id'                  => dokanId(),
                'expiry_at'                 => $request->expiry_at ?? null,
                'lot'                       => $lot,
                'opening_quantity'          => $request->opening_stock,
                'purchased_quantity'        => $request->purchased_quantity ?? 0,
                'sold_quantity'             => $request->sold_quantity ?? 0,
                'wastage_quantity'          => $request->wastage_quantity ?? 0,
                'sold_return_quantity'      => $request->sold_return_quantity ?? 0,
                'purchase_return_quantity'  => $request->purchase_return_quantity ?? 0,
                'stock_in_value'            => $stock_in_value,
                'stock_out_value'           => $stock_out_value,
                'branch_id'                 => request('branch_id') ?? null
            ]);

            (new ProductStockLogService())->stockLog(
                $this->product->id,
                'Product Add',
                $this->product->id,
                $lot,
                $request->expiry_at,
                'In',
                $request->opening_stock,
                $request->opening_stock,
                $request->purchase_price,
                $request->sell_price

            );

        }

    }








    /**
     * -----------------------------------------------------------------------------
     * CATEGORY CREATE IF NOT EXIST
     * -----------------------------------------------------------------------------
     */
    public function categoryId($name)
    {
        try {
            if (is_numeric($name)) {
                return $name;
            }

           return Category::create([
                'name'  => $name
            ])->id;

        } catch (\Throwable $th) {}
    }






    /**
     * -----------------------------------------------------------------------------
     * BRAND CREATE IF NOT EXIST
     * -----------------------------------------------------------------------------
     */
    public function brandId($name)
    {
        try {
            if (is_numeric($name)) {
                return $name;
            }

           return Brand::create([
                'name'  => $name
            ])->id;

        } catch (\Throwable $th) {}
    }





    /**
     * -----------------------------------------------------------------------------
     * UNIT CREATE IF NOT EXIST
     * -----------------------------------------------------------------------------
     */
    public function unitId($name)
    {
        try {
            if (is_numeric($name)) {
                return $name;
            }

            return Unit::create([
                'name'  => $name
            ])->id;

        } catch (\Throwable $th) {}
    }




}
