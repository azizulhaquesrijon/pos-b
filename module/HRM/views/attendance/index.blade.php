@extends('layouts.master')


@section('title', 'Employee Attendance')

@section('page-header')
    <i class="fa fa-info-circle"></i> Employee Attendance
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">
                        @if (hasPermission('dokani.employees.create', $slugs))
                            <a href="{{ route('dokani.employees.create') }}" class="">
                                <i class="fa fa-plus"></i> Add New
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Searching -->
                        <div class="row">
                            <div class="col-sm-12">
                                <form class='filter-form no-print'>
                                    <div class="row" style="margin:25px;">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">

                                        </div>

                                        <div class="col-md-3">
                                            <!-- <label>Product Name</label> -->
                                            <div class="input-group-append">
                                                <input type="text" value="{{ request('date') ?? Carbon\Carbon::now()->format('Y/m/d') }}" class="form-control filter" name="date"
                                                       id="date" data-column-index='2' onchange="getDate(this)" placeholder="Choose Date...">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div style="margin-right:10px;">
                                                <button type="submit" class="btn btn-success btn-sm"><i
                                                        class="ace-icon fa fa-check"></i>Check</button>
                                                <a href="{{ request()->url() }}" class="btn btn-warning btn-sm"><i
                                                        class="ace-icon fa fa-undo"></i>Reset</a>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <form action="{{ route('dokani.attendance.store') }}" method="POST">
                                    @csrf

                                <div class="table-responsive" style="border: 1px #cdd9e8 solid;">

                                    <!-- Table -->
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>OT Hour</th>
                                            <th>
                                                <input type="checkbox" data-toggle="toggle" data-on="Present" data-off="Absent"
                                                        id="attendance_all" onchange="return checkALL(this)" data-onstyle="success"
                                                        data-offstyle="danger" checked> </th>
                                            </tr>
                                            </thead>

                                        <tbody>

                                        <input type="hidden" name="date" value="{{ Carbon\Carbon::now()->format('Y/m/d') }}" id="att_date">

                                        @forelse ($employees as $key => $item)
                                            @php
                                                $today_attendance = optional($item->attendance)->where('date', request('date') ?? Carbon\Carbon::now()->format('Y/m/d') )->first();
                                                $status = $today_attendance->status ?? 0;
                                            @endphp

                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <input type="hidden" value="{{ $item->id }}" name="employee_id[]" required>
                                                    {{ $item->id }}
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->mobile }}</td>


                                                <td>
                                                    <input type="number" min=0 name="ot_hour[]" value="{{ optional($today_attendance)->ot_hour ?? 0 }}"
                                                           style="width: 70px;">
                                                </td>

                                                <td class="type">
                                                    <input type="hidden" value="{{ $status }}" name="status[]"
                                                           class="hidden_data" required>
                                                    <label>
                                                        <input class="ace ace-switch ace-switch-7" type="checkbox"
                                                               {{ optional($today_attendance)->status == 1 ? 'checked' : '' }} value="1"/>
                                                        <span class="lbl"
                                                              data-lbl="NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;YES"></span>
                                                    </label>
                                                    <label>
                                                        <input type="checkbox" class="ace input-lg leave"
                                                               {{ optional($today_attendance)->status == 2 ? 'checked' : '' }} value="2"/>
                                                        <span class="lbl bigger-120">Leave</span>
                                                    </label>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="30" class="text-center text-danger py-3"
                                                    style="background: #eaf4fa80 !important; font-size: 18px">
                                                    <strong>No records found!</strong>
                                                </td>
                                            </tr>
                                        @endforelse

                                        </tbody>
                                    </table>
                                        <div class="form-group" style="float: right;">
                                            <div class="col-sm-4">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa fa-check"></i>
                                                    Submit
                                                </button>
                                            </div>
                                        </div>

{{--                                    @include('partials._paginate',['data'=> $employees])--}}

                                </div>
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


    <!-- inline scripts related to this page -->
    <script>
        $(document).ready(function() {
            $('#date').datepicker({
                format: "yyyy/mm/dd",
                autoclose: true,
                todayHighlight: true,
            })
        })

        function getDate(obj){
            var date = $('#date').val();
            $('#att_date').val(date);

        }

        function checkALL(object) {
            if ($(object).is(':checked')) {
                $('.ace-switch-7').each(function() {
                    $(this).prop('checked', true)
                    $(this).closest('.type').find('.hidden_data').val(1);
                })
            } else {
                $('.ace-switch-7').each(function() {
                    $(this).prop('checked', false)
                    $(this).closest('.type').find('.hidden_data').val(0);
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
        $('.leave').on('change', function() {
            if ($(this).is(':checked')) {
                $(this).closest('.type').find('.ace-switch-7').prop('checked', false)
                $(this).closest('.type').find('.hidden_data').val(2);
            }
        })

        function checkEnable(object) {
            $(object).each(function() {
                $(this).closest('label').addClass("btn btn-danger");
            })
        }
    </script>

    <script type="text/javascript">
        function delete_item(url) {
            $('#deleteItemForm').attr('action', url)
        }
    </script>
    <script>
        /***************/
        $('.show-details-btn').on('click', function(e) {
            e.preventDefault();
            $(this).closest('tr').next().toggleClass('open');
            $(this).find(ace.vars['.icon']).toggleClass('fa-eye').toggleClass('fa-eye-slash');
        });
        /***************/
    </script>

@stop

