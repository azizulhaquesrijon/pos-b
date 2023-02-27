@if (hasAnyPermission(['dokani.sales.create','dokani.orders.create', 'dokani.sale-return-exchanges.create' ,'dokani.sales.index', 'dokani.collections.create', 'dokani.collections.index'], $slugs))
    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-shopping-bag"></i>
            <span class="menu-text">Sales</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">

            @if (hasPermission('dokani.sales.create', $slugs))
                <li id="pos_create">
                    <a href="{{ route('dokani.sales.create') }}" target="_blank">
                        <i class="menu-icon fa fa-caret-right"></i>POS Sale</a>
                    <b class="arrow"></b>
                </li>

                <li id="sales_new">
                    <a href="{{ route('dokani.create-sale') }}">
                        <i class="menu-icon fa fa-caret-right"></i>New Sale</a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.sales.index', $slugs))
                <li>
                    <a href="{{ route('dokani.sales.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>Sales list</a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.orders.create', $slugs))
                <li id="order_create">
                    <a href="{{ route('dokani.orders.create') }}">
                        <i class="menu-icon fa fa-caret-right"></i>Order</a>
                    <b class="arrow"></b>
                </li>

                <li id="order_list">
                    <a href="{{ route('dokani.orders.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>Order List</a>
                    <b class="arrow"></b>
                </li>
             @endif

            @if (hasPermission('dokani.collections.create', $slugs))
                <li>
                    <a href="{{ route('dokani.collections.create') }}">
                        <i class="menu-icon fa fa-caret-right"></i>Due Collection</a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.collections.index', $slugs))
                <li>
                    <a href="{{ route('dokani.collections.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>Collection List</a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.sale-return-exchanges.create', $slugs))
                <li>
                    <a href="{{ route('dokani.sale-return-exchanges.create') }}">
                        <i class="menu-icon fa fa-caret-right"></i>Sales Return Exchange</a>
                    <b class="arrow"></b>
                </li>
            @endif
                @if (hasPermission('dokani.sale-return-exchanges.index', $slugs))
                    <li>
                        <a href="{{ route('dokani.sale-return-exchanges.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>Sales Return List</a>
                        <b class="arrow"></b>
                    </li>
                @endif

                <li id="employees">
                    <a href="" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Courier
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <b class="arrow"></b>
                    <ul class="submenu">
                        @if (hasPermission('dokani.couriers.create', $slugs))
                            <li id="add_employee">
                                <a href="{{ route('dokani.couriers.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Add Courier
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        @if (hasPermission('dokani.couriers.index', $slugs))
                            <li id="employee_list">
                                <a href="{{ route('dokani.couriers.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Courier List
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                    </ul>
                </li>
        </ul>
    </li>
@endif


@if (hasAnyPermission(['dokani.purchases.create', 'dokani.purchases.index', 'dokani.payments.create', 'dokani.payments.index'], $slugs))
    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-cart-plus"></i>
            <span class="menu-text">Purchases</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">

            @if (hasPermission('dokani.purchases.create', $slugs))
                <li>
                    <a href="{{ route('dokani.purchases.create') }}" target="_blank">
                        <i class="menu-icon fa fa-caret-right"></i>Add Purchase</a>
                    <b class="arrow"></b>
                </li>

                 <li id="purchase_new">
                    <a href="{{ route('dokani.create-purchase') }}">
                        <i class="menu-icon fa fa-caret-right"></i>New Purchase</a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.purchases.index', $slugs))
                <li>
                    <a href="{{ route('dokani.purchases.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>Purchases List</a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.orders.create', $slugs))
            <li id="order_create">
                <a href="{{ route('dokani.purchase-orders.create') }}">
                    <i class="menu-icon fa fa-caret-right"></i>Order</a>
                <b class="arrow"></b>
            </li>

            <li id="order_list">
                <a href="{{ route('dokani.purchase-orders.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>Order List</a>
                <b class="arrow"></b>
            </li>
         @endif




            @if (hasPermission('dokani.payments.create', $slugs))
                <li>
                    <a href="{{ route('dokani.payments.create') }}">
                        <i class="menu-icon fa fa-caret-right"></i>Due Payment</a>
                    <b class="arrow"></b>
                </li>
            @endif





            @if (hasPermission('dokani.payments.index', $slugs))
                <li>
                    <a href="{{ route('dokani.payments.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>Due Payment List</a>
                    <b class="arrow"></b>
                </li>
            @endif


            @if (hasPermission('dokani.sale-return-exchanges.create', $slugs))
            <li>
                <a href="{{ route('dokani.purchase-return-exchanges.create') }}">
                    <i class="menu-icon fa fa-caret-right"></i>Purchase Return Exchange</a>
                <b class="arrow"></b>
            </li>
        @endif
            @if (hasPermission('dokani.sale-return-exchanges.index', $slugs))
                <li>
                    <a href="{{ route('dokani.purchase-return-exchanges.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>Purchase Return List</a>
                    <b class="arrow"></b>
                </li>
            @endif
        </ul>
    </li>
