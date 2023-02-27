<?php

use Carbon\Carbon;
use App\Models\User;
use Module\Dokani\Models\Sale;
use Module\Dokani\Models\Courier;
use Module\Dokani\Models\CusArea;
use Module\Dokani\Models\Product;
use Illuminate\Support\Facades\DB;
use Module\Dokani\Models\CashFlow;
use Module\Dokani\Models\Category;
use Module\Dokani\Models\Customer;
use Module\Dokani\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Module\Dokani\Models\AccountType;
use Module\Dokani\Models\CusCategory;
use Module\Dokani\Models\PointSetting;
use Module\Dokani\Models\ProductStock;
use Module\Dokani\Models\BusinessSetting;
use Module\Dokani\Services\CashFlowService;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StatusUpdateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

/*
|---------------------------------------------------------------------------------------
|CUSTOM LOGIN
|---------------------------------------------------------------------------------------
*/

Route::post('signin', [LoginController::class, 'signin'])->name('signin');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/dashboard-cash-flow-chart', [App\Http\Controllers\HomeController::class, 'cashFlowCart'])->name('dashboard-cash-flow-chart-ajax');


Route::group(['middleware' => 'auth'], function () {



    // Custom Query


    Route::get('dokani/update-product-stock-table', function () {

        $product_stocks = ProductStock::query()->get();

        foreach ($product_stocks as $key => $product_stock){

            $product = Product::where('id',$product_stock->product_id)->first();

            $stock_in_value = ($product_stock->opening_quantity * optional($product)->purchase_price)
                + ($product_stock->purchased_quantity * optional($product)->purchase_price)
                + ($product_stock->sold_return_quantity * optional($product)->purchase_price);

            $stock_out_value = ($product_stock->wastage_quantity * optional($product)->purchase_price)
                + ($product_stock->sold_quantity * optional($product)->purchase_price)
                + ($product_stock->purchase_return_quantity * optional($product)->purchase_price);

            $product_stock->update([
                'dokan_id'               => dokanId(),
                'lot'                    => 'N/A',
                'stock_in_value'         => $stock_in_value,
                'stock_out_value'        => $stock_out_value,
            ]);
        }
        return redirect()->back();
    });


    Route::get('dokani/cash-flow/delete-row', function () {

        $cashFlows = CashFlow::where('description', 'like', '%'.'Delete')->get();

        foreach ($cashFlows as $key => $cashFlow){

            $cashFlow->delete();
        }
        return redirect()->back();
    });



    Route::get('dokani/voucher-payment-date', function () {

        $voucherDetails = \Module\Dokani\Models\VoucherPaymentDetail::query()->get();

        foreach ($voucherDetails as $key => $voucherDetail){

            $voucher = \Module\Dokani\Models\VoucherPayment::query()->where('id',$voucherDetail->voucher_payment_id)->first();
            $voucherDetail->update([
                'date' => $voucher->date,
            ]);
        }
        return redirect()->back();
    });



    Route::get('dokani/cash-flow/income-expense/type-change', function () {

        $cashFlows = CashFlow::query()->whereIn('transactionable_type', ['In', 'Out'])->get();

        foreach ($cashFlows as $key => $cashFlow){

            $cashFlow->update([
                'transactionable_type' => $cashFlow->transactionable_type == 'In' ?'Income' : 'Expense',
            ]);
        }
        return redirect()->back();
    });


    Route::get('dokani/delete/all/cashflow', function () {

        $cashFlows = CashFlow::dokani()->get();

        foreach ($cashFlows as $key => $cashFlow){

            $cashFlow->delete();
        }
        return redirect()->back();
    });


    Route::get('dokani/cash-flow/income-expense/balance-type-change', function () {

        $cashFlows = CashFlow::query()->whereIn('balance_type', ['Income', 'Expense'])->get();

        foreach ($cashFlows as $key => $cashFlow){

            $cashFlow->update([
                'balance_type' => $cashFlow->balance_type == 'Income' ?'In' : 'Out',
            ]);
        }
        return redirect()->back();
    });


    Route::get('dokani/pos-test', function () {

        $data['categories'] = Category::dokani()->pluck('name', 'id');
        $data['invoice']    = BusinessSetting::where('user_id', User::first()->id)->first();
        $data['customers']  = Customer::dokani()->get();
        $data['areas']      = CusArea::dokani()->get();
        $data['categories'] = CusCategory::dokani()->get();
        $data['users']      = User::whereDokanId(auth()->id())->get();
        $data['couriers']   = Courier::dokani()->get();
        $data['point']      = PointSetting::dokani()->first();
        $data['products']   = Product::dokani()->with('unit')
            ->withCount(['stocks as available_qty' => function ($query) {
                $query->select(DB::raw('SUM(available_quantity)'));
            }])
            ->latest()
            ->paginate(25);

        return view('sales.sales.pos-test',$data);
    });


    Route::get('dokani/product-stock/dokan_id', function () {

        $products = Product::query()->get();

        foreach ($products as $key => $product){

            $stock = ProductStock::where('product_id', $product->id)->first();
            //dd($product);
            optional($stock)->update([
                'dokan_id' => $product->dokan_id
            ]);
        }
        return redirect()->back();
    });


    Route::get('dokani/available-quantity-calculate', function () {

        $stocks = ProductStock::query()->get();

        foreach ($stocks as $key => $stock){

            $available_quantity = $stock->opening_quantity + $stock->purchased_quantity + $stock->sold_return_quantity - $stock->sold_quantity - $stock->wastage_quantity - $stock->purchase_return_quantity;

            $stock->update([
                'available_quantity'   => $available_quantity,
            ]);
        }
        return redirect()->back();
    });



    Route::get('dokani/opening-quantity-solve', function () {

        $products = Product::query()->get();

        foreach ($products as $key => $product){

            $stock = ProductStock::where('product_id', $product->id)->first();

            $available_quantity = optional($stock)->opening_quantity + optional($stock)->purchased_quantity + optional($stock)->sold_return_quantity - optional($stock)->sold_quantity - optional($stock)->wastage_quantity - optional($stock)->purchase_return_quantity;

            //dd($product);
            optional($stock)->update([
                'opening_quantity'      => $product->opening_stock,
                'available_quantity'    => $available_quantity
            ]);
        }
        return redirect()->back();
    });


    Route::get('dokani/account/name', function () {

        $accounts = \Module\Dokani\Models\Account::query()->get();

        foreach ($accounts as $key => $account){

            $type = AccountType::where('id', $account->account_type_id)->first();

            //dd($product);
            optional($account)->update([
                'name'      => $type->name,
            ]);
        }
        return redirect()->back();
    });

    Route::get('dokani/cashflow-report/description-change', function () {

        $cashFlows = CashFlow::where('description', 'like', '%'.'Create')->get();

        foreach ($cashFlows as $key => $cashFlow){

            $cashFlow->update([
                'description'   => substr($cashFlow->description,0,-6)
            ]);
        }
        return redirect()->back();
    });

    Route::get('dokani/product-ledger/description-change', function () {

        $products = \Module\Dokani\Models\ProductLedger::where('sourceable_type', 'like', '%'.'Details')->get();

        foreach ($products as $key => $product){

            $product->update([
                'sourceable_type'   => substr($product->sourceable_type,0,-8)
            ]);
        }
        return redirect()->back();
    });

    Route::get('dokani/product-ledger/in-out', function () {

        $products = \Module\Dokani\Models\ProductLedger::where('sourceable_type','Sale')->get();

        foreach ($products as $key => $product){

            $product->update([
                'type'   => 'Out'
            ]);
        }
        return redirect()->back();
    });

    Route::get('dokani/again-create-purchase', [\Module\Dokani\Services\PurchaseService::class, 'againPurchase']);
    Route::get('dokani/again-create-sale', [\Module\Dokani\Services\SaleService::class, 'againSale']);
    Route::get('dokani/again-add-product', [\Module\Dokani\Services\ProductService::class, 'againAddProduct']);
    Route::get('dokani/again-return-product', [\Module\Dokani\Services\SaleReturnExchangeService::class, 'againReturnProduct']);


    Route::get('dokani/again-create-purchase-cashflow', function () {

        $purchases = Purchase::dokani()->get();

        foreach ($purchases as $purchase){

            if ($purchase->paid_amount > 0) {
                (new CashFlowService())->transaction(
                    $purchase->id,
                    'Purchase',
                    $purchase->paid_amount,
                    'Out',
                    'Purchase Create',
                    $purchase->account_id,
                    null,
                    $purchase->date
                );
            }
        }

        return redirect()->back();
    });


    Route::get('dokani/again-create-sale-cashflow', function () {

        $sales = Sale::dokani()->get();

        foreach ($sales as $sale){

            if ($sale->paid_amount > 0) {
                (new CashFlowService())->transaction(
                    $sale->id,
                    'Sale',
                    $sale->paid_amount,
                    'In',
                    'Sale Create',
                    $sale->account_id,
                    null,
                    $sale->date
                );
            }
        }

        return redirect()->back();
    });


    Route::get('dokani/voucher-payment-cashflow', function () {

        $payments = \Module\Dokani\Models\VoucherPayment::dokani()->get();

        foreach ($payments as $payment) {

            if (($payment->total_amount ?? 0) > 0) {

                $balance_type = $payment->type == 'income' ? 'Income' : 'Expense';
                $description = $payment->type == 'income' ? 'Income' : 'Expense';

                (new CashFlowService())->transaction(
                    $payment->id,
                    $balance_type,
                    $payment->total_amount,
                    $balance_type,
                    $description,
                    $payment->account_id,
                    null,
                    $payment->date
                );
            }
        }

        return redirect()->back();
    });



    Route::get('dokani/due-collection-cash-flow', function () {

        $collections = \Module\Dokani\Models\Collection::dokani()->get();

        foreach ($collections as $collection) {

            (new CashFlowService())->transaction(
                $collection->id,
                'Collection',
                $collection->paid_amount,
                'In',
                'Due Collection',
                $collection->account_id,
                null,
                $collection->date
            );
        }

        return redirect()->back();
    });



    Route::get('dokani/opening-account-balance-cash-flow', function () {

        $openings = \Module\Dokani\Models\Account::dokani()->get();

        foreach ($openings as $opening) {

            if ($opening->opening_balance > 0){
                (new CashFlowService())->transaction(
                    $opening->id,
                    'Account',
                    $opening->opening_balance,
                    'In',
                    'Account Opening Balance',
                    $opening->id,
                    null,
                    Carbon::parse($opening->created_at)->format('Y-m-d')
                );
            }
        }

        return redirect()->back();
    });



    Route::get('dokani/payment-cash-flow', function () {

        $payments = \Module\Dokani\Models\Payment::dokani()->get();

        foreach ($payments as $payment) {

            (new CashFlowService())->transaction(
                $payment->id,
                'Payment',
                $payment->paid_amount,
                'Out',
                'Payment',
                $payment->account_id,
                null,
                $payment->date
            );
        }

        return redirect()->back();
    });



    Route::get('dokani/fund-transfer-cashflow', function () {

        $funds = \Module\Dokani\Models\FundTransfer::dokani()->get();

        foreach ($funds as $fund) {

            $date = Carbon::parse($fund->created_at)->format('Y-m-d');
            (new CashFlowService())->transaction($fund->id, 'Fund Transfer', $fund->amount, 'Out', 'Fund Transfer',$fund->from_account_id,null,$date);
            (new CashFlowService())->transaction($fund->id, 'Fund Transfer', $fund->amount, 'In', 'Fund Transfer',$fund->to_account_id,null,$date);
        }

        return redirect()->back();
    });


    Route::get('dokani/product-stock-log/purchase-details', function () {

        $stocks = \Module\Dokani\Models\ProductStock::dokani()->get();

        foreach ($stocks as $key => $stock){
            $log = \Module\Dokani\Models\ProductStockLog::dokani()->where('product_id',$stock->product_id)
                ->where('lot',$stock->lot)
                ->where('sourceable_type','Purchase Details')->first();

                $stock->lot == 'N/A' ? $lot = null : $lot = $stock->lot;

            $purchaseDetail = \Module\Dokani\Models\PurchaseDetail::where('lot', $lot)->where('product_id',$stock->product_id)->first();



            if (!$log){
                if ($purchaseDetail){
                    \Module\Dokani\Models\ProductStockLog::create([
                        'dokan_id'              => dokanId(),
                        'product_id'            => $stock->product_id,
                        'sourceable_type'       =>'Purchase Details',
                        'sourceable_id'         => $purchaseDetail->id,
                        'date'                  => \Illuminate\Support\Carbon::parse($stock->created_at)->format('Y-m-d'),
                        'lot'                   =>$stock->lot,
                        'expiry_at'             =>$stock->expiry_at,
                        'quantity'              =>$purchaseDetail->quantity,
                        'actual_quantity'       =>$purchaseDetail->quantity,
                        'stock_type'            =>'In',
                        'purchase_price'        => $purchaseDetail->price,

                    ]);
                }
            }
        }




        return redirect()->back();
    });



    Route::get('dokani/due-supplier-list-edit', function () {

        $purchase = \Module\Dokani\Models\Purchase::dokani()->get();

        foreach ($purchase as $key => $item){

            $supplier = \Module\Dokani\Models\Supplier::dokani()->where('id',$item->supplier_id)->first();
            $supplier->update([
                'balance' => $item->due_amount,
            ]);
        }
        return redirect()->back();
    });

    Route::get('dokani/payable_due/calculate', function () {

        $purchase = \Module\Dokani\Models\Purchase::dokani()->get();

        foreach ($purchase as $key => $item){

            $payable_amount = $item->paid_amount + $item->previous_due - $item->discount;
            $item->update([
                'payable_amount' => $payable_amount,
            ]);
        }
        return redirect()->back();
    });



    Route::get('dokani/voucher-payment/invoice_add', function () {

        $vouchers = \Module\Dokani\Models\VoucherPayment::dokani()->get();
        $collections = \Module\Dokani\Models\Collection::dokani()->get();
        $payments = \Module\Dokani\Models\Payment::dokani()->get();

        foreach ($vouchers as $key => $item){

            $item->update([
                'invoice_no' => "#VP-" . str_pad(rand(1,1000), 5, '0', 0),
            ]);
        }
        foreach ($collections as $key => $item){

            $item->update([
                'invoice_no' => "#C-" . str_pad(rand(1,1000), 5, '0', 0),
            ]);
        }
        foreach ($payments as $key => $item){

            $item->update([
                'invoice_no' => "#PM-" . str_pad(rand(1,1000), 5, '0', 0),
            ]);
        }
        return redirect()->back();
    });


    Route::post('status-update', [StatusUpdateController::class, 'statusUpdate'])->name('status-update');
});
