<?php

namespace Module\Dokani\Services;

use Module\Dokani\Models\CashFlow;

class CashFlowService
{

    public function transaction(
        $soruce_id,
        $soruce_type,
        $amount,
        $balance_type,
        $desc,
        $account_id,
        $invoice = null,
        $date = null

    ) {

        CashFlow::create(
            [
            'dokan_id'                  => dokanId(),
            'transactionable_type'      => $soruce_type,
            'transactionable_id'        => $soruce_id,
            'account_type_id'           => $account_id,
            'invoice_no'                => $invoice,
            'amount'                    => $amount,
            'balance_type'              => $balance_type,
            'date'                      => $date ?? date('Y-m-d'),
            'description'               => $desc,
        ]);


    }
}