@endif

<li>
    <a href="#" class="dropdown-toggle">
        <i class="menu-icon fa fa-users"></i>
        <span class="menu-text"> Customers </span>

        <b class="arrow fa fa-angle-down"></b>
    </a>
    <b class="arrow"></b>
    <ul class="submenu">
        @if (hasPermission('dokani.customers.index', $slugs))
            <li id="customer_info">
                <a href="{{ route('dokani.customers.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Customers Info
                </a>
                <b class="arrow"></b>
            </li>
        @endif

            @if (hasPermission('dokani.customers.client.view', $slugs))
                <li id="customer_info">
                    <a href="{{ route('dokani.clients.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Client Info
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

        @if (hasPermission('dokani.customers.store', $slugs))
            <li>
                <a href="{{ route('dokani.customers.create') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Add Customer
                </a>
                <b class="arrow"></b>
            </li>
        @endif

            @if (hasPermission('dokani.cus_areas.index', $slugs))
                <li>
                    <a href="{{ route('dokani.cus_areas.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Area
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.cus_categories.index', $slugs))
                <li>
                    <a href="{{ route('dokani.cus_categories.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Category
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.refer.transfer.index', $slugs))
                <li>
                    <a href="{{ route('dokani.refer.transfer.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Refer Balance Transfer
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

    </ul>
</li>

@if (hasAnyPermission(['dokani.products.create', 'dokani.products.index', 'dokani.units.index', 'dokani.categories.index', 'dokani.product-barcode.index'], $slugs))
<li>
    <a href="#" class="dropdown-toggle">
        <i class="menu-icon fa fa-list"></i>
        <span class="menu-text"> Products </span>
        <b class="arrow fa fa-angle-down"></b>
    </a>
    <b class="arrow"></b>
    <ul class="submenu">

        @if (hasPermission('dokani.products.create', $slugs))
            <li>
                <a href="{{ route('dokani.products.create') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Add Product
                </a>
                <b class="arrow"></b>
            </li>
        @endif


        @if (hasPermission('dokani.products.index', $slugs))
            <li>
                <a href="{{ route('dokani.products.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Products List
                </a>
                <b class="arrow"></b>
            </li>
        @endif


        @if (hasPermission('dokani.units.index', $slugs))
            <li>
                <a href="{{ route('dokani.units.index') }}">
                    <i class="menu-icon fa fa-tag"></i>
                    <span class="menu-text"> Unit</span>
                </a>
            </li>
        @endif



        @if (hasPermission('dokani.categories.index', $slugs))
            <li>
                <a href="{{ route('dokani.categories.index') }}">
                    <i class="menu-icon fa fa-tag"></i>
                    <span class="menu-text"> Categories </span>
                </a>
            </li>
        @endif


            @if (hasPermission('dokani.brands.index', $slugs))
                <li>
                    <a href="{{ route('dokani.brands.index') }}">
                        <i class="menu-icon fa fa-tag"></i>
                        <span class="menu-text"> Brands </span>
                    </a>
                </li>
            @endif


        @if (hasPermission('dokani.products.create', $slugs))
            <li class="hasQuery">
                <a href="{{ route('dokani.products.create', ['upload' => 'product']) }}">
                    <i class="menu-icon fa fa-upload"></i>
                    <span class="menu-text"> Bulk Product </span>
                </a>
            </li>
        @endif

        @if (hasPermission('dokani.product-barcode.index', $slugs))
            <li>
                <a href="{{ route('dokani.product-barcode.index') }}">
                    <i class="menu-icon fa fa-barcode"></i>
                    <span class="menu-text"> Manage Barcode </span>
                </a>
            </li>
        @endif
    </ul>
