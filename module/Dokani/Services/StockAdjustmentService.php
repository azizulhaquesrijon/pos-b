<?php

namespace Module\Dokani\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\ProductLedger;
use Module\Dokani\Models\StockTransfer;
use Module\Dokani\Models\ProductStockLog;
use Module\Dokani\Models\StockAdjustment;
use Module\Dokani\Models\StockTransferDetail;

class StockAdjustmentService
{

    public $stock_adjustment;


    /**
     * ----------------------------------------
     * VALIDATE METHOD
     * ----------------------------------------
     */
    public function validate($request)
    {
        $request->validate([
            'from_branch_id'    => 'nullable',
            'product_ids.*'     => 'required',
            'adjust_qtys'       => 'required',
        ]);
    }




    /**
     * ----------------------------------------
     * STORE METHOD
     * ----------------------------------------
     */
    public function store($request)
    {
        if ($request->from_branch_id == 'null') {
            $request->from_branch_id = null;
        }

        $this->stock_adjustment = StockAdjustment::create([
            'date'              => $request->date,
            'branch_id'         => $request->from_branch_id,
            'invoice_no'        => 'STK_ADJT-' . str_pad(rand(1,1000), 5, '0', 0),
            'total_quantity'    => $request->total_quantity,
            'sub_total'         => $request->total_amount,
            'comment'           => $request->transfer_cost,
            'is_approved'       => $request->is_approved ?? 0,
        ]);

    }




    /**
     * ----------------------------------------
     * STORE DETAIL METHOD
     * ----------------------------------------
     */
    public function storeDetails($request)
    {

        foreach ($request->product_ids ?? [] as $key => $product_id) {

            $this->stock_adjustment->details()->create([
                'dokan_id'              => dokanId(),
                'product_id'            => $product_id,
                'adjustment_type'       => $request->adjustment_type[$key],
                'previous_qty'          => $request->current_stocks[$key],
                'quantity'              => $request->adjust_qtys[$key],
                'unit_price'            => $request->unit_costs[$key],
                'comment'               => $request->comments[$key] ?? null,
            ]);

        }
    }







    /**
     * ----------------------------------------
     * STORE METHOD
     * ----------------------------------------
     */
    public function update($id, $request)
    {
        if ($request->from_branch_id == 'null') {
            $request->from_branch_id = null;
        }

        $this->stock_adjustment = StockTransfer::find($id);

        $this->stock_adjustment->update([
            'date'              => $request->date,
            'from_branch_id'    => $request->from_branch_id,
            'to_branch_id'      => $request->to_branch_id,
            'total_amount'      => $request->total_amount,
            'transfer_cost'     => $request->transfer_cost,
            'total_quantity'    => $request->total_quantity,
        ]);
    }




    /**
     * ----------------------------------------
     * STORE DETAIL METHOD
     * ----------------------------------------
     */
    public function updateDetails($request)
    {

        foreach ($request->product_ids ?? [] as $key => $product_id) {

            $this->stock_adjustment->details()->updateOrCreate([
                'product_id'    => $product_id,
            ],[
                'unit_cost'     => $request->unit_costs[$key],
                'transfer_qty'  => $request->transfer_qtys[$key],
                'current_stock' => $request->current_stocks[$key],
                'comment'       => $request->comments[$key] ?? null,
            ]);

        }
        StockTransferDetail::where('stock_transfer_id', $this->stock_adjustment->id)->whereNotIn('product_id', $request->product_ids)->delete();
    }





    /**
     * ----------------------------------------
     * APPROVED METHOD
     * ----------------------------------------
     */
    public function approve($id)
    {
        if (request('is_approved') == 1) {

            $this->stock_adjustment = StockAdjustment::find($id);

            DB::transaction(function () {
                foreach ($this->stock_adjustment->details as $detail) {

                    $branch_id = $this->stock_adjustment->branch_id == 'null' ? NULL : $this->stock_adjustment->branch_id;
                    $getLotNumbers = $this->getLotNumbers($detail->product_id);

                    if($detail->adjustment_type == 'In'){

                        $this->createProductStock($detail->product_id, $detail->quantity, $branch_id);

                    }else{
                        $stockOutQty = $detail->quantity;
                        $stockOutingQty = $detail->quantity;

                        foreach($getLotNumbers as $lotNumber) {

                            $lotAvailableQty = $lotNumber->available_quantity;

                            if($stockOutingQty > 0){
                                if($lotAvailableQty > 0){

                                    if($lotAvailableQty >= $stockOutingQty){
                                        $stock = ProductStock::dokani()
                                                            ->where([
                                                                ['product_id', $detail->product_id],
                                                                ['branch_id', $this->stock_adjustment->branch_id],
                                                                ['lot', $lotNumber->lot]
                                                            ])
                                                            ->first()->update([
                                                                'stock_adjust_out_qty' => $stockOutingQty
                                                            ]);
                                        $stockOutingQty = 0;
                                    }else{

                                        $stock = ProductStock::dokani()
                                                            ->where([
                                                                ['product_id', $detail->product_id],
                                                                ['branch_id', $this->stock_adjustment->branch_id],
                                                                ['lot', $lotNumber->lot]
                                                            ])
                                                            ->first()->update([
                                                                'stock_adjust_out_qty' => $lotAvailableQty
                                                            ]);
                                        $stockOutingQty = $stockOutingQty - $lotAvailableQty;
                                    }
                                }
                            }
                        }
                    }

                }

                $this->stock_adjustment->update([
                    'is_approved'   => 1,
                    'approved_by'   => auth()->id(),
                ]);
            });

        }
    }


    public function getLotNumbers($product_id)
    {
        return  ProductStock::dokani()
                ->where([
                    'product_id'            => $product_id,
                    'branch_id'             => $this->stock_adjustment->branch_id,
                ])
                ->orderBy('id', 'ASC')
                ->get();
    }



  public function checkAvailableQuantity($product_id, $lot)
    {
        return  ProductStock::dokani()
            ->where([

                'product_id'            => $product_id,
                'lot'                   => $lot,
            ])
            ->orderBy('id', 'ASC')
            ->sum('available_quantity');
    }




    public function createProductStock($product_id, $stock_adjust_in_qty, $branch_id){

        $lot = strtoupper(Str::random(5));

        ProductStock::create([
            'dokan_id'                  => dokanId(),
            'product_id'                => $product_id,
            'branch_id'                 => $branch_id,
            'lot'                       => $lot,
            'stock_adjust_in_qty'       => $stock_adjust_in_qty,
        ]);

        $this->createProductStockLogs($product_id, $branch_id, $lot, $stock_adjust_in_qty, 'In');
    }




    public function createProductStockLogs($product_id, $branch_id, $lot, $quantity, $stock_type){
        ProductStockLog::create([
            'dokan_id'                  => dokanId(),
            'product_id'                => $product_id,
            'branch_id'                 => $branch_id,
            'lot'                       => $lot,
            'sourceable_type'           => 'Stock Adjustment',
            'sourceable_id'             => $this->stock_adjustment->id,
            'quantity'                  => $quantity,
            'stock_type'                => $stock_type,
        ]);
    }


}
