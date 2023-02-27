<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <!-- Title -->
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>

    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />



    <!-- fav icon -->
    <link rel="icon" href="{{ $fav_icon ?? asset('icon.png') }}" type="image/png">



    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />





    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-timepicker.min.css') }}" />


    <!-- page specific plugin styles -->
    @yield('css')

    @stack('style')






    <!-- google fonts -->
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.googleapis.com.css') }}?v=0.1" />



    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/ace.min.css') }}" class="ace-main-stylesheet"
        id="main-ace-style" />


    <link rel="stylesheet" href="{{ asset('assets/css/ace-skins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/ace-rtl.min.css') }}" />



    <!-- ace settings handler -->
    <script src="{{ asset('assets/js/ace-extra.min.js') }}"></script>



    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" />




    <!-- toster -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/toastr.min.css') }}">



    <!-- sweatalert2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.min.css') }}">


    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-110599322-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-110599322-1');
    </script>

    <!-- custom and global style -->
    <style type="text/css">

        @import url('https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200;300;400;500;600;700&display=swap');
        .fa-business-profile:before {
            content: "\f1cc";
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .table>tbody>tr>td {
            vertical-align: middle;
        }

        .logo {
            height: 25px !important;
            width: 269px !important;
        }

        .no-skin .sidebar-shortcuts {
            background-color: #dfe2cd;
            padding-top: 10px;
            padding-bottom: 10px;
            font-size: 14px;
        }

        .ace-nav>li {
            border-left: 1px solid #bfc1ae !important;
        }

        .bg-dark {
            background-color: #ededed !important;
        }

        select.required:invalid {
            height: 0px !important;
            opacity: 0 !important;
            position: absolute !important;
            display: flex !important;
        }

        .btn {
            border-radius: 5px;
        }




        .select2-container .select2-selection--single {
            height: 33px !important;
        }


        .select2-container--default .select2-selection--single {
            border-radius: 0 !important;
        }


        .chosen-container, .chosen-container+.help-inline, [class*=chosen-container] {
            width: 100% !important;
        }

        /*.chosen-container>.chosen-single, [class*=chosen-container]>.chosen-single {*/
        /*    background: #ffffff !important;*/
        /*}*/




            /* .input-group-addon {
                border: none !important;
                color: #161616 !important;
                background-color: #c3c3c3 !important;
                font-weight: bold;
            }

            .input-group {

                background: #c3c3c3 !important;
                padding: 4px !important;

            } */


        thead tr{
            background: #807e7e !important;
            color: white !important;
        }

        /*tfoot{*/
        /*    background: #c1c1c1 !important;*/
        /*    color: black !important;*/
        /*}*/


        /* Widget Hader Color */
        .widget-header {
            background: #eaf4fa !important;
        }

        .ui-helper-hidden-accessible>div {
            display: none !important;
        }

        /* .widget-main {
            min-height: 500px;
        } */

        .navbar-fixed-top+.main-container {
            padding-top: 46px
        }



         .rotate {
             transition: width 2s, height 2s, transform 1s;
         }

        .rotate:hover {

            transform: rotate(360deg);
        }


    </style>









    @if (strlen(optional(auth()->user())->name) >= 10)
        <style>
            .user-info {
                width: 120px;
                max-width: 250px;
            }

        </style>
    @endif




    <!-- bootstrap4 support css -->
    <link rel="stylesheet" href="{{ asset('assets/custom_css/color-size.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/bootstrap4.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/style.css') }}" />
</head>