</li>
@endif


@if (hasAnyPermission(['dokani.production-issue.store', 'dokani.production-receive.store'], $slugs))
<li>
    <a href="#" class="dropdown-toggle">
        <i class="menu-icon fa fa-industry"></i>
        <span class="menu-text"> Production </span>
        <b class="arrow fa fa-angle-down"></b>
    </a>
    <b class="arrow"></b>
    <ul class="submenu">

        @if (hasPermission('dokani.production-issue.store', $slugs))
            <li>
                <a href="{{ route('dokani.production-issues.create') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Production Issues
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.production-receive.store', $slugs))
            <li>
                <a href="{{ route('dokani.production-receives.create') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Receive Product
                </a>
                <b class="arrow"></b>
            </li>
        @endif

    </ul>
</li>
@endif


<li>
    <a href="#" class="dropdown-toggle">
        <i class="menu-icon fa fa-users"></i>
        <span class="menu-text"> Suppliers </span>
        <b class="arrow fa fa-angle-down"></b>
    </a>
    <b class="arrow"></b>
    <ul class="submenu">

        @if (hasPermission('dokani.suppliers.index', $slugs))
            <li>
                <a href="{{ route('dokani.suppliers.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Supplier List
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.suppliers.store', $slugs))
            <li>
                <a href="{{ route('dokani.suppliers.create') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Add Supplier
                </a>
                <b class="arrow"></b>
            </li>
        @endif

    </ul>
</li>

@if (hasBranching())

    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-university"></i>
            <span class="menu-text"> Branches </span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">

            @if (hasPermission('dokani.branches.store', $slugs))
                <li>
                    <a href="{{ route('dokani.branches.create') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Add Branch
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.branches.index', $slugs))
                <li>
                    <a href="{{ route('dokani.branches.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Branch List
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

        </ul>
    </li>
    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-exchange"></i>
            <span class="menu-text"> Stock Transfer </span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">

            @if (hasPermission('dokani.stock-transfers.store', $slugs))
                <li>
                    <a href="{{ route('dokani.stock-transfers.create') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Add Stock Transfer
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.stock-transfers.index', $slugs))
                <li>
                    <a href="{{ route('dokani.stock-transfers.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Stock Transfer List
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

        </ul>
    </li>

    <!-- Stock Adjustment -->
    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-bar-chart"></i>
            <span class="menu-text"> Stock Adjustment </span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">

            @if (hasPermission('dokani.stock-adjustments.store', $slugs))
                <li>
                    <a href="{{ route('dokani.stock-adjustments.create') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Add Stock Adjustment
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.stock-adjustments.index', $slugs))
                <li>
                    <a href="{{ route('dokani.stock-adjustments.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Stock Adjustment List
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

        </ul>
    </li>
@endif

<li>
    <a href="#" class="dropdown-toggle">
        <i class="menu-icon fa fa-gear"></i>
        <span class="menu-text"> Point Configure </span>
        <b class="arrow fa fa-angle-down"></b>
    </a>
    <b class="arrow"></b>
    <ul class="submenu">

        @if (hasPermission('dokani.points.index', $slugs))
            <li>
                <a href="{{ route('dokani.points.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Point List
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.points.create', $slugs))
            <li>
                <a href="{{ route('dokani.points.create') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Add Point
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.refers.index', $slugs))
             <li>
                 <a href="{{ route('dokani.refers.index') }}">
                     <i class="menu-icon fa fa-caret-right"></i>
                     Refer Configure
                 </a>
                 <b class="arrow"></b>
             </li>
        @endif

    </ul>
</li>



