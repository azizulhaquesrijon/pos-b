<?php

namespace Module\Dokani\Services\Reports;

use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\ProductStockLog;

class InventoryReportService
{
    public function inventory()
    {
        $branch_id = request('branch_id');

        if ($branch_id == 'null') {
            $branch_id = null;
        }

        return Product::dokani()->searchByFields(['category_id','unit_id','name'])
            ->when(request('product_type'),function($q){
                $q->where('product_type',request('product_type'));
            })
            ->withSum(['stocks as opening_qty'              => fn($q) => $q->searchByBranch('branch_id')], 'opening_quantity')
            ->withSum(['stocks as available_qty'            => fn($q) => $q->searchByBranch('branch_id')], 'available_quantity')
            ->withSum(['stocks as sold_qty'                 => fn($q) => $q->searchByBranch('branch_id')], 'sold_quantity')
            ->withSum(['stocks as sold_exchange_qty'        => fn($q) => $q->searchByBranch('branch_id')], 'sold_exchange_quantity')
            ->withSum(['stocks as purchased_qty'            => fn($q) => $q->searchByBranch('branch_id')], 'purchased_quantity')
            ->withSum(['stocks as sold_return_qty'          => fn($q) => $q->searchByBranch('branch_id')], 'sold_return_quantity')
            ->withSum(['stocks as purchase_return_qty'      => fn($q) => $q->searchByBranch('branch_id')], 'purchase_return_quantity')
            ->withSum(['stocks as production_issue'         => fn($q) => $q->searchByBranch('branch_id')], 'production_issue_qty')
            ->withSum(['stocks as production_receive'       => fn($q) => $q->searchByBranch('branch_id')], 'production_qty')
            ->withSum(['stocks as stock_transfer_in_qty'       => fn($q) => $q->searchByBranch('branch_id')], 'stock_transfer_in_qty')
            ->withSum(['stocks as stock_transfer_out_qty'       => fn($q) => $q->searchByBranch('branch_id')], 'stock_transfer_out_qty')
            ->latest()
            ->paginate(50);
    }



    public function alertInventory()
    {
        return Product::searchByField('category_id')->likeSearch('name')->with('stocks')
            ->dokani()
            ->withCount(['stocks as available_qty' => function ($query) {
                $query->select(DB::raw('SUM(available_quantity)'));
            }])
            ->latest()
            ->paginate(25);

    }




    public function productLedger($request)
    {



        return ProductStockLog:: dokani()->with('product.stocks')
                            ->where('product_id',$request->product_id)
                            ->searchByField('product_id', 'branch_id')
                            ->searchFromRelation('product','unit_id')
                            ->dateFilter('date')
                            ->orderBy('date')
                            ->paginate(25);

    }

}
