<?php

namespace Module\Dokani\Requests;

use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\Collection;
use Module\Dokani\Services\AccountService;
use Module\Dokani\Services\CashFlowService;
use Module\Dokani\Services\LedgerService;
use Illuminate\Foundation\Http\FormRequest;

class CollectionRequest extends FormRequest
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
            'customer_id'       => 'required',
            'payable_amount'    => 'required',
        ];
    }


    public function store()
    {

        DB::transaction(function () {
            $collection = Collection::create([
                'customer_id'           => $this->customer_id,
                'refer_amount'          => $this->refer_amount ?? 0,
                'account_id'            => $this->account_id,
                'check_no'              => $this->check_no,
                'check_date'            => $this->check_date,
                'payable_amount'        => $this->payable_amount,
                'paid_amount'           => $this->paid_amount,
                'due_amount'            => $this->payable_amount - $this->paid_amount - $this->refer_amount,
                'date'                  => date("Y-m-d", strtotime($this->date)),
            ]);

             $this->balanceUpdate();

            (new LedgerService())->customerLedger($this->customer_id, $collection->id, 'Collection', $this->current_amount, 'In',$this->account_id);

            (new CashFlowService())->transaction(
                $collection->id,
                'Collection',
                $collection->paid_amount,
                'In',
                'Due Collection',
                $collection->account_id
            );

            (new AccountService())->increaseBalance($this->account_id, $this->paid_amount);

            return $collection->refresh();
        });
    }


    private function balanceUpdate()
    {
        $customer = Customer::find($this->customer_id);
        $customer->decrement('balance', $this->payable_amount);
        $customer->decrement('refer_balance', $this->refer_amount ?? 0);
    }
}
