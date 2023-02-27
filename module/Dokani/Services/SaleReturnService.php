<?php

namespace Module\Dokani\Services;

use Module\Dokani\Models\Sale;
use Module\Dokani\Models\SaleDetail;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\SaleReturn;
use Module\Dokani\Models\SaleReturnDetail;

class SaleReturnService
{

    public $sale;

    public function store($request)
    {
        $this->sale = SaleReturn::create([
            'customer_id'       => $request->customer_id,
            'payable_amount'    => $request->payable_amount,
            'paid_amount'       => $request->paid_amount ?? 0,
            'date'              => date('Y-m-d'),
        ]);

        if (($request->paid_amount ?? 0) > 0) {
            $this->transaction();
        }
        $this->customerDue($request);

        return $this->sale;
    }

    public function saleDetails($request)
    {
        foreach ($request->product_ids ?? [] as $key => $product_id) {

            $details = $this->sale->details()->create([
                'product_id'    => $product_id,
                'price'         => $request->product_price[$key] ?? 0,
                'quantity'      => $request->quantity[$key],
                'condition'     => $request->conditions[$key],
                'description'   => $request->description[$key],
            ]);

            $this->stockUpdateOrCreate($product_id, $request->quantity[$key]);

            (new ProductLedgerService())->storeLedger(
                $product_id,
                $details->id,
                'Sale Return Details',
                'In',
                $request->quantity[$key]
            );
        }
    }


    public function stockUpdateOrCreate($product_id, $sold_qty, $destroy = null)
    {
        $product_stock = ProductStock::where('product_id', $product_id)->dokani()->first();
        if ($product_stock) {
            if ($destroy) {
                $product_stock->increment('available_quantity', $sold_qty);
                $product_stock->decrement('sold_return_quantity', $sold_qty);
            } else {
                $product_stock->increment('sold_return_quantity', $sold_qty);
                $product_stock->decrement('available_quantity', $sold_qty);
            }
        } else {
            $this->product->stocks()->create([
                'opening_quantity'          => $sold_qty,
                'available_quantity'        => $available_qty ?? 0,
                'purchased_quantity'        => 0,
                'sold_quantity'             => 0,
                'wastage_quantity'          => 0,
                'sold_return_quantity'      => $sold_qty,
                'purchase_return_quantity'  => 0,
            ]);
        }
    }


    public function transaction()
    {
        (new CashFlowService())->transaction(
            $this->sale->id,
            'Sale Return',
            $this->sale->paid_amount,
            'Out',
            'Sale Return Create'
        );
    }




    public function saleDelete($id)
    {
        $sale     = SaleReturn::find($id);
        $customer = Customer::find($sale->customer_id);
        $customer->increment('balance', $sale->paid_amount);

        if ($sale) {

            $sale_details = SaleReturnDetail::where('sale_return_id', $sale->id)->get();

            foreach ($sale_details as $key => $item) {
                $this->stockUpdateOrCreate($item->product_id, $item->quantity, 1);

                (new ProductLedgerService())->storeLedger(
                    $item->product_id,
                    $item->id,
                    'Sale Return Details',
                    'Out',
                    $item->quantity
                );


                $item->delete();
            }
        }

        if ($sale->paid_amount > 0) {
            (new CashFlowService())->transaction(
                $sale->id,
                'Sale Return',
                $sale->paid_amount,
                'In',
                'Sale Return Delete'
            );
        }


        $sale->delete();
    }

    public function customerDue($request)
    {
        if ($request->paid_amount > 0) {
            (new LedgerService())->customerLedger($request->customer_id, $this->sale->id, 'Sale', $request->paid_amount, 'In');
        }
    }
}
