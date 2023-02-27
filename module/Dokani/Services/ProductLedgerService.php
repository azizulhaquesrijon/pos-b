<?php

namespace Module\Dokani\Services;

use Module\Dokani\Models\ProductLedger;

class ProductLedgerService
{

    public function storeLedger($product_id, $soruce_id, $soruce_type, $type, $quantity, $desc = null, $date = null)
    {

        $data = ProductLedger::create(
            [
            'product_id'        => $product_id,
            'sourceable_type'   => $soruce_type,
            'sourceable_id'     => $soruce_id,
            'quantity'          => $quantity,
            'type'              => $type,
            'date'              => $date ?? date('Y-m-d'),
            'description'       => $desc,
        ]);

    }
}
