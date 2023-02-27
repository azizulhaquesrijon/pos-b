<?php

namespace Module\Dokani\Import;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Dokani\Models\ProductUpload;

class ProductUploadCSV implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            return new ProductUpload([
                // 'dokan_id'      => auth()->user()->dokan_id,  // commit-> DEVHANIF
                'dokan_id'      => dokanId(),
                'name'          => trim($row['name']),
                'category'      => trim($row['category']),
                'unit'          => trim($row['unit']),
                'buy_price'     => trim($row['buy_price']),
                'sell_price'    => trim($row['sell_price']),
                'barcode'       => trim($row['barcode']),
                'openingQty'    => trim($row['openingqty'] ?? 0),
                'alertQty'      => trim($row['alertqty'] ?? 0),
                'product_type'  => trim(strtolower($row['product_type']) ?? 1),

            ]);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
