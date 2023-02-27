@extends('layouts.master')

@section('title', 'Payroll Setting')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Payroll Setting
@stop



@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <div class="row">
                            <div class="col-xs-12">

                                <!-- PAGE CONTENT BEGINS -->
                                <form class="form-horizontal" action="{{ route('dokani.payrolls.update', $payroll->id ?? 1) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Start Time</label>
                                        <div class="col-xs-12 col-sm-5">
                                        <span class="block input-icon input-icon-right">
                                            <input name="start_time" type="text" id="timepicker1" placeholder="Start Time" value="{{$payroll->start_time ?? null}}" class="form-control" />
                                        </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">End Time</label>
                                        <div class="col-xs-12 col-sm-5">
                                        <span class="block input-icon input-icon-right">
                                            <input type="text" id="timepicker2" name="end_time" onfocus="getDiff()" value="{{$payroll->end_time ?? null }}" placeholder="End Time" class="form-control" />
                                        </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Working Hours</label>
                                        <div class="col-xs-12 col-sm-5">
                                        <span class="block input-icon input-icon-right">
                                            <input name="working_hours" type="number" id="working_hours" onfocus="getDiff()" value="{{$payroll->working_hours ?? null}}" class="form-control" placeholder="Enter working hours" required />
                                        </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-md-6">
                                            <label class="control-label col-sm-3 no-padding-right"></label>
                                            <div class="col-sm-9">
                                                <button class="btn btn-info" type="submit">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>

                                    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                                        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



@section('js')


    <script src="{{ asset('assets/js/jquery-ui.custom.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/spinbox.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('assets/js/autosize.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-tag.min.js') }}"></script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        // Add jquery class
        $("#payroll_setting").addClass('active');
        $("#hrm_payroll").addClass('open');
        // end add class

        $('#timepicker1').timepicker({
            timeFormat: 'h',
            interval: 60,
            defaultTime: '11',
            startTime: '09:00',
        }).on('focus', function() {
            $('#timepicker1').timepicker('showWidget');
        }).next().on(ace.click_event, function() {
            $(this).prev().focus();
        });
        $('#timepicker2').timepicker({
            timeFormat: 'h',
            interval: 60,
            defaultTime: '11',
            startTime: '09:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        })

        function getDiff() {
            var timeFrom = $('#timepicker1').data('timepicker');
            var timeTO = $('#timepicker2').data('timepicker');
            var timeFromHH = (timeFrom.hour == 12 && timeFrom.meridian == "AM") ? 0 :
                (timeFrom.hour != 12 && timeFrom.meridian == "PM") ? timeFrom.hour + 12 :
                    timeFrom.hour;
            var timeTOHH = (timeTO.hour == 12 && timeTO.meridian == "AM") ? 0 :
                (timeTO.hour != 12 && timeTO.meridian == "PM") ? timeTO.hour + 12 :
                    timeTO.hour;
            var timeFromMM = timeFromHH * 60 + timeFrom.minute;
            var timeTOMM = timeTOHH * 60 + timeTO.minute;

            var diffMM = Math.abs(timeTOMM - timeFromMM);
            var diff = Math.floor(diffMM / 60);
            // var diff = Math.floor(diffMM / 60) + ": " + (diffMM % 60) + "Hours";

            $("#working_hours").val(diff);
        }
        $('#timepicker2').on('onfocus', function() {
            console.log('ok')
            differenceHours.diff_hours('timepicker1', 'timepicker2', 'working_hours')
        })
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
                        $this.next().css({
                            'width': $this.parent().width()
                        });
                    })
                }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                if (event_name != 'sidebar_collapsed') return;
                $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({
                        'width': $this.parent().width()
                    });
                })
            });


            $('#chosen-multiple-style .btn').on('click', function(e) {
                var target = $(this).find('input[type=radio]');
                var which = parseInt(target.val());
                if (which == 2) $('#form-field-select-4').addClass('tag-input-style');
                else $('#form-field-select-4').removeClass('tag-input-style');
            });
        }
    </script>


@endsection


