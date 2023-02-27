
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>@yield('title') - Smart ERP</title>
        {{--    <title>@yield('title') - {{ config('app.name') }}</title>--}}

        <meta name="description" content="overview &amp; stats" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="icon" href="/icon.png" type="image/png">
        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

        <!-- page specific plugin styles -->
        @yield('css')
        @stack('style')

        <!-- text fonts -->
        <link rel="stylesheet" href="{{ asset('assets/css/fonts.googleapis.com.css') }}?v=0.1" />
        <!-- ace styles -->
        <link rel="stylesheet" href="{{ asset('assets/css/ace.min.css') }}" class="ace-main-stylesheet" id="main-ace-style" />
        <!--[if lte IE 9]>
        <link rel="stylesheet" href="{{ asset('assets/css/ace-part2.min.css') }}" class="ace-main-stylesheet" />
        <![endif]-->
        <link rel="stylesheet" href="{{ asset('assets/css/ace-skins.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/ace-rtl.min.css') }}" />
        <!--[if lte IE 9]>
        <link rel="stylesheet" href="{{ asset('assets/css/ace-ie.min.css') }}" />
        <![endif]-->
        <!-- inline styles related to this page -->

        <!-- ace settings handler -->
        <script src="{{ asset('assets/js/ace-extra.min.js') }}"></script>
        <!--[if lte IE 8]>
        <script src="{{ asset('assets/js/html5shiv.min.js') }}"></script>
        <script src="{{ asset('assets/js/respond.min.js') }}"></script>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css">
        <style type="text/css">
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            .logo{
                height: 25px !important;
                width: 269px !important;
            }
            .navbar{
                /*background-color: #dfe2cd !important;*/
            }
            .no-skin .sidebar-shortcuts {
                background-color: #dfe2cd;
                padding-top: 10px;
                padding-bottom: 10px;
                font-size: 14px;
            }
            .ace-nav>li{
                border-left: 1px solid #bfc1ae !important;
            }
            .bg-dark{
                background-color: #ededed !important;
            }

        </style>

        <link rel="stylesheet" href="{{ asset('assets/custom_css/color-size.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/custom_css/bootstrap4.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/custom_css/bootstrap4.css') }}" />
    </head>

    <body class="no-skin" style="font-family: monospace;">

        @if (!request()->is('hrm/payroll/master-salary/*') && !request()->is('hrm/payroll/bank-salary/*')
        && !request()->is('hrm/payroll/cash-salary/*') && !request()->is('hrm/payroll/master-salary-without-payslip/*')
        && !request()->is('hrm/payroll/master-salary-with-payslip/*') && !request()->is('hrm/bonus/fixed/bonus/details/*')
        && request()->segment(1) != 'ems'
        )
            @include('partials._header')
        @elseif(request()->segment(1) == 'em')
            @include('partials._em._header')
        @endif


<div class="main-container ace-save-state" id="main-container">
    <script type="text/javascript">
        try{ace.settings.loadState('main-container')}catch(e){}
    </script>

    <input type="hidden" class="sidebar-type" value="{{ request()->segment(1) }}">

    @if (!request()->is('hrm/payroll/master-salary/*') && !request()->is('hrm/payroll/bank-salary/*') && !request()->is('hrm/payroll/cash-salary/*') && !request()->is('hrm/payroll/master-salary-without-payslip/*')
&& !request()->is('hrm/payroll/master-salary-with-payslip/*') && !request()->is('hrm/bonus/fixed/bonus/details/*') && request()->segment(1) !== 'em')
        @include('partials._sidebar')
    @elseif(request()->segment(1) == 'em')
        @include('partials._em._sidebar')
    @endif

    @if(request()->is('/') || request()->is('home'))
        <div class="main-content">
    @else 
        <div class="main-content">
        <div class="main-content-inner" style="background: linear-gradient( rgb(255 255 255 / 38%), rgba(255, 255, 255, 0.72)), no-repeat url('assets/images/dashboard/bg-image.jpeg');background-size: cover;">

            <div class="page-content" style="background: transparent">

            @if (!request()->is('hrm/payroll/master-salary/*') && !request()->is('hrm/payroll/bank-salary/*') && !request()->is('hrm/payroll/cash-salary/*') && !request()->is('hrm/payroll/master-salary-without-payslip/*')
&& !request()->is('hrm/payroll/master-salary-with-payslip/*') && !request()->is('hrm/bonus/fixed/bonus/details/*'))

            @endif

            <!-- /.ace-settings-container -->

            @yield('content', 'Default Content')

            <!-- /.row -->
            </div><!-- /.page-content -->


        </div>
    </div><!-- /.main-content -->

    @if (!request()->is('hrm/payroll/master-salary/*') && !request()->is('hrm/payroll/bank-salary/*') && !request()->is('hrm/payroll/cash-salary/*') && !request()->is('hrm/payroll/master-salary-without-payslip/*')
&& !request()->is('hrm/payroll/master-salary-with-payslip/*'))
        @include('partials._footer')
    @endif
