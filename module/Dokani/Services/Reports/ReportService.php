<?php

namespace Module\Dokani\Services\Reports;

use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\Purchase;
use Module\Dokani\Models\Sale;



class ReportService
{
    public function sale($request)
    {
        return Sale::dokani()->likeSearch('invoice_no')->searchByFields(['customer_id','created_by'])
                ->searchFromRelation('customer','cus_area_id')
                ->searchFromRelation('customer','cus_category_id')
                ->dateFilter('date')->with(['details' => function ($q) {
                $q->withSum('product', 'purchase_price');
            }])
            ->searchByBranch('branch_id')
            ->withCount(['details as total_qty' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->withCount(['details as buy_price' => function ($query) {
                $query->select(DB::raw('SUM(buy_price) * SUM(quantity)'));
            }])
            ->with('customer:id,name')
            ->latest()
            ->paginate(50);


    }

    public function allSale()
    {
        return Sale::dokani()
            ->likeSearch('invoice_no')
            ->likeSearch('branch_id')
            ->likeSearch('created_by')
            ->likeSearch('customer_id')
            // ->searchFromRelation('customer','cus_area_id')
            // ->searchFromRelation('customer','cus_category_id')
            ->dateFilter('date')
            ->with(['details' => function ($q) {
                $q->withSum('product', 'purchase_price');
            }])
            ->with('customer:id,name')
            ->latest()
            ->paginate(50);


    }

    public function vat()
    {

        return Sale::dokani()->likeSearch('invoice_no')->dateFilterGroupBy('date')->with(['details' => function ($q) {
        $q->withSum('product', 'purchase_price');
    }])
            ->latest()
            ->paginate(25);
    }


    public function purchase($request)
    {
        return Purchase::dokani()->likeSearch('invoice_no')
                ->searchByField('supplier_id')->dateFilter('date')->with(['details' => function ($q) {
                $q->withSum('product', 'purchase_price');
            }])
            ->with('supplier:id,name')
            ->latest()
            ->paginate(50);
    }



    public function allPurchase()
    {
        return Purchase::dokani()->likeSearch('invoice_no')
            ->dateFilter('date')->with(['details' => function ($q) {
                $q->withSum('product', 'purchase_price');
            }])
            ->with('supplier:id,name')
            ->latest()
            ->paginate(50);
    }




}
