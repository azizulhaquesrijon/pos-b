<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Purchase - Smart Dokani</title>

    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/fonts.googleapis.com.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/ace.min.css') }}" class="ace-main-stylesheet"
        id="main-ace-style" />
    <link rel="stylesheet" href="{{ asset('assets/css/ace-skins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/ace-rtl.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <script src="{{ asset('assets/js/ace-extra.min.js') }} "></script>

</head>

<body class="no-skin">
    <div id="navbar" class="navbar navbar-default ace-save-state">
        <div class="navbar-container ace-save-state" id="navbar-container">
            <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                <span class="sr-only">Toggle sidebar</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-header pull-left">
                <a href="{{ route('home') }}" class="navbar-brand">
                    <small>
                        <span class="text-success">{{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->shop_name : optional(auth()->user()->businessProfileByUser)->shop_name }}</span>
                    </small>
                </a>
            </div>
            <div class="navbar-buttons navbar-header pull-right" role="navigation">
                <ul class="nav ace-nav">
                    <li class="dropdown">
                        <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications">
                            <i class="fa fa-question-circle fa-lg"></i>
                            <sup class="badge badge-danger not-badge"></sup>
                        </a>
                        <ul class="app-notification dropdown-menu dropdown-menu-right">
                            <li class="app-notification__title titleCount"></li>
                            <div class="app-notification__content temp">
                            </div>
                            <li class="app-notification__footer">
                                <a href="https://www.youtube.com/watch?v=nGiUIKKcsvc" target="_blank">Software
                                    Tutorial
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="app-nav__item" style="background-color:none" href="#" data-toggle="dropdown"
                            aria-label="Open Profile Menu" aria-expanded="false">
                            <span class="pr-2">Admin</span>
                            <i class="fa fa-user fa-lg"></i>
                        </a>
                        <ul id="logout-nav" class="dropdown-menu settings-menu dropdown-menu-right"
                            x-placement="bottom-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('dokani.business-profile.index') }}">
                                    <i class="fa fa-business-profile menu-icon"></i>
                                    {{ __('Business Setting') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form2').submit();">
                                    <i class="menu-icon fa fa-sign-out"></i>
                                    {{ __('Signout') }}
                                </a>
                                <form id="logout-form2" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>

                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /.navbar-container -->
    </div>

    <div class="main-container ace-save-state no-print" id="main-container">
        <div class="main-content">
            <div class="main-content-inner">
                <div class="page-content">
                    <div class="row">
                        <div class="col-xs-12">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                            @elseif ($message = Session::get('warning'))
                                <div class="alert alert-warning alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                            @endif
                            <!-- PAGE CONTENT BEGINS -->
                            <div class="space-24"></div>
                            <div class="space-24"></div>
                            <center>
                                <div class="row">
                                    <div class="col-xs-3"></div>
                                    <div class="col-xs-4 col-sm-3 pricing-box">
                                        <div class="widget-box widget-color-orange">
                                            <div class="widget-header">
                                                <!-- <h5 class="widget-title bigger lighter">AamarPay</h5> -->
                                                <img src="{{ asset('assets/images/aamarpay.png') }}" alt="AamarPay"
                                                    height="70px" width="300px">
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <hr />
                                                    <div class="price">
                                                        ৳{{ $packagePrice }}
                                                    </div>
                                                </div>
                                                <div>{!! aamarpay_post_button(
                                                        [
                                                            'cus_name' => ($profile['name'] == null) ? 'Dokani'.$profile['mobile'] : $profile['name'],
                                                            'cus_email' => $profile['email'] ?? 'admin@smartsoftware.com',
                                                            'cus_phone' => $profile['mobile'] ?? 01,
                                                            'desc' => 'Package',
                                                        ],
                                                        $packagePrice,
                                                        '<i class="fa fa-money"> Pay Now</i>',
                                                        'btn btn-block btn-success'
                                                    ) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-xs-4 col-sm-3 pricing-box">
                                        <div class="widget-box">
                                            <div class="widget-header" style="background-color: none;">
                                                <img src="{{ asset('images/nagad.svg') }}" alt="Nagad" height="70px" width="300px">
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <hr />
                                                    <div class="price">
                                                        ৳50
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="#" class="btn btn-block btn-success">
                                                        <i class="ace-icon fa fa-money bigger-110"></i>
                                                        <span>Payment</span>
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-xs-1"></div>
                                </div>
                            </center>

                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.page-content -->

            </div>
        </div>
        <div class="footer">
            <div class="footer-inner">
                <div class="footer-content">Developed By
                    <span class="blue bolder">
                        <a href="https://www.smartsoftware.com.bd/" target="_blank"> Smart Software Ltd </a>
                    </span>
                    &copy; 2020
                </div>
            </div>
        </div>
        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
        </a>
    </div>
    <script src="{{ asset('components/jquery/jquery-3.5.1.min.js') }}"></script>


    <script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
    <script type="text/javascript">
        if ('ontouchstart' in document.documentElement) document.write(
            "<script src='{{ asset('assets/js/jquery.mobile.custom.min.js') }}'>" + "<" + "/script>");
    </script>

    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.custom.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.sparkline.index.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.flot.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.flot.pie.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.flot.resize.min.js') }}"></script>
    <!-- ace scripts -->
    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace.min.js') }}"></script>
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jQuery.print.js') }}"></script>

</body>

</html>
