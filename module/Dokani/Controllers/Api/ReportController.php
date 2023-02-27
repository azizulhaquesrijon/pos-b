<?php

namespace Module\Dokani\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\Category;
use Module\Dokani\Models\CusArea;
use Module\Dokani\Models\CusCategory;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\CustomerLedger;
use Module\Dokani\Models\GeneralAccount;
use Module\Dokani\Models\GParty;
use Module\Dokani\Models\Investor;
use Module\Dokani\Models\InvestorLedger;
use Module\Dokani\Models\Product;
use Module\Dokani\Models\Sale;
use Module\Dokani\Models\SaleDetail;
use Module\Dokani\Models\Supplier;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\SupplierLedger;
use Module\Dokani\Models\Unit;
use Module\Dokani\Models\VoucherPayment;
use Module\Dokani\Services\Reports\ExportService;
use Module\Dokani\Services\Reports\ReportService;
use Module\Dokani\Services\Reports\InventoryReportService;
use Module\Dokani\Services\BranchService;

class ReportController extends Controller
{
    public function inventoryReport()
    {
        $data['categories'] = Category::dokani()->pluck('name','id');
        $data['units'] = Unit::dokani()->pluck('name','id');
        $data['reports']    = (new InventoryReportService())->inventory();

        return $data;
    }


    public function alertInventory()
    {
        $reports =  (new InventoryReportService())->alertInventory();

        return $reports->map(function ($item) {
            if ($item->available_qty <= $item->alert_qty) {
                return [
                    'id'        => $item->id,
                    'name'      => $item->name,
                    'stock'     => $item->available_qty,
                    'alert_qty' => $item->available_qty,
                    'status'    => 'Low Qty',
                ];
            }
        })->filter(function ($item) {
            return $item !== null;
        });
    }

    public function receiveableDue(Request $request)
    {
        $data['customers'] = Customer::dokani()->pluck('name','id');
        $data['reports']   = Customer::dokani()->where('balance', '>', 0)->searchByField('id')->paginate(25);
        return $data;
    }

    public function payableDue(Request $request)
    {
        $data['suppliers'] = Supplier::dokani()->pluck('name','id');
        $data['reports']   = Supplier::dokani()->where('balance', '>', 0)->searchByField('id')->paginate(25);
        return $data;
    }










    /*
    |=====================================================================
    |   SALE REPORT
    |=====================================================================
    */

    public function sale(Request $request)
    {
        try{
            $data                = (new BranchService())->branch();
            $data['customers']   = Customer::dokani()->pluck('name','id');
            $data['users']       = User::latest()->whereDokanId(auth()->id())->get();
            $data['areas']       = CusArea::latest()->dokani()->get();
            $data['categories']  = CusCategory::latest()->dokani()->get();
            $data['reports']     = (new ReportService())->sale($request);

            return response()->json([
                'status'  => 1,
                'message' => 'Success',
                'data'    => $data
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'   => 0,
                'message'  => $th->getMessage(),
            ]);

        }
    }








    /*
    |=====================================================================
    |   Purchase REPORT
    |=====================================================================
    */
    public function purchase(Request $request)
    {
        try{
            $data['suppliers']  = Supplier::dokani()->pluck('name','id');
            $data['reports']    = (new ReportService())->purchase($request);

            return response()->json([
                'status'  => 1,
                'message' => 'Success',
                'data'    => $data
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'   => 0,
                'message'  => $th->getMessage(),
            ]);
        }
    }








    /*
    |=====================================================================
    |   CUSTOMER LEDGER REPORT
    |=====================================================================
    */
    public function customerLedger(Request $request)
    {
        try{
            $data['customers']   = Customer::dokani()->pluck('name','id');
            $data['areas']       = CusArea::latest()->dokani()->get();
            $data['categories']  = CusCategory::latest()->dokani()->get();
            $data['customer']    = Customer::where('id',$request->customer_id)->first();
            $data['reports']     = CustomerLedger::dokani()
                                    ->where('customer_id',$request->customer_id)
                                    ->with('customer','source')
                                    ->searchFromRelation('customer','cus_area_id')
                                    ->searchFromRelation('customer','cus_category_id')
                                    ->searchByField('customer_id')
                                    ->dateFilter('date')
                                    ->paginate(25);

            return response()->json([
                'status'  => 1,
                'message' => 'Success',
                'data'    => $data
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'   => 0,
                'message'  => $th->getMessage(),
            ]);
        }
    }