</div><!-- /.main-container -->


<script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='{{ asset('assets/js/jquery.mobile.custom.min.js') }}'>"+"<"+"/script>");
</script>
<!-- ace scripts -->
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
<script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace.min.js') }}"></script>
    
<script type="text/javascript">
    function withDefault(value, default_value) {
        return value ? value : default_value
    }
</script>

@yield('js')

<script type="text/javascript">
    $('[data-rel=popover]').popover({html:true, container:'body'});
</script>

<script type="text/javascript">
    $('.success').fadeIn('slow').delay(10000).fadeOut('slow');
</script>
<script type="text/javascript">

    function showAlertMessage(message, time = 1000, type = 'error')
    {
        swal.fire({
            title: type.toUpperCase(),
            html: "<b>" + message + "</b>",
            type: type,
            timer: time
        })
    }


    @if(session()->get('message'))
    swal.fire({
        title: "Success",
        html: "<b>{{ session()->get('message') }}</b>",
        type: "success",
        timer: 1000
    });

    @elseif(session()->get('arpMassage'))
    swal.fire({
        // title: "Success",
        html: "<h4><b>{!! session()->get('arpMassage') !!}</b></h4><br><b>Work Order Generated Successfully.</b>",
        type: "success",
        timer: 9000
    });

    @elseif(session()->get('message-number'))
    swal.fire({
        title: "Success",
        html: "<b>{!! session()->get('message-number') !!}</b>",
        // type: "success",
        timer: 30000
    });

    @elseif(session()->get('error'))
    swal.fire({
        title: "Error",
        html: "<b>{{ session()->get('error') }}</b>",
        type: "error",
        timer: 1000
    });

    @elseif(session()->get('payment-success'))
    swal.fire({
        title: "Payment Success",
        html: "<b>{{ session()->pull('payment-success') }}</b>",
        type: "success",
        timer: 10000
    });

    @elseif(session()->get('payment-fail'))
    swal.fire({
        title: "Payment Failed",
        html: "<b>{{ session()->pull('payment-fail') }}</b>",
        type: "Error",
        timer: 10000
    });
    @endif


    function onlyNumber(evnt) {
        let keyCode = evnt.charCode
        let str = evnt.target.value
        let n = str.includes(".")

        if (keyCode == 13) {
            evnt.preventDefault();
        }

        if (keyCode == 46 && n) {
            return false
        }

        if (str.length > 12) {
            showAlertMessage('Number length out of range', 3000)
            return false
        }
        return (keyCode >= 48 && keyCode <= 57) || keyCode == 13 || keyCode == 46
    }


    $('.only-number').keypress(function() {
        return onlyNumber(event)
    })

    @if(request()->segment(1) != 'em' && auth()->id() != 1)
        let permitted_menu_count = $('.main-sidebar').find('li').length

        if(permitted_menu_count <= 1) {
            // window.location.href = '/em';
        }
    @endif
</script>


@if (request()->segment(1) != 'em')
    
@include('partials._payment_notification')

@endif

</body>
</html>
