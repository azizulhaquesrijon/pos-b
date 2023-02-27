<?php


namespace Module\Dokani\Services;

use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\ProductStockLog;
use Module\Dokani\Models\ProductionReceive;
use Module\Dokani\Models\ProductionReceiveDetails;

class ProductionReceiveService
{


    public function store($request){

        // dd($request->all());
        DB::transaction(function () use ($request) {


            $production_receive = ProductionReceive::create([
                'total_amount'              => $request->total_amount,
                'date'                      => date('Y-m-d H:i:s'),
            ]);




            if($production_receive){
                $production_receive->invoice_id = 'PDR-'.$production_receive->id;
                $production_receive->save();
            }


            foreach ($request->product_ids as $key => $product_id) {

                ProductionReceiveDetails::create([
                    'production_receive_id' => $production_receive->id,
                    'product_id'            => $product_id,
                    'quantity'              => $request->product_qty[$key],
                    'unit_price'            => $request->product_cost[$key],
                    'comment'               => $request->commends[$key],
                ]);
                // dd("ok");
                $this->updateProductStock($product_id, $request->product_qty[$key], $production_receive->id);
            }


        });

    }



    public function updateProductStock($product_id, $receive_qty, $production_receive_id){
        $stock = ProductStock::dokani()->where('product_id',$product_id)->with('product')->first();
        $stock->increment('production_qty',$receive_qty);

        $stock_log = ProductStockLog::create([
            'dokan_id'                  =>  dokanId(),
            'product_id'                =>  $stock->product_id,
            'sourceable_type'           =>  ProductionReceive::class,
            'sourceable_id'             =>  $production_receive_id,
            'date'                      =>  date('Y-m-d'),
            'lot'                       =>  $stock->lot,
            'quantity'                  =>  $receive_qty,
            'actual_quantity'           =>  $stock->available_quantity ?? 0,
            'stock_type'                =>  'In',
            'purchase_price'            =>  $stock->product->purchase_price ?? 0,
            'sale_price'                =>  $stock->product->sell_price ?? 0,
        ]);
    }








    public function delete($id){

        DB::transaction(function () use($id){

            $production_receive = ProductionReceive::with('production_receive_details')->find($id);

            foreach ($production_receive->production_receive_details as $key => $receive_details) {

                //   PRODUCT STOCK
                $stock = ProductStock::find($receive_details->product_id);
                $stock->decrement('production_qty', $receive_details->quantity);

                //      PRODUCT STOCK LOGS
                ProductStockLog::create([
                    'dokan_id'                  =>  dokanId(),
                    'product_id'                =>  $stock->product_id,
                    'sourceable_type'           =>  ProductionReceive::class,
                    'sourceable_id'             =>  $production_receive->id,
                    'date'                      =>  date('Y-m-d'),
                    'lot'                       =>  $stock->lot,
                    'quantity'                  =>  $receive_details->quantity,
                    'actual_quantity'           =>  $stock->available_quantity ?? 0,
                    'stock_type'                =>  'Out',
                    'purchase_price'            =>  optional($stock->product)->purchase_price ?? 0,
                    'sale_price'                =>  optional($stock->product)->sell_price ?? 0,
                ]);

                //      PRODUCTION ISSUE DETAILS DELETE
                ProductionReceiveDetails::find($receive_details->id)->delete();
            }




            //      PRODUCTION ISSUE DELETE
            if($production_receive){
                $production_receive->delete();
            }
        });

    }






}
