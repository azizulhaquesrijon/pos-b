@extends('layouts.master')


@section('title', 'View Details')

@section('page-header')
    <i class="fa fa-info-circle"></i> View Details
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
                        @if (hasPermission('dokani.salaries.index', $slugs))
                            <a href="{{ route('dokani.salaries.index') }}" class="">
                                <i class="fa fa-backward"></i> Back
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <div class="row">
                            <div class="col-xs-12">

                                <div class="table-responsive" style="border: 1px #cdd9e8 solid;">

                                    <!-- Table -->
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Employee Name</th>
                                            <th>Date</th>
                                            <th style="width:5%;">TTL Days</th>
                                            <th style="width:5%;">Off Day</th>
                                            <th style="width:5%;">W. Days</th>
                                            <th style="width:5%;">Attn Day</th>
                                            <th style="width:5%;">Abs Days</th>
                                            <th style="width:5%;">Leave</th>
                                            <th style="width:5%;">OT Hours</th>
                                            <th style="width:5%;">OT Amount</th>
                                            <th>Advance</th>
                                            <th>Salary</th>
                                            <th>Net Salary</th>

                                        </tr>
                                        </thead>

                                        <tbody>

                                            <tr>
                                                <td>{{ $details->employee->name }}</td>
                                                <td>{{ $details->date }}</td>
                                                <td>{{ $details->total_days }}</td>
                                                <td>{{ $details->total_off_days }}</td>
                                                <td>{{ $details->working_days }}</td>
                                                <td>{{ $details->total_present }}</td>
                                                <td>{{ $details->total_absent }}</td>
                                                <td>{{ $details->total_leave }}</td>
                                                <td>{{ $details->ot_hours }}</td>
                                                <td>{{ $details->ot_amounts }}</td>
                                                <td>{{ $details->advance }}</td>
                                                <td>{{ $details->salary }}</td>
                                                <td>{{ $details->payable_salary }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

{{--                                    @include('partials._paginate',['data'=> $salaries])--}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('js')



@stop