<li id="accounts">
    <a href="#" class="dropdown-toggle">
        <i class="menu-icon fa fa-money"></i>
        <span class="menu-text"> G Accounts </span>
        <b class="arrow fa fa-angle-down"></b>
    </a>
    <b class="arrow"></b>
    <ul class="submenu">

        @if (hasPermission('dokani.ac.accounts.index', $slugs))
            <li class="hasQuery">
                <a href="{{ route('dokani.ac.accounts.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Account
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.voucher-payments.store', $slugs))
            <li class="hasQuery">
                <a href="{{ route('dokani.voucher-payments.create') }}?type=income">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Add Income
                </a>
                <b class="arrow"></b>
            </li>
        @endif


        @if (hasPermission('dokani.voucher-payments.index', $slugs))
            <li class="hasQuery">
                <a href="{{ route('dokani.voucher-payments.index') }}?type=income">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Income List
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.voucher-payments.store', $slugs))
            <li class="hasQuery">
                <a href="{{ route('dokani.voucher-payments.create') }}?type=expense">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Add Expense
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.voucher-payments.index', $slugs))
            <li class="hasQuery">
                <a href="{{ route('dokani.voucher-payments.index') }}?type=expense">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Expense List
                </a>
                <b class="arrow"></b>
            </li>
        @endif

            @if (hasPermission('dokani.ac.fund-transfers.index', $slugs))
                <li>
                    <a href="{{ route('dokani.ac.fund-transfers.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Fund Transfer
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

        @if (hasPermission('dokani.ac.parties.index', $slugs))
            <li>
                <a href="{{ route('dokani.ac.parties.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    G Parties
                </a>
                <b class="arrow"></b>
            </li>
        @endif


        @if (hasPermission('dokani.ac.charts.index', $slugs))
            <li>
                <a href="{{ route('dokani.ac.charts.index') }}">
                    <i class="menu-icon fa fa-users"></i>
                    <span class="menu-text"> Chart of Account </span>

                </a>
                <b class="arrow"></b>
            </li>
        @endif

            <li>
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Investors
                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <b class="arrow"></b>

                <b class="arrow"></b>
                <ul class="submenu">
                    @if (hasPermission('dokani.ac.investors.create', $slugs))
                        <li id="add_investor">
                            <a href="{{ route('dokani.ac.investors.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Create Investor
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif


                    @if (hasPermission('dokani.ac.investors.index', $slugs))
                        <li id="investor_list">
                            <a href="{{ route('dokani.ac.investors.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Investor List
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif


{{--                        @if (hasPermission('dokani.ac.investor.balance.add', $slugs))--}}
{{--                            <li id="investor_list">--}}
{{--                                <a href="{{ route('dokani.ac.investor.balance.add') }}">--}}
{{--                                    <i class="menu-icon fa fa-caret-right"></i>--}}
{{--                                    Add Invest Amount--}}
{{--                                </a>--}}
{{--                                <b class="arrow"></b>--}}
{{--                            </li>--}}
{{--                        @endif--}}

                        @if (hasPermission('dokani.ac.investor.withdraw.list', $slugs))
                            <li id="investor_list">
                                <a href="{{ route('dokani.ac.investor.withdraw.list') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Withdraw List
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                </ul>
            </li>


            @if (hasPermission('dokani.ac.disbursement', $slugs))
                <li>
                    <a href="{{ route('dokani.ac.disbursement') }}">
                        <i class="menu-icon fa fa-users"></i>
                        <span class="menu-text"> Profit Disbursement </span>

                    </a>
                    <b class="arrow"></b>
                </li>
            @endif


    </ul>
</li>

