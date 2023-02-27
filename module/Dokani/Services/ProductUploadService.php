<?php

namespace Module\Dokani\Services;

use App\Traits\FileSaver;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Product;
use Module\Dokani\Models\ProductStock;
use Illuminate\Validation\Rule;
use Module\Dokani\Models\Category;
use Module\Dokani\Models\ProductUpload;
use Module\Dokani\Models\Unit;

class ProductUploadService
{
    use FileSaver;

    public $product;
    public $product_stock;


    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function store()
    {

        $products = ProductUpload::take(50)->get();

        foreach ($products as $key => $product) {

            DB::transaction(function () use ($product) {
                $this->product = Product::firstOrCreate([
                    'name'              => $product->name,
                    'unit_id'           => $this->unit($product->unit),
                    'category_id'       => $this->category($product->category),
                ],[
                    'opening_stock'     => $product->openingQty,
                    'alert_qty'         => $product->alertQty,
                    'purchase_price'    => $product->buy_price,
                    'sell_price'        => $product->sell_price,
                    'barcode'           => $product->barcode,
                    'product_type'      => strtolower($product->product_type) == 'finish' ? 1 : (strtolower($product->product_type) == 'raw material' ? 2 : 1)
                ]);

                (new ProductLedgerService())->storeLedger(
                    $this->product->id,
                    $this->product->id,
                    'Product',
                    'In',
                    $product->openingQty,
                );

                $this->stockUpdateOrCreate();

                $product->delete();
            });
        }
    }


    public function stockUpdateOrCreate()
    {
        $this->product_stock = $product_stock = ProductStock::where('product_id', $this->product->id)->first();
        if ($product_stock) {
            $product_stock->increment('opening_quantity', $this->product->opening_stock);
            // $product_stock->increment('available_quantity', $this->product->opening_stock);
        } else {
            $this->product_stock = $this->product->stocks()->create([
                'dokan_id'                  => dokanId(),
                'opening_quantity'          => $this->product->opening_stock,
                // 'available_quantity'        => $this->product->opening_stock ?? 0,
                'purchased_quantity'        => 0,
                'sold_quantity'             => 0,
                'wastage_quantity'          => 0,
                'sold_return_quantity'      => 0,
                'purchase_return_quantity'  => 0,
            ]);
        }
    }



    public function category($name)
    {
        if (is_numeric($name)) {
            return $name;
        } else {
            return Category::firstOrCreate([
                'name'  => $name,
            ])->id;
        }
    }



    public function unit($name)
    {
        if (is_numeric($name)) {
            return $name;
        } else {
            return Unit::firstOrCreate([
                'name'  => $name,
            ])->id;
        }
    }




}
