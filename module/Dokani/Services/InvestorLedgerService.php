<?php


namespace Module\Dokani\Services;


use Module\Dokani\Models\Investor;
use Module\Dokani\Models\InvestorLedger;

class InvestorLedgerService
{

    public function investorLedger($investor_id, $source_id, $source_type, $amount, $type, $description,$date,$account_id, $note = null)
    {

        InvestorLedger::create([
            'investor_id'       => $investor_id,
            'source_type'       => $source_type,
            'source_id'         => $source_id,
            'amount'            => $amount,
            'balance_type'      => $type,
            'description'       => $description,
            'account_id'        => $account_id,
            'date'              => $date ?? date('Y-m-d'),
            'note'              => $note,
        ]);

        $investor = Investor::find($investor_id);

        if ($type == 'In'){
            $investor->increment('balance', $amount);
        }
        else {
            $investor->decrement('balance', $amount);
        }
        $investor->save();

    }

}
