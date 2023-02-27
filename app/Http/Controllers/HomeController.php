<?php

namespace App\Http\Controllers;

// use GuzzleHttp\Psr7\Request;
use Module\Dokani\Services\HelperService;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Module\Dokani\Models\Sale;
use Module\Dokani\Models\Order;
use Module\Dokani\Models\Branch;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\Purchase;
use Module\Dokani\Models\ManageSmsApi;
use Module\Dokani\Models\Supplier;
use Module\Dokani\Models\VoucherPayment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        ini_set('max_execution_time', -1);
        // $data = (new HelperService())->dahboardData();


        // $startDateOfMonth       = Carbon::now()->startOfMonth()->format('Y-m-d');
        // $endDateOfMonth         = Carbon::now()->endOfMonth()->format('Y-m-d');
        // $today                  = Carbon::now()->format('Y-m-d');
        // $yesterday              = Carbon::yesterday()->format('Y-m-d');
        // $todaySales             = Sale::dokani()->searchByBranch('branch_id');
        // $yesterdaySales         = Sale::where('dokan_id', dokanId())->searchByBranch('branch_id');
        // $todayPurchase          = Purchase::where('dokan_id', dokanId());
        // $yesterdayPurchase      = Purchase::where('dokan_id', dokanId());

        // $data['total_product']      = Product::dokani()->count();

        // //     SALE
        // $data['today_sale']         = $todaySales->where('date', $today)->sum('payable_amount');
        // $data['yesterday_sale']     = $yesterdaySales->whereDate('date', $yesterday)->sum('payable_amount');

        // //    PURCHASE
        // $data['today_purchase']     = $todayPurchase->whereDate('date',$today)->sum('payable_amount');
        // $data['yesterday_purchase'] = $yesterdayPurchase->whereDate('date', $yesterday)->sum('payable_amount');

        // //    INCOMES
        // $data['today_income']       = ProfitCalculate(date('Y-m-d'), $request->branch_id);
        // $data['yesterday_income']   = ProfitCalculate($yesterday, $request->branch_id);

        // $data['net_income']         = Sale::dokani()
        //                                 ->withCount(['details as net_income' => function ($query) {
        //                                     $query->select(DB::raw('SUM(price - buy_price - discount)'));
        //                                 }])
        //                                 ->searchByBranch('branch_id')
        //                                 ->get();

        // $data['today_expense']      = 0;
        // $data['yesterday_expense']  = 0;

        // $data['net_expense']        = 0;

        // $data['sms_balance']        = (new HelperService())->getAPIBalance();

        // $data['online_order']       = Order::where('dokan_id', dokanId())
        //                             ->count();

        // $data['cash_flows']         = CashFlow::where('dokan_id', dokanId())
        //                                 ->where('date', '>=', $startDateOfMonth)
        //                                 ->where('date', '<=', $endDateOfMonth)
        //                                 ->get(['id', 'date', 'amount', 'balance_type']);

        // $data['supplier_due'] = Supplier::dokani()->sum('balance');
        // $data['customer_due'] = Customer::dokani()->sum('balance');
        // $data['g_acc'] = VoucherPayment::dokani()->get();

        // // dd($data['supplier_due']);
        // if(auth()->user()->type == 'owner'){
        //     $data['branches'] = $branches = Branch::dokani()->with('employee')->get();
        // }else{
        //     $data['branches'] = $branches = Branch::dokani()
        //                         ->whereHas('users',fn($q)=>$q->where('user_id',auth()->user()->id))
        //                         ->with('employee')
        //                         ->get();
        // }

        // if($request->filled('is_dynamic')){
        //     return response()->json($data);
        // }


        // return $data;
        // return view('home', $data);
        return view('home_blank');
    }



    public function subscriptionCheck()
    {
        return 12345;
    }


    public function permissionCheck()
    {
        return '1234';
    }



    public function cashFlowCart(Request $request)
    {
        // return $request->year_month;
        $startDateOfMonth       = date('Y-m-01', strtotime($request->year_month));
        $endDateOfMonth         = date('Y-m-t', strtotime($request->year_month));
        $number_of_days = cal_days_in_month(CAL_GREGORIAN,date('m', strtotime($request->year_month)),date('Y', strtotime($request->year_month)));

        $data    = CashFlow::dokani()
                            ->where('date', '>=', $startDateOfMonth)
                            ->where('date', '<=', $endDateOfMonth)
                            ->get(['id', 'date', 'amount', 'balance_type']);
        return response()->json([
            'data' => $data,
            'total_days' => $number_of_days,
            'last_date'  => $endDateOfMonth
        ]);
    }
}
