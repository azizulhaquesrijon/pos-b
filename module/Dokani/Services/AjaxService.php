<?php


namespace Module\Dokani\Services;

use Module\Dokani\Models\Product;
use Module\Product\Models\Stock;
use Illuminate\Support\Facades\DB;
use Module\Product\Models\StockSummary;
use Module\Product\Models\ProductBarcode;


class AjaxService
{


    /**
     * GET PRODUCT BY BARCODE WITH PRODUCT
     */
    public function getProductByBarcodeProductCodeProductName($request)
    {
        $branch_id = $request->product_id;

        $categories_id = (new CategoryService)->getCategoryIds($request);

        $products   = Product::query()
            ->whereHas('stockSummary', function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            })
            ->when(request('category_id'), function($q) use ($categories_id) {
                $q->whereIn('category_id', $categories_id);
            })
            ->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('code', 'LIKE', '%' . $request->search . '%');
            })
            ->finishGood()
            ->with(['productBarcodes' => function ($q) use ($branch_id) {
                $q->whereHas('productActiveBarcodeTracking', function($query) use ($branch_id) {
                    $query->whereHas('stock', function ($q1) use ($branch_id) {
                        $q1->where('branch_id', $branch_id);
                    })
                        ->has('issueToPOSStock');
                });
            }])
            ->with('productBarcode')
            ->get();

        $barcodeArr = [];

        foreach($products ?? [] as $product) {
            foreach($product->productBarcodes as $productBarcode) {
                $barcodeArr[] = $productBarcode->barcode;
            }

            if ($barcodeArr == null && $product->is_serial == 0) {
                $barcodeArr[] = optional($product->productBarcode)->barcode;
            }
        }

        if ($barcodeArr == null) {
            $barcodeArr[] = $request->search;
        }


        return $a =  ProductBarcode::whereIn('barcode', $barcodeArr)
            ->with(['product' => function ($q) use ($branch_id) {
                $q->whereHas('stockSummary', function ($q1) use ($branch_id) {
                    $q1->where('branch_id', $branch_id);
                });
            }])
            ->where(function ($q) use ($branch_id) {
                $q->whereHas('productActiveBarcodeTracking', function($query) use ($branch_id) {
                    $query->whereHas('stock', function ($q1) use ($branch_id) {
                        $q1->where('branch_id', $branch_id);
                    })
                        ->has('issueToPOSStock');
                })
                    ->orWhereHas('isNotSerialProduct');
            })
            ->get()->map(function ($item) use ($branch_id) {

                $stock_summary_id = optional($item->productBarcodeTracking)->stock_summary_id ?? $this->getStockSummaryId($branch_id, optional($item->product)->id);


                $purchase_price = (float)number_format(optional($item->productBarcodeTracking)->purchase_price, 2, '.', '');
                if ($purchase_price == 0) {
                    $purchase_price = $this->getProductPrice($branch_id, optional($item->product)->id, $stock_summary_id);
                    $purchase_price = $purchase_price['purchase_price'];
                }


                $sale_price =  (float)number_format(optional($item->productBarcodeTracking)->sale_price, 2, '.', '');
                if ($sale_price == 0) {
                    $sale_price = $this->getProductPrice($branch_id, optional($item->product)->id, $stock_summary_id);
                    $sale_price = $sale_price['sale_price'];
                }


                return [
                    'id'                => optional($item->product)->id ?? '',
                    'name'              => optional($item->product)->name ?? '',
                    'product_code'      => optional($item->product)->code ?? '',
                    'product_unit'      => optional(optional($item->product)->unit)->name ?? '',
                    'product_category'  => optional(optional($item->product)->category)->name ?? '',
                    'product_is_seiral' => optional($item->product)->is_serial ?? '',
                    'barcode'           => $item->barcode,
                    'barcode_id'        => $item->id,
                    'tracking_id'       => optional($item->productBarcodeTracking)->tracking_id ?? '',
                    'stock_id'          => optional($item->productBarcodeTracking)->stock_id ?? '',
                    'stock_summary_id'  => $stock_summary_id,
                    'stock_summary'     => $this->getStockSummary($stock_summary_id),
                    'purchase_price'    => $purchase_price,
                    'sale_price'        => $sale_price,
                    'available_qty'     => $this->getAvailableQty($branch_id, optional($item->product)->id),
                    'image'             => optional($item->product)->image,
                ];
            });
    }






    public function getProductPrice($branch_id, $product_id, $stock_summary_id)
    {
        $stock_summary = StockSummary::where('id', $stock_summary_id)->first();


        $product = Stock::query()
            ->where([

                'branch_id'     => $branch_id,
                'product_id'    => $product_id,
                'fabric_id'     => $stock_summary->fabric_id,
                'size_id'       => $stock_summary->size_id,
                'color_id'      => $stock_summary->color_id,
                'print_id'      => $stock_summary->print_id,
                'lot'           => $stock_summary->lot,

            ])->where(function ($q) {
                $q->where('stockable_type', 'Production Issue To POS')
                    ->orWhere('stockable_type', 'Stock Transfer To Branch')
                    ->orWhere('stockable_type', 'Stock Transfer Details')
                    ->orWhere('stockable_type', 'Sale Return Exchange Details');
            })
            ->select('id', 'purchase_price', 'sale_price')
            ->latest()
            ->first();

        $data = [
            'purchase_price' => $product->purchase_price ?? 0,
            'sale_price' => $product->sale_price ?? 0,
        ];

        return $data;
    }






    public function getStockSummaryId($branch_id, $product_id)
    {
        $product = Product::query()
            ->where('id', $product_id)
            ->with(['stockSummary' => function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id,)
                    ->select('id', 'product_id');
            }])
            ->first();

        return $product->stockSummary->id;
    }




    public function getAvailableQty($branch_id, $product_id)
    {
        $stock_in_qty = Stock::query()
            ->where(['product_id' => $product_id, 'stock_type' => 1, 'branch_id' => $branch_id])
            ->where(function ($q) {
                $q->where('stockable_type', 'Production Issue To POS')
                    ->orWhere('stockable_type', 'Stock Transfer To Branch')
                    ->orWhere('stockable_type', 'Stock Transfer Details')
                    ->orWhere('stockable_type', 'Sale Return Exchange Details');
            })
            ->sum('qty');

        $stock_out_qty = Stock::query()
            ->where(['product_id' => $product_id, 'stock_type' => 2, 'stockable_type' => 'POS Sale', 'branch_id' => $branch_id])
            ->sum('qty');


        return $stock_in_qty - $stock_out_qty;
    }




    public function getStockSummary($stock_summary_id)
    {
        return  StockSummary::query()
            ->where('id', $stock_summary_id)
            ->with('product:id,name,code,is_serial')
            ->with('fabric:id,name')
            ->with('size:id,name')
            ->with('color:id,name')
            ->with('print:id,name')
            ->first();
    }








    /**
     * GET PRODUCT BY NAME OR CODE
     */
    public function getProductByQuery($search)
    {

        return ProductBarcode::with(['product' => function ($query) use ($search) {

            $query->where('code', 'LIKE', $search)
                ->orWhere("name", "LIKE", "{$search}%")
                ->withCount(['productStock as stock' => function ($q) {

                    return $q->select(DB::raw('SUM(stock)'));
                }]);
        }])->where('status', 1)->take(30)->get();
        // ->map(function ($item) {

        //     return [
        //         'id'            => $item->product->id,
        //         'name'          => $item->product->name,
        //         'product_code'  => $item->product->code,
        //         'barcode'       => $item->barcode,
        //         'product_price' => $item->product->sale_price,
        //         'product_cost'  => $item->product->purchase_price,
        //         'stock'         => $item->product->stock ?: 10,
        //         'image_url'     => $item->product->image,
        //     ];
        // });
    }
}