<li>
    <a href="#" class="dropdown-toggle">
        <i class="menu-icon fa fa-book"></i>
        <span class="menu-text"> Report </span>
        <b class="arrow fa fa-angle-down"></b>
    </a>
    <b class="arrow"></b>
    <ul class="submenu">

        @if (hasPermission('dokani.reports.inventory', $slugs))
            <li id="report_inventory">
                <a href="{{ route('dokani.reports.inventory','product_type=1') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Inventory Report
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.reports.product_ledger', $slugs))
            <li id="report_inventory">
                <a href="{{ route('dokani.reports.product_ledger') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Product Ledger
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.reports.inventory', $slugs))
            <li id="alert_report_inventory">
                <a href="{{ route('dokani.reports.alert-inventory') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Alert Inventory Report
                </a>
                <b class="arrow"></b>
            </li>
        @endif


        @if (hasPermission('dokani.reports.sales', $slugs))
            <li id="report_sale">
                <a href="{{ route('dokani.reports.sales') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Sales Report
                </a>
                <b class="arrow"></b>
            </li>
        @endif

            @if (hasPermission('dokani.reports.product-wise-sales', $slugs))
                <li id="report_sale">
                    <a href="{{ route('dokani.reports.product-wise-sales') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Product Wise Sales Report
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission('dokani.reports.vats', $slugs))
                <li id="report_sale">
                    <a href="{{ route('dokani.reports.vats') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        VAT Report
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

        @if (hasPermission('dokani.reports.purchase', $slugs))
            <li id="purchase_report">
                <a href="{{ route('dokani.reports.purchase') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Purchase Report
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.reports.cash-flow', $slugs))
            <li id="cashflow_report">
                <a href="{{ route('dokani.reports.cash-flow') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Cash flow Report
                </a>
                <b class="arrow"></b>
            </li>
        @endif


        @if (hasPermission('dokani.reports.receivable-due', $slugs))
            <li id="receive_due">
                <a href="{{ route('dokani.reports.receivable-due') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Receivable Due
                </a>
                <b class="arrow"></b>
            </li>
        @endif


        @if (hasPermission('dokani.reports.payable-due', $slugs))
            <li>
                <a href="{{ route('dokani.reports.payable-due') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Payable Due
                </a>
                <b class="arrow"></b>
            </li>
        @endif


        @if (hasPermission('dokani.reports.customer-ledger', $slugs))
            <li>
                <a href="{{ route('dokani.reports.customer-ledger') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Customer Ledger
                </a>
                <b class="arrow"></b>
            </li>
        @endif


        @if (hasPermission('dokani.reports.supplier-ledger', $slugs))
            <li>
                <a href="{{ route('dokani.reports.supplier-ledger') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Supplier Ledger
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.reports.refer-wise-customer', $slugs))
            <li>
                <a href="{{ route('dokani.reports.refer-wise-customer') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Refer Wise Customer Report
                </a>
                <b class="arrow"></b>
            </li>
        @endif


        @if (hasPermission('dokani.reports.chart-of-account', $slugs))
            <li>
                <a href="{{ route('dokani.reports.chart-of-account') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Chart of Account Report
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.reports.income-expense', $slugs))
            <li>
                <a href="{{ route('dokani.reports.income-expense') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Income Expense Report
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.reports.profit', $slugs))
            <li>
                <a href="{{ route('dokani.reports.profit') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Profit Report
                </a>
                <b class="arrow"></b>
            </li>
        @endif
        @if (hasPermission('dokani.reports.product-wise-profit', $slugs))
            <li>
                <a href="{{ route('dokani.reports.product-wise-profit') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Product wise Profit
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.reports.investor-ledger', $slugs))
            <li>
                <a href="{{ route('dokani.reports.investor-ledger') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Investor Ledger Report
                </a>
                <b class="arrow"></b>
            </li>
        @endif


    </ul>
</li>



<li>
    <a href="#" class="dropdown-toggle">
        <i class="menu-icon fa fa-users"></i>
        <span class="menu-text"> User Management </span>
        <b class="arrow fa fa-angle-down"></b>
    </a>
    <b class="arrow"></b>
    <ul class="submenu">
        @if (hasPermission('dokani.users.index', $slugs))
            <li>
                <a href="{{ route('dokani.users.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    User List
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.users.store', $slugs))
            <li id="user_add">
                <a href="{{ route('dokani.users.create') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Add User
                </a>
                <b class="arrow"></b>
            </li>
        @endif

    </ul>
</li>

