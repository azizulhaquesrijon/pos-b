<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Subscription Expired - Smart Dokani</title>

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
    <div class="main-container ace-save-state no-print" id="main-container">
        <div class="main-content">
            <div class="main-content-inner">

                <!-- Add custom design start -->
                <div class="page-content">
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->

                            <div class="error-container">
                                <div class="well">
                                    {{-- <h1 class="grey lighter smaller">
                                        <span class="blue bigger-125">
                                            <i class="ace-icon fa fa-sitemap"></i>
                                            404
                                        </span>
                                        Page Not Found
                                    </h1> --}}

                                    <hr />
                                    <h3 class="lighter smaller"><span class="text-danger">Your Software Subscription
                                            already Expired!</span> </h3>

                                    <div>
                                        <div class="space"></div>
                                        <h4 class="smaller">Please Pay your Software Subscription Fee.</h4>

                                        <ul class="list-unstyled spaced inline bigger-110 margin-15">
                                            <li>
                                                <i class="ace-icon fa fa-hand-o-right blue"></i>
                                                If you need help call us 01844047005
                                            </li>
                                        </ul>
                                    </div>

                                    <hr />
                                    <div class="space"></div>

                                    <div class="center">
                                        <a href="{{ route('subscription.index') }}" class="btn btn-success">
                                            <i class="fa fa-money" aria-hidden="true"> Make Payment</i>
                                        </a>
                                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                            <i class="ace-icon fa fa-tachometer"></i>
                                            Dashboard
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- PAGE CONTENT ENDS -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
                <!-- End custom design -->

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
        </div>
    </div>
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

    <script type="text/javascript">
        jQuery(function($) {
            $('#id-disable-check').on('click', function() {
                var inp = $('#form-input-readonly').get(0);
                if (inp.hasAttribute('disabled')) {
                    inp.setAttribute('readonly', 'true');
                    inp.removeAttribute('disabled');
                    inp.value = "This text field is readonly!";
                } else {
                    inp.setAttribute('disabled', 'disabled');
                    inp.removeAttribute('readonly');
                    inp.value = "This text field is disabled!";
                }
            });


            if (!ace.vars['touch']) {
                $('.chosen-select').chosen({
                    allow_single_deselect: true
                });
                //resize the chosen on window resize

                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function() {
                        $('.chosen-select').each(function() {
                            var $this = $(this);
                            // $this.next().css({'width': $this.parent().width()});
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if (event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        // $this.next().css({'width': $this.parent().width()});
                    })
                });


                $('#chosen-multiple-style .btn').on('click', function(e) {
                    var target = $(this).find('input[type=radio]');
                    var which = parseInt(target.val());
                    if (which == 2) $('#form-field-select-4').addClass('tag-input-style');
                    else $('#form-field-select-4').removeClass('tag-input-style');
                });
            }
        });
    </script>
</body>

</html>
