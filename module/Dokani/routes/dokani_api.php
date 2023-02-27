<?php

use Illuminate\Support\Facades\Route;
use Module\Dokani\Controllers\Api\AppController;
use Module\Dokani\Controllers\Api\AuthController;
use Module\Dokani\Controllers\Api\SaleController;
use Module\Dokani\Controllers\Api\UserController;
use Module\Dokani\Controllers\Api\BrandController;
use Module\Dokani\Controllers\Api\OrderController;
use Module\Dokani\Controllers\Api\BranchController;
use Module\Dokani\Controllers\Api\ReportController;
use Module\Dokani\Controllers\Api\PaymentController;
use Module\Dokani\Controllers\Api\CustomerController;
use Module\Dokani\Controllers\Api\PurchaseController;
use Module\Dokani\Controllers\Api\SupplierController;
use Module\Dokani\Controllers\Api\CollectionController;
use Module\Dokani\Controllers\Api\Product\UnitController;
use Module\Dokani\Controllers\Api\Account\GPartyController;
use Module\Dokani\Controllers\Api\VoucherPaymentController;
use Module\Dokani\Controllers\Api\Account\AccountController;
use Module\Dokani\Controllers\Api\Product\ProductController;
use Module\Dokani\Controllers\Api\Account\InvestorController;
use Module\Dokani\Controllers\Api\Product\CategoryController;
use Module\Dokani\Controllers\Api\Account\FundTransferController;
use Module\Dokani\Controllers\Api\Account\GeneralAccountController;


//public routes
Route::post('check-user-is-exists',     [AuthController::class, 'isUserIdExists']);

Route::post('register',                 [AuthController::class, 'register']);
Route::post('login',                    [AuthController::class, 'login']);

Route::get('profile',                   [AuthController::class, 'profile'])->middleware('auth:sanctum');
Route::put('profile',                   [AuthController::class, 'profileUpdate'])->middleware('auth:sanctum');

Route::post('change-pin',               [AuthController::class, 'updatePin'])->middleware('auth:sanctum');

Route::post('forgot-pin',               [AuthController::class, 'forgotPin']);


Route::group(['prefix' => 'v1'], function () {

    Route::get('shop_types', [AppController::class, 'shopType']);

});

Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum']], function () {

    Route::apiResources([
        'customers'         => CustomerController::class,
        'users'             => UserController::class,
        'suppliers'         => SupplierController::class,
        'sales'             => SaleController::class,
        'orders'            => OrderController::class,
        'purchases'         => PurchaseController::class,
        'voucher-payments'  => VoucherPaymentController::class,
        'collections'       => CollectionController::class,
        'payments'          => PaymentController::class,
        'accounts'          => AccountController::class,

    ]);

    Route::post('suppliers/update/{id}', [SupplierController::class, 'updateSupplier']);

    Route::get('shop_types', [AppController::class, 'shopType']);

    Route::post('dokan/delete', [AuthController::class, 'dokanDelete']);


    Route::get('dashboard-stats', [AppController::class, 'dashboardData']);

    Route::group(['prefix'  => 'product'], function () {

        Route::apiResources([
            'categories'    => CategoryController::class,
            'units'         => UnitController::class,
            'products'      => ProductController::class,
            'brands'        => BrandController::class,
        ]);
    });
    Route::get('branch',                            [BranchController::class, 'branch']);

    Route::post('product/brands/update/{id}', [BrandController::class, 'updateBrand']);
    Route::post('product/categories/update/{id}', [CategoryController::class, 'updateCategory']);


    Route::group(['prefix' => 'g-accounts'], function () {
        Route::resources([
            'charts'            => GeneralAccountController::class,
            'parties'           => GPartyController::class,
            'fund-transfers'    => FundTransferController::class,
            'investors'         => InvestorController::class,
        ]);
    });

    Route::post('g-accounts/parties/update/{id}', [GPartyController::class, 'update']);
    Route::post('g-accounts/charts/update/{id}', [GeneralAccountController::class, 'update']);
    Route::post('g-accounts/investors/balance-store/{id}', [InvestorController::class, 'balanceStore']);

    Route::post('g-accounts/investors/withdraw-balance/create', [InvestorController::class, 'withdrawCreate']);
    Route::get('g-accounts/investors/withdraw-balance/show/{id}', [InvestorController::class, 'withdrawShow']);
    Route::get('g-accounts/investors/withdraw-balance-list', [InvestorController::class, 'withdrawList']);
    Route::delete('g-accounts/investors/withdraw-balance/delete/{id}', [InvestorController::class, 'withdrawDelete']);

    Route::post('g-accounts/investors/profit-disbursement-update', [InvestorController::class, 'disbursementUpdate']);

    Route::group(['prefix' => 'reports'], function () {

        Route::get('cash-flow',                         [AppController::class, 'cashFlow']);
        Route::get('inventory-report',                  [ReportController::class, 'inventoryReport']);
        Route::get('alert-inventory',                   [ReportController::class, 'alertInventory']);
        Route::get('receiveable-due',                   [ReportController::class, 'receiveableDue']);
        Route::get('payable-due',                       [ReportController::class, 'payableDue']);
        Route::get('sales',                             [ReportController::class, 'sale']);
        Route::get('purchases',                         [ReportController::class, 'purchase']);
        Route::get('customer-ledger',                   [ReportController::class, 'customerLedger']);
        Route::get('supplier-ledger',                   [ReportController::class, 'supplierLedger']);
        Route::get('product-wise-sales',                [ReportController::class, 'productWiseSales']);
        Route::get('vat',                               [ReportController::class, 'vat']);
        Route::get('vat-details/{date}',                [ReportController::class, 'vatDetails']);
        Route::get('refer-wise-customer',               [ReportController::class, 'referWiseCustomer']);
        Route::get('refer-wise-customer-details/{id}',  [ReportController::class, 'referWiseCustomerDetails']);
        Route::get('chart-of-account',                  [ReportController::class, 'ChartOfAccount']);
        Route::get('income-expense',                    [ReportController::class, 'incomeExpense']);
        Route::get('profit',                            [ReportController::class, 'profit']);
        Route::get('investor-ledger',                   [ReportController::class, 'investorLedger']);

    });
});
