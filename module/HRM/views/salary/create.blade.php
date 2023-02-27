@extends('layouts.master')

@section('title', 'Salary Generate')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Salary Generate
@stop

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .switch-toggle {
            width: 10em;
        }

        .switch-toggle label:not(.disabled) {
            cursor: pointer;
        }

        .active {
            background-color: green;
        }
    </style>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                @if (\Session::has('message'))
                    <div class="alert alert-success">
                        {!! \Session::get('message').'</br>' !!}
                    </div>
                @endif
                <form class='filter-form no-print' action="">
                    <div class="row" style="margin-top:25px;">
                        <div class="col-md-2"></div>
                        <div class="col-md-2">

                        </div>

                        <div class="col-md-3">
                            <!-- <label>Product Name</label> -->
                            <div class="input-group-append">
                                <input type="text" value="{{ $date ?? Carbon\Carbon::now()->format('Y/m') }}"
                                       class="form-control filter" name="date" id="date" onchange="getDate()"
                                       placeholder="Choose Date..." autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div style="margin-right:10px;">
                                <button type="submit" class="btn btn-success btn-sm"><i class="ace-icon fa fa-check"></i>Check</button>
                                <a href="{{ request()->url() }}" class="btn btn-warning btn-sm"><i
                                        class="ace-icon fa fa-undo"></i>Reset</a>
                            </div>

                        </div>
                    </div>
                </form>
                <div class="space-10"></div>
                <form action="{{route('dokani.salaries.store')}}" method="post">
                    @csrf
                    <div class="table-responsive">
                        <input type="hidden" value="{{ $date ?? Carbon\Carbon::now()->format('Y/m') }}" name="date" required>
                        <input type="hidden" value="{{ $payroll->working_hours }}" id="t_hours" readonly>
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="center">
                                    <label class="pos-rel">
                                        <input type="checkbox" class="ace" onchange="checkAll(this);storeTblValues()" />
                                        <span class="lbl"></span>
                                    </label>
                                </th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th style="width:5%;">TTL Days</th>
                                <th style="width:5%;">Off Day</th>
                                <th style="width:5%;">W. Days</th>
                                <th style="width:5%;">Attn Day</th>
                                <th style="width:5%;">Abs Days</th>
                                <th style="width:5%;">Leave</th>
                                <th style="width:5%;">Pay Days</th>
                                <th style="width:5%;">OT Hours</th>
                                <th style="width:5%;">OT Amount</th>
                                <th>Advance</th>
                                <th style="text-align: right">Salary</th>
                                <th>Net Salary</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <td colspan="14">Total Payable Salary</td>
                                <td class="total_payable_salary text-right">0</td>
                                <input type="hidden" name="total_payable_salary" id="total_payable_salary" value="">
                            </tr>
                            </tfoot>
                            <tbody>
                            @php
                                $monthDays=cal_days_in_month(CAL_GREGORIAN,date("m",strtotime($date)),date("Y",strtotime($date)));
                            @endphp
                            @foreach($employees as $key=> $employee)
                                @php
                                $getDate = $date ?? \Carbon\Carbon::now()->format('Y/m');
                                  $has_employee = \Module\HRM\Models\Salary::dokani()->where('employee_id',$employee->id)->where('date',$getDate)->count();
                                @endphp
                                @if($has_employee == 0)
                                    <tr>
                                    <td class="center">
                                        <label class="pos-rel">
                                            <input type="checkbox" class="ace ace-switch-7 employee_id" onchange="storeTblValues()" name="data[]" value="" />
                                            <span class="lbl"></span>
                                        </label>
                                    </td>

                                    <td class="em_id">{{ $employee->id }}
                                        <input type="hidden" name="employee_id[]" id="employee_id" value="{{ $employee->id }}" required>
                                    </td>
                                    <td>
                                        {{ $employee->name }}
                                    </td>
                                    <td>
                                        <input type="text" name="ttl_days[]" class="ttl_days" value="{{ $monthDays }}" readonly style="width:50px">
                                    </td>
                                    <td class="off_day">
                                        <input type="number" class="holidays" name="holidays[]" min=1 max=31 onkeyup="wdays(this)" value="{{ $holidays }}" style="width:50px">
                                    </td>
                                    <td class="wdays">
                                        <input type="number" class="working_days" name="working_days[]" value="{{ $monthDays-$holidays }}" style="width:50px" readonly>
                                    </td>
                                    @php

                                        $month = $getMonth ?? Carbon\Carbon::now()->format('m');

                                        $present=\Module\HRM\Models\Attendance::dokani()->where('employee_id',$employee->id)->where('month',$month)->where('status',1)->count();
                                        $absent=\Module\HRM\Models\Attendance::dokani()->where('employee_id',$employee->id)->where('month',$month)->where('status',0)->count();
                                        $leave=\Module\HRM\Models\Attendance::dokani()->where('employee_id',$employee->id)->where('month',$month)->where('status',2)->count();
                                        $total_ot_hours=\Module\HRM\Models\Attendance::dokani()->where('employee_id',$employee->id)->where('month',$month)->sum('ot_hour');
                                        //$present = 24;
                                        //$absent = 2;
                                        //$leave = 0;
                                        $advance=\Module\HRM\Models\Advance::dokani()->where('employee_id',$employee->id)->first();
                                        $advance=$advance->amount ?? 0;
                                    @endphp

                                    <td class="present">{{ $present }}</td>
                                    <td class="absent">{{ $absent }}</td>
                                    <td class="leave">{{ $leave }}</td>
                                    <td class="pay_days">{{ $present+$holidays+$leave }}</td>
                                    <td>
                                        <input type="number" class="ot_hours" onkeyup="OtHours(this);payableSalary()" min=0 name="ot_hour[]" style="width:50px" value="{{ $total_ot_hours }}">
                                    </td>
                                    @php
                                      $total_hours = $payroll->working_hours * $monthDays;
                                      $amount = $employee->salary / $total_hours;
                                      $ot_amount = $amount * $total_ot_hours;
                                    @endphp
                                    <td class="ot_amounts">{{ floor($ot_amount) }}</td>
                                    <td class="advance">{{ $advance }}</td>
                                    <td class="salary">{{ $employee->salary }}</td>

                                    @php
                                        $net_salary=($employee->salary*($present+$holidays))/$monthDays;
                                    @endphp
                                    <td class="net_salary text-right">{{ floor($employee->salary + $ot_amount) }}</td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        <div class="space-2"></div>
                        <textarea name="alldata" id="all" style="display: none;" required></textarea>
                        <div class="form-group" style="float: right;">
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-check"></i>
                                    Generate
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- The Modal -->
        <div class="modal fade" id="delete-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="" id="deleteItemForm" method="POST">
                    @csrf @method("DELETE")
                    <!-- Modal Header -->
                        <div class="modal-header" style="padding:5px 15px 5px 0px !important; ">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <h3 style="margin: 0;text-align: center;padding-bottom: 20px;">Are you sure, want to delete this
                                record</h3>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer text-right">
                            <div class="btn-group btn-corner">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- inline scripts related to this page -->
    <script>
        $(document).ready(function() {
            payableSalary()
            $('#date').datepicker({
                format: "yyyy/mm",
                autoclose: true,
                todayHighlight: true,
                viewMode: "months",
                minViewMode: "months"
            })
        })

        function checkAll(object) {
            if ($(object).is(':checked')) {

                $('.ace-switch-7').each(function() {
                    $(this).prop('checked', true)
                })
            } else {
                $('.ace-switch-7').each(function() {
                    $(this).prop('checked', false)
                })

            }
        }
        $('.ace-switch-7').on('click', function() {
            if ($(this).is(':checked')) {
                $(this).closest('.type').find('.hidden_data').val(1);
            } else {
                $(this).closest('.type').find('.hidden_data').val(0);
            }

        })

        function getDate(){

            var dt = $('#date').val();
            var date = new Date(dt)
            var currentYear = date.getFullYear();
            var currentMonth = date.getMonth() + 1; // months are 0-based
            var daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
            $('.ttl_days').val(daysInMonth)

        }

        function wdays(object) {
            var that = $(object)
            var holidays = parseInt(that.val());
            var wdays = parseInt(that.closest('tr').find('.ttl_days').val());
            var pay_days = parseInt(that.closest('tr').find('.present').text());
            var net_salary = parseInt(that.closest('tr').find('.salary').text());
            var total = (wdays - holidays);
            var total_pay_days = (pay_days + holidays);
            net_salary=parseInt((net_salary/wdays)*total_pay_days);
            that.closest('tr').find('.working_days').val(total)
            that.closest('tr').find('.net_salary').text(net_salary)
            that.closest('tr').find('.pay_days').text(total_pay_days)
        }

        function storeTblValues() {
            var TableData = new Array();
            $('.ace-switch-7:checked').each(function(row) {
                var that = $(this)
                TableData[row] = {
                    "total_days": parseInt(that.closest('tr').find('.ttl_days').val()),
                    "working_days": parseInt(that.closest('tr').find('.working_days').val()),
                    "total_present": parseInt(that.closest('tr').find('.present').text()),
                    "salary": parseInt(that.closest('tr').find('.salary').text()),
                    "payable_salary": parseInt(that.closest('tr').find('.net_salary').text()),
                    "total_absent": parseInt(that.closest('tr').find('.absent').text()),
                    "total_off_days": parseInt(that.closest('tr').find('.holidays').val()),
                    "total_leave": parseInt(that.closest('tr').find('.leave').text()),
                    "employee_ids": parseInt(that.closest('tr').find('#employee_id').val()),
                    "advance": parseInt(that.closest('tr').find('.advance').text()),
                    "ot_hours": parseInt(that.closest('tr').find('.ot_hours').val()),
                    "ot_amounts": parseInt(that.closest('tr').find('.ot_amounts').text()),
                }
            })
            var obj = JSON.stringify(TableData)
            $('#all').val(obj)
        }
    </script>
    <script>
        // ot_hours
        function OtHours(object) {
            var that = $(object);
            var hours = parseInt(that.val());
            var payroll_working_hours = parseInt($('#t_hours').val());
            var salary = parseInt(that.closest('tr').find('.salary').text());
            var ttl_days = parseInt(that.closest('tr').find('.ttl_days').val());
            var working_days = parseInt(that.closest('tr').find('.working_days').val());
            var total_hours = parseInt(payroll_working_hours * ttl_days);
            var amount = parseInt(salary / total_hours);
            var ot_amount = amount * hours;
            that.closest('tr').find('.ot_amounts').text(ot_amount)
            that.closest('tr').find('.net_salary').text(salary + ot_amount);
        }
    </script>
    <script>
        function payableSalary() {
            var total = 0;
            $('.net_salary').each(function() {
                total += parseInt($(this).text());
            })
            $('.total_payable_salary').text(total);
            $('#total_payable_salary').val(total);
        }
    </script>
    <script type="text/javascript">
        jQuery(function($) {
            //initiate dataTables plugin
            var myTable =
                $('#dynamic-tables')
                    //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                    .DataTable({
                        bAutoWidth: false,
                        "aoColumns": [{
                            "bSortable": false
                        },
                            null, null, null, null, null,
                            {
                                "bSortable": false
                            }
                        ],
                        "aaSorting": [],
                        select: {
                            style: 'multi'
                        }
                    });

        })
    </script>

    <script type="text/javascript">
        // Add class for active/open
        $('#salaries').addClass('active')
        $("#hrm_payroll").addClass('open')
        //
        function delete_item(url) {
            $('#deleteItemForm').attr('action', url)
        }
    </script>
@endsection
