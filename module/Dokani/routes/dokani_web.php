<?php

use Module\Dokani\Models\Customer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Module\Dokani\Controllers\SaleController;
use Module\Dokani\Controllers\UnitController;
use Module\Dokani\Controllers\UserController;
use Module\Dokani\Controllers\BrandController;
use Module\Dokani\Controllers\OrderController;
use Module\Dokani\Controllers\BranchController;
use Module\Dokani\Controllers\CourierController;
use Module\Dokani\Controllers\CusAreaController;
use Module\Dokani\Controllers\PaymentController;
use Module\Dokani\Controllers\CategoryController;
use Module\Dokani\Controllers\CustomerController;
use Module\Dokani\Controllers\InvestorController;
use Module\Dokani\Controllers\PurchaseController;
use Module\Dokani\Controllers\SupplierController;
use Module\Dokani\Controllers\ChangePinController;
use Module\Dokani\Controllers\SMSManageController;
use Module\Dokani\Controllers\CollectionController;
use Module\Dokani\Controllers\ProductionController;
use Module\Dokani\Controllers\CusCategoryController;
use Module\Dokani\Controllers\FundTransferController;
use Module\Dokani\Controllers\SubscriptionController;
use Module\Dokani\Controllers\CustomerReferController;
use Module\Dokani\Controllers\PackageManageController;
use Module\Dokani\Controllers\PurchaseOrderController;
use Module\Dokani\Controllers\Report\ReportController;
use Module\Dokani\Controllers\StockTransferController;
use Module\Dokani\Controllers\Account\GPartyController;
use Module\Dokani\Controllers\PointConfigureController;
use Module\Dokani\Controllers\VoucherPaymentController;
use Module\Dokani\Controllers\Account\AccountController;
use Module\Dokani\Controllers\BusinessSettingController;
use Module\Dokani\Controllers\Product\ProductController;
use Module\Dokani\Controllers\ProductionIssueController;
use Module\Dokani\Controllers\StockAdjustmentController;
use Module\Dokani\Controllers\Report\InventoryController;
use Module\Dokani\Controllers\ProductionReceiveController;
use Module\Dokani\Controllers\SaleReturnExchangeController;
use Module\Dokani\Controllers\Product\ProductUploadController;
use Module\Dokani\Controllers\Account\GeneralAccountController;
use Module\Dokani\Controllers\PurchasesReturnExchangeController;
use Module\Dokani\Models\Purchase;

