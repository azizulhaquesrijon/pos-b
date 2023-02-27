<?php

namespace Module\Dokani\Services;

use Module\Dokani\Models\CashFlow;

class CollectionService
{

    public function transaction(
        $soruce_id,
        $soruce_type,
        $amount,
        $balance_type,
        $desc = null,
        $invoice = null,
        $date = null
    ) {
        CashFlow::create([
            'dokan_id'                  => auth()->user()->type == 'owner' ? auth()->id() : auth()->user()->dokan_id,
            'transactionable_type'      => $soruce_type,
            'transactionable_id'        => $soruce_id,
            'invoice_no'                => $invoice,
            'amount'                    => $amount,
            'balance_type'              => $balance_type,
            'date'                      => $date ?? date('Y-m-d'),
            'description'               => $desc,
        ]);
    }
}
