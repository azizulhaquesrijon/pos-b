@extends('layouts.master')


@section('title', 'Chart of Account Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Chart of Account Report
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

                        <!-- Searching -->
                        <div class="row">
                            <div class="col-sm-12">
                                <form action="">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="row">
                                                <div class="col-md-4">
                                                    <select name="party_id" id="" class="form-control select2" data-placeholder="--Select Party">
                                                        <option value=""></option>
                                                        @foreach($g_parties as $key => $item)
                                                            <option value="{{ $key }}" {{ request('party_id') == $key ? 'selected' : '' }}>
                                                                {{ $item }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="chart_of_account_id" id="" class="form-control select2"
                                                            data-placeholder="--Select Head Account--" required>
                                                        <option value=""></option>
                                                        @foreach($g_accounts as $item)
                                                            <option value="{{ $item->id }}" {{ request('chart_of_account_id') == $item->id ? 'selected' : '' }}>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" name="from" class="form-control date-picker" value="{{ request('from') }}"
                                                               autocomplete="off" placeholder="From" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-exchange"></i>
                                                        </span>
                                                        <input type="text" name="to" class="form-control date-picker" value="{{ request('to') }}"
                                                               autocomplete="off" placeholder="To" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="btn-group btn-corner" style="float: right">
                                                    <button type="submit" class="btn btn-xs btn-success">
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                    <a href="{{ request()->url() }}" class="btn btn-xs">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">

                                <div class="table-responsive" style="border: 1px #cdd9e8 solid;">

                                    <!-- Table -->
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            {{--                                            <th>Sl</th>--}}
                                            <th>Date</th>
                                            <th>Party Name</th>
                                            <th>Account Name</th>
                                            <th class="text-center">Head Account</th>
                                            <th class="text-right">Income Amount</th>
                                            <th class="text-right">Expense Amount</th>
                                        </tr>
                                        </thead>

{{--                                        @if(request()->filled('party_id') || request()->filled('chart_of_account_id') || request()->filled('from') || request()->filled('to'))--}}
                                        <tbody>

                                        @php
                                            $income_total = 0;
                                            $expense_total = 0;
                                        @endphp
                                        @forelse ($vouchers as $key => $report)

                                            @foreach($report->details as $item)
                                                @php
                                                    $income = $report->type == 'income' ? $item->amount : 0;
                                                    $expense = $report->type == 'expense' ? $item->amount : 0;
                                                    $income_total += $income;
                                                    $expense_total += $expense;
                                                @endphp

                                                <tr>
                                                    {{--                                                    <td>{{ $item->id }}</td>--}}
                                                    <td>{{ $report->date }}</td>
                                                    <td>{{ optional($report->party)->name }}</td>
                                                    <td>{{ $report->type }}</td>
                                                    <td class="text-center">{{ optional($item->chart_account)->name }}</td>
                                                    <td class="text-right">{{ $report->type == 'income' ? $item->amount : 0 }}</td>
                                                    <td class="text-right">{{ $report->type == 'expense' ? $item->amount : 0 }}</td>
                                                </tr>
                                            @endforeach

                                        @empty
                                            <tr>
                                                <td colspan="30" class="text-center text-danger py-3"
                                                    style="background: #eaf4fa80 !important; font-size: 18px">
                                                    <strong>No records found!</strong>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
{{--                                        @endif--}}

                                        <tfoot>
                                        {{--                                            <tr>--}}
                                        {{--                                                <th colspan="4"><strong>Grand Total:</strong></th>--}}
                                        {{--                                                <th class="text-right">--}}
                                        {{--                                                    {{ $income_total }}--}}
                                        {{--                                                </th>--}}
                                        {{--                                                <th class="text-right">--}}
                                        {{--                                                    {{ $expense_total }}--}}
                                        {{--                                                </th>--}}
                                        {{--                                            </tr>--}}

                                        <tr>
                                            <th colspan="7">
                                                <a  href="{{ route('dokani.reports.chart-of-account') }}?export_type=chart-of-account-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                    title="Excel" style="padding-left: 10px" target="_blank">
                                                    <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                                </a>&nbsp;&nbsp;&nbsp;
                                                <a href="{{ route('dokani.reports.chart-of-account') }}?export_type=chart-of-account-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                   title="Pdf" target="_blank">
                                                    <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}" alt="">
                                                </a>
                                            </th>
                                        </tr>
                                        </tfoot>

                                    </table>

                                    @include('partials._paginate',['data'=> $vouchers])

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


