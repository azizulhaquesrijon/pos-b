<?php


namespace Module\Dokani\Services;

use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\ProductionIssue;
use Module\Dokani\Models\ProductionIssueDetails;
use Module\Dokani\Models\ProductStockLog;

class ProductionIssueService
{



    public function store($request){
        DB::transaction(function () use ($request) {


            $production_issue = ProductionIssue::create([
                'total_amount'              => $request->total_amount,
                'date'                      => date('Y-m-d H:i:s'),
            ]);


            if($production_issue){
                $production_issue->invoice_id = 'PDI-'.$production_issue->id;
                $production_issue->save();
            }

            foreach ($request->product_ids as $key => $product_id) {

                ProductionIssueDetails::create([
                    'production_issue_id'   => $production_issue->id,
                    'product_id'            => $product_id,
                    'quantity'              => $request->product_qty[$key],
                    'unit_price'            => $request->product_cost[$key],
                    'comment'               => $request->commends[$key],
                ]);
                $this->updateProductStock($product_id, $request->product_qty[$key], $production_issue->id);
            }


        });

    }



    public function updateProductStock($product_id, $issue_qty, $production_issue_id){
        $stock = ProductStock::dokani()->where('product_id',$product_id)->with('product')->first();
        $stock->increment('production_issue_qty',$issue_qty);

        $stock_log = ProductStockLog::create([
            'dokan_id'                  =>  dokanId(),
            'product_id'                =>  $stock->product_id,
            'sourceable_type'           =>  ProductionIssue::class,
            'sourceable_id'             =>  $production_issue_id,
            'date'                      =>  date('Y-m-d'),
            'lot'                       =>  $stock->lot,
            'quantity'                  =>  $issue_qty,
            'actual_quantity'           =>  $stock->available_quantity ?? 0,
            'stock_type'                =>  'Out',
            'purchase_price'            =>  $stock->product->purchase_price ?? 0,
            'sale_price'                =>  $stock->product->sell_price ?? 0,
        ]);
    }






    public function delete($id){

        DB::transaction(function () use($id){
            $production_issue = ProductionIssue::with('production_issue_details')->find($id);

            foreach ($production_issue->production_issue_details as $key => $issue_details) {

                //   PRODUCT STOCK
                $stock = ProductStock::find($issue_details->product_id);
                $stock->decrement('production_issue_qty', $issue_details->quantity);

                //      PRODUCT STOCK LOGS
                ProductStockLog::create([
                    'dokan_id'                  =>  dokanId(),
                    'product_id'                =>  $stock->product_id,
                    'sourceable_type'           =>  ProductionIssue::class,
                    'sourceable_id'             =>  $production_issue->id,
                    'date'                      =>  date('Y-m-d'),
                    'lot'                       =>  $stock->lot,
                    'quantity'                  =>  $issue_details->quantity,
                    'actual_quantity'           =>  $stock->available_quantity ?? 0,
                    'stock_type'                =>  'In',
                    'purchase_price'            =>  optional($stock->product)->purchase_price ?? 0,
                    'sale_price'                =>  optional($stock->product)->sell_price ?? 0,
                ]);

                //      PRODUCTION ISSUE DETAILS DELETE
                ProductionIssueDetails::find($issue_details->id)->delete();
            }




            //      PRODUCTION ISSUE DELETE
            if($production_issue){
                $production_issue->delete();
            }
        });

    }



}
