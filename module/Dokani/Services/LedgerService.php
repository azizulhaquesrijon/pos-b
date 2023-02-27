<?php

namespace Module\Dokani\Services;

use Carbon\Carbon;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\CustomerLedger;
use Module\Dokani\Models\Supplier;
use Module\Dokani\Models\SupplierLedger;

class LedgerService
{
    public function customerLedger($customer_id, $source_id, $soruce_type, $amount, $type,$account_id, $pos = null)
    {

        CustomerLedger::create([
            'customer_id'       => $customer_id,
            'account_id'        => $account_id,
            'source_type'       => $soruce_type,
            'source_id'         => $source_id,
            'amount'            => $amount,
            'balance_type'      => $type,
            'date'              => date('Y-m-d'),
        ]);

        /*
        |-------------------------------------------------------------------------
        |We Called Customer Balance as Due => ( customer balance (+) mean => Due | customer balance (+) mean => Advance )
        |-------------------------------------------------------------------------
        */
        $customer = Customer::find($customer_id);
        if ($pos == null){
            $customer->balance = $amount;
            $customer->save();
        }else{
            $customer->increment('balance', $amount);
        }

    }



    public function customerReferBalance($customer_id, $source_id, $soruce_type, $refer_amount, $type)
    {
        CustomerLedger::create([
            'customer_id'       => $customer_id,
            'source_type'       => $soruce_type,
            'source_id'         => $source_id,
            'refer_amount'      => $refer_amount,
            'balance_type'      => $type,
            'date'              => date('Y-m-d'),
        ]);

        $customer = Customer::find($customer_id);

        $customer->refer_balance = $refer_amount;
        $customer->save();

    }


    public function supplierLedger($supplier_id, $source_id, $soruce_type, $amount, $type,$account_id)
    {

        SupplierLedger::create([
            'supplier_id'       => $supplier_id,
            'account_id'        => $account_id,
            'source_type'       => $soruce_type,
            'source_id'         => $source_id,
            'amount'            => $amount,
            'balance_type'      => $type,
            'date'              => date('Y-m-d'),
        ]);

        $supplier = Supplier::find($supplier_id);
        $supplier->increment('balance', $amount);

    }
}
