<?php

namespace Module\Dokani\Services;


use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\VoucherPayment;
use function Composer\Autoload\includeFile;

class VoucherPaymentService
{

    public $voucherPayment;

    public function store($request)
    {
        $total_amount = array_sum(array_filter($request->amount));
        $this->voucherPayment = VoucherPayment::create([
            'party_id'          => $request->party_id,
            'invoice_no'        => "#VP-" . str_pad(rand(1,1000), 5, '0', 0),
            'type'              => $request->type,
            'account_id'        => $request->account_id,
            'check_no'          => $request->check_no,
            'check_date'        => $request->check_date,
            'date'              => $request->date,
            'total_amount'      => $total_amount,
            'note'              => $request->note,
        ]);

        if (($total_amount ?? 0) > 0) {
            $this->transaction();
        }

        if ($request->type == 'income'){

            (new AccountService())->increaseBalance($request->account_id, $total_amount);
        }
        if ($request->type == 'expense'){

            (new AccountService())->decreaseBalance($request->account_id, $total_amount);
        }

        return $this->voucherPayment;

    }

    public function details($request)
    {
        foreach ($request->chart_of_account_ids as $key => $account_id) {
            $details = $this->voucherPayment->details()->create([
                'chart_of_account_id'    => $account_id,
                'amount'                 => $request->amount[$key] ?? 0,
                'date'                   => $request->date,
            ]);
        }
    }




    public function transaction()
    {
        $source_type = $this->voucherPayment->type == 'income' ? 'Income' : 'Expense';
        $balance_type = $this->voucherPayment->type == 'income' ? 'In' : 'Out';
        $description = $this->voucherPayment->type == 'income' ? 'Income' : 'Expense';

        (new CashFlowService())->transaction(
            $this->voucherPayment->id,
            $source_type,
            $this->voucherPayment->total_amount,
            $balance_type,
            $description,
            $this->voucherPayment->account_id,
            null,
            $this->voucherPayment->date
        );
    }




    public function vocuherDelete($id)
    {
        $voucherPayment = VoucherPayment::find($id);

        if ($voucherPayment) {


            $cashFlow = CashFlow::query()->whereIn('transactionable_type',['Income','Expense'])->where('transactionable_id', $voucherPayment->id)->first();

            $cashFlow->delete();

            $voucherPayment->details()->delete();
        }

//        if ($voucherPayment->total_amount > 0) {
//            (new CashFlowService())->transaction(
//                $voucherPayment->id,
//                ucfirst($voucherPayment->type),
//                $voucherPayment->total_amount,
//                'Out',
//                ucfirst($voucherPayment->type) . ' Delete',
//                $voucherPayment->account_type_id
//            );
//        }


        if($voucherPayment->type == 'income') {
            (new AccountService())->decreaseBalance($voucherPayment->account_id, $voucherPayment->total_amount);
        }

        if ($voucherPayment->type == 'expense'){

            (new AccountService())->increaseBalance($voucherPayment->account_id, $voucherPayment->total_amount);
        }

        $voucherPayment->delete();
    }
}