<li id="sms">
    <a href="#" class="dropdown-toggle">
        <i class="menu-icon fa fa-send"></i>
        <span class="menu-text"> SMS Management </span>
        <b class="arrow fa fa-angle-down"></b>
    </a>
    <b class="arrow"></b>
    <ul class="submenu">

        <li id="sms_send">
            @if (hasPermission('dokani.manage.sms', $slugs))
                <a href="{{ route('dokani.manage.sms') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Send SMS
                </a>
            @endif
            <b class="arrow"></b>
        </li>
    </ul>
</li>

@if (hasAnyPermission(['dokani.employees.create','dokani.employees.index', 'dokani.attendance.index' ,'dokani.holidays.index', 'dokani.holidays.create', 'dokani.advance.index', 'dokani.advance.create', 'dokani.salaries.create','dokani.salaries.index','dokani.payrolls.edit',], $slugs))
<li id="hrm_payroll">
    <a href="#" class="dropdown-toggle">
        <i class="menu-icon fa fa-users"></i>
        <span class="menu-text"> HRM & Payroll </span>
        <b class="arrow fa fa-angle-down"></b>
    </a>
    <b class="arrow"></b>
    <ul class="submenu">
        <li id="employees">
            <a href="" class="dropdown-toggle">
                <i class="menu-icon fa fa-caret-right"></i>
                Employees
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>

            <b class="arrow"></b>
            <ul class="submenu">
                @if (hasPermission('dokani.employees.create', $slugs))
                    <li id="add_employee">
                        <a href="{{ route('dokani.employees.create') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Add Employee
                        </a>
                        <b class="arrow"></b>
                    </li>
                @endif

                @if (hasPermission('dokani.employees.index', $slugs))
                    <li id="employee_list">
                        <a href="{{ route('dokani.employees.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Employees List
                        </a>
                        <b class="arrow"></b>
                    </li>
                @endif

            </ul>
        </li>
        @if (hasPermission('dokani.attendance.index', $slugs))
            <li>
                <a href="{{ route('dokani.attendance.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Attendance Management
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        <li>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-caret-right"></i>
                Holidays
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @if (hasPermission('dokani.holidays.create', $slugs))
                    <li id="add_holiday">
                        <a href="{{ route('dokani.holidays.create') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Add Holiday
                        </a>
                        <b class="arrow"></b>
                    </li>
                @endif

                @if (hasPermission('dokani.holidays.index', $slugs))
                    <li id="holiday_list">
                        <a href="{{ route('dokani.holidays.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Holidays List
                        </a>
                        <b class="arrow"></b>
                    </li>
                @endif

            </ul>
        </li>
        <li id="advance">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-caret-right"></i>
                Advance
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @if (hasPermission('dokani.advances.create', $slugs))
                    <li>
                        <a href="{{ route('dokani.advances.create') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Add Advance
                        </a>
                        <b class="arrow"></b>
                    </li>
                @endif

                @if (hasPermission('dokani.advances.index', $slugs))
                    <li>
                        <a href="{{ route('dokani.advances.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Advance List
                        </a>
                        <b class="arrow"></b>
                    </li>
                @endif
            </ul>
        </li>
        @if (hasPermission('dokani.salaries.create', $slugs))
            <li>
                <a href="{{ route('dokani.salaries.create') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Salary Generate
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('dokani.salaries.index', $slugs))
            <li>
                <a href="{{ route('dokani.salaries.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Salary List
                </a>
                <b class="arrow"></b>
            </li>
        @endif
        @if (hasPermission('dokani.payrolls.edit', $slugs))
            <li>
                <a href="{{ route('dokani.payrolls.edit',1) }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Payroll Setting
                </a>
                <b class="arrow"></b>
            </li>
        @endif

    </ul>
</li>
@endif

<li id="business_setting">
    @if (hasPermission('dokani.business-profile.index', $slugs))
        <a href="{{ route('dokani.business-profile.index') }}">
            <i class="fa fa-gears menu-icon" aria-hidden="true"></i>
            <span class="menu-text"> Business Setting </span>
        </a>
    @endif
    <b class="arrow"></b>
</li>
<li>
    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
            document.getElementById('logout-sform').submit();">
        <i class="menu-icon fa fa-sign-out"></i>
        {{ __('Signout') }}
    </a>
    <form id="logout-sform" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>
