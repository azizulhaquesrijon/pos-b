<?php

namespace Module\Dokani\Services;

use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\ProductLedger;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\ProductStockLog;
use Module\Dokani\Models\StockTransfer;
use Module\Dokani\Models\StockTransferDetail;

class StockTransferService
{

    public $stock_transfer;


    /**
     * ----------------------------------------
     * VALIDATE METHOD
     * ----------------------------------------
     */
    public function validate($request)
    {
        $request->validate([
            'from_branch_id'    => 'nullable',
            'to_branch_id'      => 'required|different:from_branch_id',
            'product_ids.*'     => 'required',
            'transfer_qtys'     => 'required',
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

        $this->stock_transfer = StockTransfer::create([
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
    public function storeDetails($request)
    {

        foreach ($request->product_ids ?? [] as $key => $product_id) {

            $this->stock_transfer->details()->create([
                'product_id'    => $product_id,
                'unit_cost'     => $request->unit_costs[$key],
                'transfer_qty'  => $request->transfer_qtys[$key],
                'current_stock' => $request->current_stocks[$key],
                'comment'       => $request->comments[$key] ?? null,
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

        $this->stock_transfer = StockTransfer::find($id);

        $this->stock_transfer->update([
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

            $this->stock_transfer->details()->updateOrCreate([
                'product_id'    => $product_id,
            ],[
                'unit_cost'     => $request->unit_costs[$key],
                'transfer_qty'  => $request->transfer_qtys[$key],
                'current_stock' => $request->current_stocks[$key],
                'comment'       => $request->comments[$key] ?? null,
            ]);

        }
        StockTransferDetail::where('stock_transfer_id', $this->stock_transfer->id)->whereNotIn('product_id', $request->product_ids)->delete();
    }


    /**
     * ----------------------------------------
     * APPROVED METHOD
     * ----------------------------------------
     */
    public function approve($id)
    {
        if (request('is_approved') == 1) {

            $this->stock_transfer = StockTransfer::find($id);

            DB::transaction(function () {
                foreach ($this->stock_transfer->details as $detail) {

                    $getLotNumbers = $this->getLotNumbers($detail->product_id);
                    $leftQty = $detail->transfer_qty;

                    foreach($getLotNumbers as $lotNumber) {
                        $availableQty = $lotNumber->available_quantity;
                        if($availableQty > $leftQty){
                            $stock   = $leftQty;
                            $leftQty = 0;
                        }else{
                            $stock   = $availableQty;
                            $leftQty = $leftQty - $availableQty;
                        }

                        $this->stock_transaction($this->stock_transfer->from_branch_id, $detail->product_id, $lotNumber->expiry_at, $lotNumber->lot, $detail->id, $detail->unit_cost, 0, 'Out', $stock, 0, $stock * $detail->unit_cost, 0);
                        $this->stock_transaction($this->stock_transfer->to_branch_id, $detail->product_id, $lotNumber->expiry_at, $lotNumber->lot, $detail->id, $detail->unit_cost, 0, 'In', 0, $stock, 0, $stock * $detail->unit_cost);

                    }



                    // //TRANSFER FROM BRANCH
                    // $this->stockTransfer($detail->product_id, $this->stock_transfer->from_branch_id, 0, $detail->transfer_qty, 'out');

                    // //TRANSFER TO BRANCH
                    // $this->stockTransfer($detail->product_id, $this->stock_transfer->to_branch_id, $detail->transfer_qty, 0, 'in');


                    // // STOCK TRANSFER LOG
                    // $this->stockManagementLog($detail->product_id, $detail->id, $detail->transfer_qty, 'Out',  $this->stock_transfer->from_branch_id);

                    // // STOCK TRANSFER LOG TO BRANCH
                    // $this->stockManagementLog($detail->product_id, $detail->id, $detail->transfer_qty, 'In',  $this->stock_transfer->to_branch_id);


                    // // PRODUCT LEDGER
                    // $this->stockLedger($detail->product_id, $detail->id, $detail->transfer_qty, 'Out',  $this->stock_transfer->from_branch_id);

                    // // PRODUCT LEDGER TO BRANCH
                    // $this->stockLedger($detail->product_id, $detail->id, $detail->transfer_qty, 'In',  $this->stock_transfer->to_branch_id);
                }


                $this->stock_transfer->update([
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
                    'branch_id'             => $this->stock_transfer->from_branch_id, 
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



    public function stock_transaction($branch_id, $product_id, $expiry_at, $lot, $details_id, $purchase_price, $sell_price, $type, $stock_transfer_out_qty, $stock_transfer_in_qty, $stock_out_value, $stock_in_value){
          
        $product_stock      = ProductStock::dokani()
                            ->where('lot', $lot)
                            ->where('expiry_at', $expiry_at)
                            ->where('branch_id', $branch_id)
                            ->where('product_id', $product_id)
                            ->first();

        $new_stock_in_value  = ((float)optional($product_stock)->stock_in_value ?? 0) + $stock_in_value;

        $new_stock_out_value = ((float) optional($product_stock)->stock_out_value ?? 0) + $stock_out_value;

        $stock = ProductStock::updateOrCreate([
            'dokan_id'                   => dokanId(),
            'product_id'                 => $product_id,
            'lot'                        => $lot,
            'branch_id'                  => $branch_id ?? request('branch_id') ?? null,
            'expiry_at'                  => $expiry_at,
        ],
        [
            'stock_transfer_out_qty'     => $stock_transfer_out_qty,
            'stock_transfer_in_qty'      => $stock_transfer_in_qty,
            'stock_out_value'            => $new_stock_out_value,
            'stock_in_value'             => $new_stock_in_value,
        ]);

        $quantity = $stock_transfer_out_qty != 0 ? $stock_transfer_out_qty : $stock_transfer_in_qty;
        

        ProductStockLog::create([
            'dokan_id'                  => dokanId(),
            'sourceable_type'           => 'Stock Transfer',
            'sourceable_id'             => $details_id,
            'product_id'                => $product_id,
            'branch_id'                 => $branch_id,
            'reference_no'              => '',
            'lot'                       => $lot,
            'expiry_at'                 => $expiry_at,
            'date'                      => $date ?? date('Y-m-d'),
            'quantity'                  => $quantity,
            'actual_quantity'           => $type == 'In' ? $quantity : -abs($quantity),
            'stock_type'                => $type,
            'purchase_price'            => $purchase_price,
            'sale_price'                => $sell_price != null ? $sell_price : 0,
        ]);




  
        (new ProductLedgerService())->storeLedger(
            $product_id,
            $details_id,
            'Stock Transfer',
            $type,
            $quantity
        );

        
    }



    /**
     * ----------------------------------------
     * APPROVED METHOD
     * ----------------------------------------
     */
    public function stockManagementLog($product_id, $source_id, $qty, $type, $branch_id)
    {
        ProductStockLog::create([
            'product_id'        => $product_id,
            'dokan_id'          => dokanId(),
            'branch_id'         => $branch_id,
            'sourceable_type'   => 'Stock Transfer To Branch',
            'sourceable_id'     => $source_id,
            'quantity'          => $qty,
            'actual_quantity'   => $type == 'Out' ? '-' . $qty : $qty,
            'stock_type'        => $type,
        ]);
    }



    /**
     * ----------------------------------------
     * APPROVED METHOD
     * ----------------------------------------
     */
    public function stockLedger($product_id, $source_id, $qty, $type, $branch_id)
    {
        ProductLedger::create([
            'date'              => $this->stock_transfer->date,
            'product_id'        => $product_id,
            'dokan_id'          => dokanId(),
            'branch_id'         => $branch_id,
            'sourceable_type'   => 'Stock Transfer To Branch',
            'sourceable_id'     => $source_id,
            'quantity'          => $qty,
            'type'              => $type,
        ]);
    }




    public function stockTransfer($product_id, $branch_id, $transfer_in_qty, $transfer_out_qty, $type = 'in')
    {
        $old_stock = ProductStock::where([
            'product_id'        => $product_id,
            'dokan_id'          => dokanId(),
        ])->first();

        $stock = ProductStock::where([
            'product_id'        => $product_id,
            'dokan_id'          => dokanId(),
            'branch_id'         => $branch_id
        ])->first();

        if ($stock) {
            if ($type == 'in') {
                $stock->increment('stock_transfer_in_qty', $transfer_in_qty);
            }else{

                $stock->decrement('stock_transfer_out_qty', $transfer_out_qty);
            }
            $stock->save();
        }
        else{
        ProductStock::create([
            'product_id'                => $product_id,
            'dokan_id'                  => dokanId(),
            'lot'                       => $old_stock->lot ?? null,
            'branch_id'                 => $branch_id,
            'stock_transfer_in_qty'     => $transfer_in_qty,
            'stock_transfer_out_qty'    => $transfer_out_qty,
        ]);
       }
    }

}
