<?php

namespace Module\Dokani\Controllers\Report;

use App\Models\User;
use Illuminate\Http\Request;
use Module\Dokani\Models\Sale;
use Module\Dokani\Models\GParty;
use Module\Dokani\Models\CusArea;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\Investor;
use Module\Dokani\Models\Purchase;
use Module\Dokani\Models\Supplier;
use App\Http\Controllers\Controller;
use Module\Dokani\Models\Branch;
use Module\Dokani\Models\SaleDetail;
use Module\Dokani\Models\CusCategory;
use Module\Dokani\Models\CustomerLedger;
use Module\Dokani\Models\GeneralAccount;
use Module\Dokani\Models\InvestorLedger;
use Module\Dokani\Models\SupplierLedger;
use Module\Dokani\Models\VoucherPayment;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Models\Category;

use function Doctrine\Common\Cache\Psr6\get;
use Module\Dokani\Services\Reports\ExportService;
use Module\Dokani\Services\Reports\ReportService;

class ReportController extends Controller
{






    public function sales(Request $request)
    {
        $data['customers'] = Customer::dokani()->getCustomer()->pluck('name','id');
        $data['users'] = User::latest()->whereDokanId(auth()->id())->get();
        $data['branches'] = Branch::dokani()->latest()->get();
        $data['areas'] = CusArea::latest()->dokani()->get();
        $data['categories'] = CusCategory::latest()->dokani()->get();

        // if ($request->customer_id == 'all'){
            $data['reports']   = (new ReportService())->allSale();
        // }
        // else {
        //     $data['reports']   = (new ReportService())->sale($request);

        // }

        if ($request->filled('export_type')) {
            $filename = 'Sale ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }
        // dd($data);
        return view('reports/sale', $data);
    }



    public function productWiseSale(Request $request)
    {
        $data['products'] = Product::dokani()->pluck('name','id');

        $data['reports']   = SaleDetail::where('product_id',$request->product_id)
            ->whereHas('sale',function ($q){
                $q->where('dokan_id',dokanId());
            })
            ->searchByField('product_id')
            ->withSum('product', 'purchase_price')
            ->paginate(30);

            if ($request->filled('export_type')) {

            $filename = 'Product Wise Sale Report ' . fdate(date('Y-m-d'), 'Y_m_d');
            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports/product-wise-sale', $data);
    }

    public function vats()
    {

        $data['reports']   = (new ReportService())->vat();
        $data['business_setting'] = BusinessSetting::where('user_id', auth()->user()->id)->first();
        // dd($data);

        return view('reports/vat', $data);
    }

    public function vatDetails($date){

        $data['details'] = Sale::dokani()
            ->select('date','payable_amount','total_vat')
            ->where('date', $date)
            ->latest()
            ->paginate(25);
        $data['date'] = $date;
        return view('reports/vat-details', $data);
    }


    public function purchase(Request $request)
    {
        $data['suppliers'] = Supplier::dokani()->pluck('name','id');
        if ($request->supplier_id == 'all'){
            $data['reports']   = (new ReportService())->allPurchase();
        }
        else {

            $data['reports']   = (new ReportService())->purchase($request);
        }


        if ($request->filled('export_type')) {
            $filename = 'Purchase ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports.purchase', $data);
    }


    public function alertInventory()
    {

        $data['reports'] = Product::dokani()->withCount(['stocks as opening_qty' => function ($query) {
            $query->select(DB::raw('SUM(opening_quantity)'));
        }])
            ->withCount(['stocks as available_qty' => function ($query) {
                $query->select(DB::raw('SUM(available_quantity)'));
            }])
            ->latest()
            ->paginate(25);

        return view('reports.inventory', $data);
    }


    public function cashFlow(Request $request)
    {

        if ($request->account_type_id == 'all'){

            $data['reports'] = CashFlow::dokani()
                ->with('account')->dateFilter('date')->orderBy('id')->paginate(30);
        }else {
            $data['reports'] = CashFlow::dokani()->where('account_type_id',$request->account_type_id)
                ->with('account')->dateFilter('date')
                ->searchByField('account_type_id')->orderBy('id')->paginate(30);
        }

        if ($request->filled('export_type')) {

            $data['reports'] = CashFlow::dokani()->where('account_type_id',$request->account_type_id)
                ->with('account')->dateFilter('date')->orderBy('id')->get();
            $filename = 'Cash Flow ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports.cash-flow', $data);
    }



    public function receiveableDue(Request $request)
    {

        $data['areas'] = CusArea::latest()->dokani()->get();
        $data['categories'] = CusCategory::latest()->dokani()->get();
        $data['customers'] = Customer::dokani()->getCustomer()->pluck('name','id');
        if ($request->id == 'all'){
            $data['reports']   = Customer::dokani()->getCustomer()
                ->where('balance', '>', 0)->paginate(25);
        }else {
            $data['reports']   = Customer::dokani()->where('id',$request->id)->getCustomer()
                ->where('balance', '>', 0)->searchByField('id')->paginate(25);
        }

        if ($request->filled('export_type')) {
            $filename = 'Receivable Due ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports/receiveable-due', $data);
    }



    public function payableDue(Request $request)
    {
        $data['suppliers'] = Supplier::dokani()->pluck('name','id');

        if ($request->id == 'all'){
            $data['reports']   = Supplier::dokani()
                ->where('balance', '>', 0)->paginate(25);
        }else {
            $data['reports']   = Supplier::dokani()->where('id',$request->id)
                ->where('balance', '>', 0)->searchByField('id')->paginate(25);
        }

        if ($request->filled('export_type')) {
            $filename = 'Payable Due ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports/payable-due', $data);
    }



    public function customerLedger(Request $request)
    {
        $data['customers'] = Customer::dokani()->getCustomer()->pluck('name','id');
        $data['areas'] = CusArea::latest()->dokani()->get();
        $data['categories'] = CusCategory::latest()->dokani()->get();
        $data['customer'] = Customer::where('id',$request->customer_id)->first();
        $data['reports']   = CustomerLedger::dokani()
                            ->where('customer_id',$request->customer_id)
                            ->with('customer','source')
                            ->searchFromRelation('customer','cus_area_id')
                            ->searchFromRelation('customer','cus_category_id')
                            ->searchByField('customer_id')->dateFilter('date')->paginate(25);
        $data['grand_reports']   = CustomerLedger::dokani()->get();

        if ($request->filled('export_type')) {
            $filename = 'Cash Flow ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports/customer-ledger', $data);
    }



    public function supplierLedger(Request $request)
    {

        $data['suppliers'] = Supplier::dokani()->pluck('name','id');
        $data['supplier'] = Supplier::where('id',$request->supplier_id)->first();
        $data['reports']   = SupplierLedger::dokani()->with('supplier','source')
                            ->where('supplier_id',$request->supplier_id)
                            ->searchByField('supplier_id')->dateFilter('date')->paginate(25);

        $data['grand_reports']   = SupplierLedger::dokani()->with('source')->get();

        if ($request->filled('export_type')) {
            $filename = 'Supplier Ledger ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports.supplier-ledger', $data);
    }




    public function referWiseCustomer(){

        $data['refer_customers'] = Customer::dokani()->getCustomer()->latest()->where('refer_by_user_id','!=',null)
            ->orWhere('refer_by_customer_id','!=',null)
            ->searchByFields(['id','cus_area_id','cus_category_id'])
            ->dateFilter('created_at')
            ->with(['refer_user','refer_customer'])
            ->paginate(25);

        $data['areas'] = CusArea::latest()->dokani()->get();
        $data['categories'] = CusCategory::latest()->dokani()->get();

        $data['customers'] = Customer::dokani()->getCustomer()->get();

        return view('reports.refer-wise-customer', $data);

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

        return view('reports.refer-wise-customer-details', $data);
    }







    public function incomeExpense(Request $request)
    {
        $data['reports'] = [];

        if ($request->filled('from') || $request->filled('to')) {
            $data['reports'] = CashFlow::dokani()
                            ->when($request->filled('from') || $request->filled('to'), function ($q) {
                                $q->whereBetween('date', [request('from'), request('to')]);
                            })
                            ->with('transactionable')
                            ->orderBy('id', 'DESC')
                            ->paginate(20);
        }

        if ($request->filled('export_type')) {
            $filename = 'Income Expense ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports.income-expense', $data);
    }





    public function ChartOfAccount(Request $request){

        $data['vouchers'] = VoucherPayment::dokani()
            ->whereHas('details', function ($q) use ($request){
                $q->where('chart_of_account_id', $request->chart_of_account_id);
            })
            ->with(['details.chart_account','party'])
            ->searchByField('party_id')
            ->dateFilter('date')
            ->searchFromRelation('details','chart_of_account_id')
            ->paginate(30);
        $data['g_accounts'] = GeneralAccount::dokani()->get();
        $data['g_parties'] = GParty::dokani()->pluck('name','id');

        if ($request->filled('export_type')) {
            $filename = 'Chart of account ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports.chart-of-account', $data);
    }




    public function profit(Request $request){

        $data['reports'] = Sale::dokani()
            ->with('details', 'sale_return_exchange.sale_return', 'sale_return_exchange.sale_exchage')
            ->whereBetween('date', [$request->from, $request->to])
            ->withCount(['details as total_qty' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->latest()
            ->paginate(30);

        $data['expense'] = VoucherPayment::dokani()
            ->where('type','expense')
            ->whereBetween('date', [$request->from, $request->to])
            ->sum('total_amount');

        if ($request->filled('export_type')) {
            $data['reports'] = Sale::dokani()->with('details')->dateFilter('date')
                ->whereBetween('date', [$request->from, $request->to])
                ->withCount(['details as total_qty' => function ($query) {
                    $query->select(DB::raw('SUM(quantity)'));
                }])
                ->latest()
                ->get();
            $filename = 'Profit Report ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports.profit', $data);
    }








    public function investorLedger(Request $request)
    {

        $data['investors'] = Investor::dokani()->with('g_party')->get();
        $data['reports']   = InvestorLedger::dokani()->with('investor.g_party')
            ->where('investor_id',$request->investor_id)
            ->searchByField('investor_id')->dateFilter('date')->paginate(30);


        if ($request->filled('export_type')) {
            $filename = 'Investor Ledger ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }

        return view('reports.investor-ledger', $data);
    }






    public function referWiseCustomerExport(Request $request, $id){

        $transactionable_ids = Sale::dokani()->where('customer_id', $id)->pluck('id');

        $data['cashFlows'] = CashFlow::dokani()
            ->where('transactionable_type', 'Sale')
            ->whereIn('transactionable_id', $transactionable_ids)
            ->searchByField('account_type_id')
            ->dateFilter('date')
            ->paginate(30);

        $data['customer'] = Customer::dokani()->where('id',$id)->with(['refer_user','refer_customer'])->first();

        if ($request->filled('export_type')) {
            $filename = 'Refer Wise Customer Sale Report ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }
    }





    public function incomeExpenseExport(Request $request){

        $data['vouchers'] = VoucherPayment::dokani()->with(['details.chart_account','party'])->paginate(30);

        if ($request->filled('export_type')) {
            $filename = 'Income Expense ' . fdate(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'reports/export/', $filename);
        }
    }

    public function productWiseProfit(Request $request){
        try {
            // dd($request->all());
            $data['products']   = Product::dokani()->get();
            $data['categories'] = Category::dokani()->get();
            $data['reports'] = Product::dokani()
                                ->searchByField('category_id')
                                ->when($request->filled('product_id'), function($q) use($request){
                                    $q->where('id', $request->product_id);
                                })
                                ->withCount(['sale_details as profit' => function($q) use($request){
                                    $q->whereHas('sale', function($q) use($request){
                                            $q->when($request->filled('from'), function($q) use($request){
                                                $q->where('date', '>=', $request->from);
                                            })
                                            ->when($request->filled('to'), function($q) use($request){
                                                $q->where('date', '<=', $request->to);
                                            });
                                        })
                                        ->select(DB::raw('SUM(price*quantity - buy_price*quantity)'));
                                }])
                                ->get();


            return view('reports/product_wise_profit', $data);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


}