Route::group(['prefix' => 'dokani', 'as' => 'dokani.', 'middleware' => ['auth', 'permission']], function () {

    /*
    |--------------------------------------------------
    | Setup Route
    |--------------------------------------------------
    */
    Route::resources([
        'customers'                                     => CustomerController::class,
        'suppliers'                                     => SupplierController::class,
        'users'                                         => UserController::class,
        'sales'                                         => SaleController::class,
        'couriers'                                      => CourierController::class,
        'orders'                                        => OrderController::class,
        'purchases'                                     => PurchaseController::class,
        'business-profile'                              => BusinessSettingController::class,
        'collections'                                   => CollectionController::class,
        'payments'                                      => PaymentController::class,
        'voucher-payments'                              => VoucherPaymentController::class,
        'points'                                        => PointConfigureController::class,
        'refers'                                        => CustomerReferController::class,
        'sale-return-exchanges'                         => SaleReturnExchangeController::class,
        'purchase-return-exchanges'                     => PurchasesReturnExchangeController::class,
        'purchase-orders'                               => PurchaseOrderController::class,
        'branches'                                      => BranchController::class,
        'stock-transfers'                               => StockTransferController::class,
        'stock-adjustments'                             => StockAdjustmentController::class,

    ]);



    Route::group(['prefix' => 'customer'], function () {

        Route::resources([
            'cus_areas'                                 => CusAreaController::class,
            'cus_categories'                            => CusCategoryController::class,
        ]);
    });



    Route::get('sms-management',                        [SMSManageController::class, 'index'])->name('sms.manage');
    Route::post('sms-management-send',                  [SMSManageController::class, 'send'])->name('sms.manage.send');
    Route::get('client',                                [CustomerController::class, 'getClient'])->name('clients.index');





    Route::get('approve-stock-transfer/{id}',           [StockTransferController::class, 'approve'])->name('stock-transfers.approve');
    Route::post('approve-stock-transfer/{id}',          [StockTransferController::class, 'approveSubmit'])->name('stock-transfers.approve-submit');

    // Route::post('approve-sale-order/{id}',          [OrderController::class, 'approveSubmit'])->name('sale-orders.approve-submit');
    Route::post('approve-purchase-order/{id}',          [PurchaseOrderController::class, 'approveSubmit'])->name('purchase-orders.approve-submit');

    Route::get('approve-stock-adjustment/{id}',         [StockAdjustmentController::class, 'approve'])->name('stock-adjustments.approve');
    Route::post('approve-stock-adjustment/{id}',        [StockAdjustmentController::class, 'approveSubmit'])->name('stock-adjustments.approve-submit');

    Route::get('refer/transfer',                        [CustomerController::class, 'referTransfer'])->name('refer.transfer.index');
    Route::post('refer/transfer/store',                 [CustomerController::class, 'referTransferStore'])->name('refer.transfer.store');

    Route::any('default/customer/{id}',                 [CustomerController::class, 'isDefault'])->name('customer.default');

    Route::get('get-return-product',                    [SaleReturnExchangeController::class, 'getReturnProduct'])->name('get-return-product');
    Route::get('get-exchange-product',                  [SaleReturnExchangeController::class, 'getExchangeProduct'])->name('get-exchange-product');
    Route::get('delete/{id}',                           [SaleReturnExchangeController::class, 'destroy'])->name('destroy');

    Route::post('order-sale',                           [OrderController::class, 'orderSale'])->name('order-sale');
    Route::get('check-purchase-refenrece-no-unique',    [PurchaseController::class,'checkReferenceNoUnique'])->name('check-purchase-refenrece-no-unique');

    Route::get('create-sale',                           [SaleController::class, 'createSale'])->name('create-sale');
    Route::post('create-sale',                          [SaleController::class, 'store'])->name('create.sale');


    Route::get('create-purchase',                       [PurchaseController::class, 'createPurchase'])->name('create-purchase');
    Route::post('create-purchase',                      [PurchaseController::class, 'store'])->name('create.purchase');


    Route::post('point_value/edit/{id}',                [PointConfigureController::class, 'editPointValue'])->name('point.value.edit');


    Route::get('change-pin',                            [ChangePinController::class, 'index'])->name('changePin');
    Route::post('change-pin-update',                    [ChangePinController::class, 'update'])->name('changePinUpdate');




    Route::group(['prefix' => 'product'], function () {

        Route::resources([
            'units'                                     => UnitController::class,
            'categories'                                => CategoryController::class,
            'brands'                                    => BrandController::class,
            'products'                                  => ProductController::class,
            'product-uploads'                           => ProductUploadController::class,
        ]);



        Route::get('product-barcodes',                  [ProductController::class, 'barcode'])->name('product-barcode.index');
        Route::get('product-barcodes/{id}/print',       [ProductController::class, 'barcodePrint'])->name('product-barcode.print');

        Route::post('product-uploads-with-single-branch',  [ProductUploadController::class, 'productUploadWithSingleBranchStore'])->name('product-uploads-with-single-branch-store');

        Route::get('product-type-finish-goods',        [ProductController::class, 'productTypeFinishGoods'])->name('product-type-finish-goods');

        Route::get('get-products',                      [ProductController::class, 'getProduct'])->name('get-products-ajax');
        Route::get('get-purchase-products',             [ProductController::class, 'getPurchaseProduct'])->name('get-purchase-products-ajax');
        Route::post('create/brand',                     [ProductController::class, 'createBrand'])->name('create-brand');
        Route::any('get-searchable-products',           [ProductController::class, 'getSearchableProduct'])->name('get-searchable-products-ajax');
        Route::any('get-searchable-products-for-purchase', [ProductController::class, 'getSearchableProductForPurchase'])->name('get-searchable-products-for-purchase-ajax');

        Route::get('get-searchable-purchase-invoice', [ProductController::class, 'getSearchablePurchaseInvoice'])->name('get-searchable-purchase-invoice-ajax');


        Route::get('get-raw-materials-products',        [ProductController::class, 'getRawMaterialsProduct'])->name('get-raw-materials-products-ajax');
        Route::get('get-finish-products',               [ProductController::class, 'getFinishProduct'])->name('get-finish-products-ajax');
    });


    Route::group(['prefix' => 'production'], function () {

        Route::resources([
            'production-issues'                                     => ProductionIssueController::class,
            'production-receives'                                   => ProductionReceiveController::class,
        ]);
    });


    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {

        Route::get('inventory',                         [InventoryController::class, 'index'])->name('inventory');
        Route::get('product_ledger',                    [InventoryController::class, 'productLedger'])->name('product_ledger');
        Route::get('alert-inventory',                   [InventoryController::class, 'alertInventory'])->name('alert-inventory');

        Route::get('sales',                             [ReportController::class, 'sales'])->name('sales');
        Route::get('product-wise-sales',                [ReportController::class, 'productWiseSale'])->name('product-wise-sales');
        Route::get('vats',                              [ReportController::class, 'vats'])->name('vats');
        Route::get('vat-details/{date}',                [ReportController::class, 'vatDetails'])->name('vat-details');
        Route::get('purchase',                          [ReportController::class, 'purchase'])->name('purchase');
        Route::get('cash-flow',                         [ReportController::class, 'cashFlow'])->name('cash-flow');
        Route::get('customer-ledger',                   [ReportController::class, 'customerLedger'])->name('customer-ledger');
        Route::get('supplier-ledger',                   [ReportController::class, 'supplierLedger'])->name('supplier-ledger');

        Route::get('refer-wise-customer',               [ReportController::class, 'referWiseCustomer'])->name('refer-wise-customer');
        Route::get('refer-wise-customer-details/{id}',  [ReportController::class, 'referWiseCustomerDetails'])->name('refer-wise-customer.details');

        Route::get('payable-due',                       [ReportController::class, 'payableDue'])->name('payable-due');
        Route::get('receivable-due',                    [ReportController::class, 'receiveableDue'])->name('receivable-due');

        Route::get('chart-of-account',                  [ReportController::class, 'ChartOfAccount'])->name('chart-of-account');
        Route::get('income-expense',                    [ReportController::class, 'incomeExpense'])->name('income-expense');
        Route::get('profit',                            [ReportController::class, 'profit'])->name('profit');
        Route::get('product-wise-profit',               [ReportController::class, 'productWiseProfit'])->name('product-wise-profit');
        Route::get('investor-ledger',                   [ReportController::class, 'investorLedger'])->name('investor-ledger');

        //EXPORT REPORT

        Route::get('refer-wise-customer/export/{id}',   [ReportController::class, 'referWiseCustomerExport'])->name('refer-wise-customer.export');
        Route::get('income-expense/export',             [ReportController::class, 'incomeExpenseExport'])->name('income-expense.export');


    });


    Route::group(['prefix' => 'g-accounts', 'as' => 'ac.'], function () {

        Route::resources([
            'charts'                                    => GeneralAccountController::class,
            'parties'                                   => GPartyController::class,
            'fund-transfers'                            => FundTransferController::class,
            'accounts'                                  => AccountController::class,
            'investors'                                 => InvestorController::class,
        ]);


        Route::get('investor/balance-add/{id}',                     [InvestorController::class, 'balanceAdd'])->name('investor.balance.add');
        Route::post('investor/balance-store',                       [InvestorController::class, 'balanceStore'])->name('investor.balance.store');
        Route::get('investor/withdraw-balance-list',                [InvestorController::class, 'withdrawList'])->name('investor.withdraw.list');
        Route::get('investor/withdraw-balance',                     [InvestorController::class, 'withdraw'])->name('investor.withdraw');
        Route::post('investor/withdraw-balance/create',             [InvestorController::class, 'withdrawCreate'])->name('investor.withdraw.create');
        Route::get('investor/withdraw-balance/show/{id}',           [InvestorController::class, 'withdrawShow'])->name('investor.withdraw.show');
        Route::delete('investor/withdraw-balance/delete/{id}',      [InvestorController::class, 'withdrawDelete'])->name('investor.withdraw.delete');

        Route::get('profit-disbursement',                           [InvestorController::class, 'disbursement'])->name('disbursement');
        Route::post('profit-disbursement-update',                   [InvestorController::class, 'disbursementUpdate'])->name('disbursement.update');
    });



    Route::get('account/balance',                                   [AccountController::class, 'getBalance'])->name('get-account-balance-ajax');


    Route::post('subscription-payments/success',                    [SubscriptionController::class, 'paymentSuccess'])->name('subscription.success');
    Route::get('subscription-payments/failed',                      [SubscriptionController::class, 'paymentSuccessOrFailed'])->name('subscription.failed');
    Route::get('subscription-payments/cancel',                      [SubscriptionController::class, 'paymentSuccessOrFailed'])->name('subscription.cancel');
    Route::get('subscription-payments/alert',                       [SubscriptionController::class, 'paymentAlert'])->name('subscription.alert');
    Route::get('subscription-payments',                             [SubscriptionController::class, 'index'])->name('subscription.index');

    Route::get('check-subscription',                                [HomeController::class, 'subscriptionCheck'])->name('subscription');
    Route::get('permission_error',                                  [HomeController::class, 'permissionCheck'])->name('permission');







    /**
     * -------------------------------------------------------------------------------------------------------------------------------------
     * SUPER ADMIN ROUTE CONFIG
     * -------------------------------------------------------------------------------------------------------------------------------------
     */
    Route::group(['middleware' => ['super-admin']], function () {

        //custom url
        Route::get('super-admin/all-dokan/',                        [PackageManageController::class, 'index'])->name('shop.index');
        Route::post('super-admin/end_date/update/{id}',             [PackageManageController::class, 'endDateChange'])->name('end_date.change');
        Route::post('super-admin/start_date/update/{id}',           [PackageManageController::class, 'startDateChange'])->name('start_date.change');
        Route::get('super-admin/package-type',                      [PackageManageController::class, 'packageType'])->name('package-type.change');
        Route::get('super-admin/dokan/create',                      [PackageManageController::class, 'create'])->name('shop.create');
        Route::post('super-admin/dokan/store',                      [PackageManageController::class, 'store'])->name('shop.store');
        Route::get('super-admin/dokan/edit/{id}',                   [PackageManageController::class, 'edit'])->name('shop.edit');
        Route::post('super-admin/dokan/update/{id}',                [PackageManageController::class, 'update'])->name('shop.update');

        Route::get('super-admin/dokan/package',                     [PackageManageController::class,'getPackage'])->name('shop.package');
        Route::post('super-admin/dokan/package/month/{id}',         [PackageManageController::class, 'packageMonth'])->name('shop.package.month');
        Route::post('super-admin/dokan/package/price/{id}',         [PackageManageController::class, 'packagePrice'])->name('shop.package.price');

        Route::get('super-admin/dokan/sms/manage',                  [PackageManageController::class, 'smsManage'])->name('shop.sms.manage');
        Route::get('super-admin/dokan/sms/list',                    [PackageManageController::class, 'smsList'])->name('shop.sms.list');
        Route::post('super-admin/dokan/sms/store',                  [PackageManageController::class, 'smsStore'])->name('shop.sms.store');
        Route::get('super-admin/dokan/sms/edit/{id}',               [PackageManageController::class, 'smsEdit'])->name('shop.sms.edit');
        Route::post('super-admin/dokan/sms/update/{id}',            [PackageManageController::class, 'smsUpdate'])->name('shop.sms.update');
        Route::delete('super-admin/dokan/sms/delete/{id}',          [PackageManageController::class, 'smsDelete'])->name('shop.sms.delete');
    });


    Route::get('manage-sms',                                        [SMSManageController::class, 'manage'])->name('manage.sms');
    Route::post('send/manual-sms',                                  [SMSManageController::class, 'manualSms'])->name('manual.sms');
    Route::post('send/sms',                                         [SMSManageController::class, 'sendSms'])->name('send.sms');


    Route::get('update-existing-customer-current-balance', function () {
        $customers = Customer::with('sales')->get();

        foreach($customers as $customer) {

            $balance = 0;

            foreach($customer->sales as $sale) {
                $balance += $sale->due_amount;
            }


            $customer->update(['balance' => $balance]);
        }


        return redirect()->route('home')->withMessage('Update Existing Customer Current Balance');
    });



});