    /*
    |=====================================================================
    |   SUPPLIER LEDGER REPORT
    |=====================================================================
    */
    public function supplierLedger(Request $request)
    {
        try{
            $data['suppliers'] = Supplier::dokani()->pluck('name','id');
            $data['supplier']  = Supplier::where('id',$request->supplier_id)->first();
            $data['reports']   = SupplierLedger::dokani()
                                ->with('supplier','source')
                                ->where('supplier_id',$request->supplier_id)
                                ->searchByField('supplier_id')
                                ->dateFilter('date')
                                ->paginate(25);

            return response()->json([
                'status'  => 1,
                'message' => 'Success',
                'data'    => $data
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'   => 0,
                'message'  => $th->getMessage(),
            ]);

        }
    }









    /*
    |=====================================================================
    |   CHART OF ACCOUNT REPORT
    |=====================================================================
    */
    public function ChartOfAccount(Request $request){

        try{
            $data['g_accounts']    = GeneralAccount::dokani()->get();
            $data['g_parties']     = GParty::dokani()->pluck('name','id');

            $data['vouchers']      = VoucherPayment::dokani()
                                    ->whereHas('details', function ($q) use ($request){
                                        $q->where('chart_of_account_id', $request->chart_of_account_id);
                                    })
                                    ->with(['details.chart_account','party'])
                                    ->searchByField('type')
                                    ->dateFilter('date')
                                    ->searchFromRelation('details','chart_of_account_id')
                                    ->paginate(30);

            return response()->json([
                'status'  => 1,
                'message' => 'Success',
                'data'    => $data
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'   => 0,
                'message'  => $th->getMessage(),
            ]);
        }
    }








    /*
    |=====================================================================
    |   PROFIT REPORT
    |=====================================================================
    */
    public function profit(Request $request){

        try{
            $data['reports']        = Sale::dokani()->with('details')->dateFilter('date')
                                    ->whereBetween('date', [$request->from, $request->to])
                                    ->withCount(['details as total_qty' => function ($query) {
                                        $query->select(DB::raw('SUM(quantity)'));
                                    }])
                                    ->latest()
                                    ->paginate(30);

            $data['expense']        = VoucherPayment::dokani()
                                    ->where('type','expense')
                                    ->whereBetween('date', [$request->from, $request->to])
                                    ->sum('total_amount');

            return response()->json([
                'status'  => 1,
                'message' => 'Success',
                'data'    => $data
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'   => 0,
                'message'  => $th->getMessage(),
            ]);
        }

    }




    public function productWiseSales(Request $request){

        $data['products'] = Product::dokani()->pluck('name','id');

        $data['reports']   = SaleDetail::where('product_id',$request->product_id)
            ->whereHas('sale',function ($q){
                $q->where('dokan_id',dokanId());
            })
            ->searchByField('product_id')
            ->withSum('product', 'purchase_price')
            ->paginate(30);

        return $data;

    }



    public function vat()
    {

        $data['reports']   = (new ReportService())->vat();

        return $data;
    }

    public function vatDetails($date){

        $data['details'] = Sale::dokani()
            ->select('date','payable_amount','total_vat')
            ->where('date', $date)
            ->latest()
            ->paginate(25);
        $data['date'] = $date;

        return $data;
    }





    public function referWiseCustomer(Request $request){

        $data['refer_customers'] = Customer::dokani()->latest()->where('refer_by_user_id','!=',null)
            ->orWhere('refer_by_customer_id','!=',null)
            ->searchByFields(['id','cus_area_id','cus_category_id'])
            ->dateFilter('created_at')
            ->with(['refer_user','refer_customer'])
            ->paginate(25);

        $data['areas'] = CusArea::latest()->dokani()->get();
        $data['categories'] = CusCategory::latest()->dokani()->get();

        $data['customers'] = Customer::dokani()->get();

        return $data;
    }





    public function referWiseCustomerDetails($id){

        $transactionable_ids = Sale::dokani()->where('customer_id', $id)->pluck('id');

        $data['cashFlows'] = CashFlow::dokani()
            ->where('transactionable_type', 'Sale')
            ->whereIn('transactionable_id', $transactionable_ids)
            ->searchByField('account_type_id')
            ->dateFilter('date')
            ->paginate(20);
        $data['customer'] = Customer::dokani()->where('id',$id)->with(['refer_user','refer_customer'])->first();

        return $data;
    }











    public function incomeExpenseExport(Request $request){

        $data['vouchers'] = VoucherPayment::dokani()->with(['details.chart_account','party'])->paginate(30);

        if ($request->filled('export_type')) {
            $filename = 'Income Expense ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }
    }












    public function investorLedger(Request $request)
    {

        $data['investors'] = Investor::dokani()->pluck('name','id');
        $data['reports']   = InvestorLedger::dokani()->with('investor')
            ->where('investor_id',$request->investor_id)
            ->searchByField('investor_id')->dateFilter('date')->paginate(30);


        return $data;
    }





}
