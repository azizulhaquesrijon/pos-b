<?php


namespace Module\Dokani\Services;


use Module\Dokani\Models\ProductStockLog;

class ProductStockLogService
{


    public function stockLog(
        $source_id,
        $source_type,
        $product_id,
        $lot,
        $expiry_at,
        $type,
        $quantity,
        $actual_quantity,
        $purchase_price = null,
        $sell_price = null,
        $date = null,
        $invoice = null
    ) {

        ProductStockLog::create([
            'dokan_id'                  => dokanId(),
            'sourceable_type'           => $source_type,
            'sourceable_id'             => $source_id,
            'product_id'                => $product_id,
            'branch_id'                 => request('branch_id') ?? null,
            'reference_no'              => $invoice,
            'lot'                       => $lot,
            'expiry_at'                 => $expiry_at,
            'date'                      => $date ?? date('Y-m-d'),
            'quantity'                  => $quantity,
            'actual_quantity'           => $actual_quantity,
            'stock_type'                => $type,
            'purchase_price'            => $purchase_price,
            'sale_price'                => $sell_price != null ? $sell_price : 0,
        ]);

    }
}
