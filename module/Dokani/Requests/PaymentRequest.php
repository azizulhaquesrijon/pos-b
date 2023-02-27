<?php

namespace Module\Dokani\Requests;

use Module\Dokani\Models\Payment;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Supplier;
use Module\Dokani\Services\AccountService;
use Module\Dokani\Services\CashFlowService;
use Module\Dokani\Services\LedgerService;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier_id'       => 'required',
            'payable_amount'    => 'required',
            'paid_amount'    => 'required',

        ];
    }


    public function store()
    {
        DB::transaction(function () {

            $payment = Payment::create([
                'supplier_id'        => $this->supplier_id,
                'account_id'         => $this->account_id,
                'check_no'           => $this->check_no,
                'check_date'         => $this->check_date,
                'payable_amount'     => $this->payable_amount,
                'paid_amount'        => $this->paid_amount,
                'due_amount'         => $this->payable_amount - $this->paid_amount,
                'date'               => date("Y-m-d", strtotime($this->date)),

            ]);

             $this->balanceUpdate();

            (new LedgerService())->supplierLedger($this->supplier_id, $payment->id, 'Payment', $this->current_amount, 'In',$this->account_id);

            (new CashFlowService())->transaction(
                $payment->id,
                'Payment',
                $payment->paid_amount,
                'Out',
                'Payment',
                $payment->account_id
            );

            (new AccountService())->decreaseBalance($this->account_id, $this->paid_amount);

            return $payment->refresh();
        });
    }


    private function balanceUpdate()
    {
        $supplier = Supplier::find($this->supplier_id);
        $supplier->decrement('balance', $this->payable_amount);
    }
}
