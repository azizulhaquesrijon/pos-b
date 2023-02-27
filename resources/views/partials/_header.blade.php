
@php
    $bg_color= "#8194b1";
    $text_color= "white";
    $total_count = 0;
    $order_sale = \Module\Dokani\Models\Order::dokani()->whereNull('sale_id')->count();
    $due_purchase = \Module\Dokani\Models\Purchase::dokani()->where('due_amount','>', 0)->count();
    if ($order_sale > 0){
        $total_count ++;
    }
    if ($due_purchase > 0){
        $total_count ++;
    }

@endphp
<style>
    .navbar {
        background: #ae4727;
    }

    .topbar-text-color {
        color: {{ $text_color }};
    }
    .navbar .navbar-brand {
        color: #FFF;
        font-size: 24px;
        text-shadow: none;
        padding-top: 0px;
        padding-bottom: 0px;
        height: auto;
    }
    .nav-top-pos{
        transition: 0.4s
    }
    .nav-top-pos:hover{
        background-color: rgba(190, 190, 190, 0.267) !important;
        transition: 0.4s
    }
</style>
<div id="navbar" class="navbar navbar-default ace-save-state navbar-fixed-top">
    <div class="navbar-container ace-save-state" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left pt-1">
            <a href="{{ url('home') }}" class="navbar-brand" >
                <small class="text-primary font-weight-bold" style="font-weight: 600">

                    {{-- @if(file_exists(auth()->user()->image))
                        <img style="height: 50px !important;border-radius: 10px;width:110px" class="logos" src="{{ asset(auth()->user()->image) }}" alt="" >

                    @endif --}}
                        <span class="white">
                            {{-- <i class="fa fa-flag"></i> --}}
                            {{ optional(auth()->user()->businessProfileByUser)->shop_name == null ? optional(auth()->user())->name : optional(auth()->user()->businessProfileByUser)->shop_name }}
                        </span>
                </small>
            </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                @if(!request()->routeIs('dokani.sales.create'))
                <li class="nav-top-pos" title="POS Sale">
                    <a href="{{ route('dokani.sales.create') }}" target="_blank" style="color: rgb(255, 255, 255);font-size: 18px;background: transparent">
                        <i class="fa fa-shopping-bag"></i>
                        <span>Sale</span>
                    </a>
                </li>
                @endif

                @if(!request()->routeIs('dokani.purchases.create'))
                 <li class="nav-top-pos" title="POS Purcahse">
                    <a href="{{ route('dokani.purchases.create') }}" target="_blank" style="color: rgb(255, 255, 255);font-size: 18px;background: transparent">
                        <i class="fa fa-cart-plus"></i>
                        <span>Purchase</span>
                    </a>
                </li>
                @endif

                @if(request()->routeIs('dokani.sales.create'))
                <li class="light-10 dropdown-modal" title="Keyboard Shortcut">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="fa fa-2x fa-eye" style="margin-top: 10px;"></i>

                    </a>

                    <ul class="dropdown-menu-right dropdown-navbar navbar-default dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="ace-icon fa fa-bell-o"></i>
                            POS Sale Keyboard Shortcut
                        </li>

                        <li class="dropdown-content">
                            <ul class="dropdown-navbar navbar-default">
                                <li>Header Modal Open           : <strong>ctrl+m</strong></li>
                                <li>Footer Modal Open           : <strong>ctrl+b</strong></li>
                                <li>Modal Close                 : <strong>ctrl+x</strong></li>
                                <li>Delivery Charge Field Focus : <strong>ctrl+q</strong></li>
                                <li>Note Field Focus            : <strong>ctrl+y</strong></li>
                                <li>Customer Add                : <strong>ctrl+i</strong></li>
                            </ul>
                        </li>

                    </ul>
                </li>
                @endif

                @if(request()->routeIs('dokani.purchases.create'))

                <li class="light-10 dropdown-modal" title="Keyboard Shortcut">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="fa fa-2x fa-eye" style="margin-top: 10px;"></i>

                    </a>

                    <ul class="dropdown-menu-right dropdown-navbar navbar-default dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="ace-icon fa fa-bell-o"></i>
                            POS Purchase Keyboard Shortcut
                        </li>

                        <li class="dropdown-content">
                            <ul class="dropdown-navbar navbar-default">
                                <li>Header Modal Open           : <strong>ctrl+m</strong></li>
                                <li>Footer Modal Open           : <strong>ctrl+b</strong></li>
                                <li>Modal Close                 : <strong>ctrl+x</strong></li>
                                <li>Discount Field Focus        : <strong>ctrl+y</strong></li>
                            </ul>
                        </li>

                    </ul>
                </li>
                @endif






                <li class="light-10 dropdown-modal" title="Recommend Notifications">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="fa fa-2x fa-bell" style="margin-top: 10px;"></i>
                        <sup style="color: white;font-size: 12px;margin-left: -16px;background-color: red;padding: 2px;border-radius: 50%;">
                            <b>{{ hasPermission('dokani.sales.create', $slugs) ? $total_count : '0' }}</b>
                        </sup>

                    </a>

                    <ul class="dropdown-menu-right dropdown-navbar navbar-default dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="ace-icon fa fa-bell-o"></i>
                            {{ hasPermission('dokani.sales.create', $slugs) ? $total_count : '0' }} Total Notifications
                        </li>

                        <li class="dropdown-content">
                            <ul class="dropdown-menu dropdown-navbar navbar-default">

                                @if($order_sale > 0 && hasPermission('dokani.sales.create', $slugs))
                                    <li>
                                        <a href="{{ route('dokani.orders.index') }}">
                                            <div class="clearfix">
                                            <span class="pull-left text-dark">
                                                Pending Sale
                                            </span>
                                                <span class="pull-right">
                                                <span class="badge badge-danger" style="border-radius: 50%;">{{ $order_sale }}</span>
                                            </span>
                                            </div>
                                        </a>
                                    </li>
                                @endif

                                    @if($due_purchase > 0 && hasPermission('dokani.purchases.index', $slugs))
                                        <li>
                                            <a href="{{ route('dokani.purchases.index') }}">
                                                <div class="clearfix">
                                            <span class="pull-left text-dark">
                                                Due Purchase
                                            </span>
                                                    <span class="pull-right">
                                                <span class="badge badge-danger" style="border-radius: 50%;">{{ $due_purchase }}</span>
                                            </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif

                            </ul>
                        </li>

                    </ul>
                </li>
                <!--  Leave Application Notification End  -->





                <li class="light-10 dropdown-modal"

                    @if(strlen(optional(auth()->user())->name) >= 10)
                        style="width: 180x"
                    @endif
                >
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle dark">

                                <img class="nav-user-photo" height="35px" src="{{ asset('assets/images/avatars/avatar2.png') }}" alt="User Photo" />

                        <span class="user-info" style="color:white;">
                            <small >Welcome,</small>
                            {{ optional(auth()->user())->name }}
                        </span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>


                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

                        <li>
                            <a href="{{ url('dokani/change-pin') }}">
                                <i class="ace-icon fa fa-user"></i>
                                Change Password
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</div>
