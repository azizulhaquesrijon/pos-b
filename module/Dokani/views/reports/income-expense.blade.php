@extends('layouts.master')


@section('title', 'Income Expense Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Income Expense Report
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
                            <div class="col-sm-12 col-sm-offset-3">
                                <form action="">
                                    <div class="row py-2">
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
                                        <div class="col-md-4">
                                            <div class="btn-group btn-corner" style="">
                                                <button type="submit" class="btn btn-xs btn-success">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                                <a href="{{ request()->url() }}" class="btn btn-xs">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">

                                <div class="table-responsive" style="border: 1px #cdd9e8 solid">
                                    <div >
                                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th colspan="2" style="background-color: #DCF4F1;text-align: right;font-size: 12px;color: black">Income</th>
                                                <th colspan="2" style="background-color: #F4C8CA;font-size: 12px;color: black">Expense</th>
                                            </tr>
                                            <tr>
                                                <th>Description</th>
                                                <th>Amount</th>

                                                <th>Description</th>
                                                <th>Amount</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @php
                                                $income_total = 0;
                                                $expense_total = 0;
                                            @endphp

                                            @if(request()->filled('from') || request()->filled('to'))

                                                @forelse ($reports as $key => $report)

                                                    @php
                                                        $income_total += $report->balance_type == 'In' ? $report->amount : 0;
                                                        $expense_total += $report->balance_type == 'Out' ? $report->amount : 0;
                                                    @endphp

                                                    @if($report->transactionable_type == 'Income' || $report->transactionable_type == 'Expense')
                                                        @foreach($report->transactionable->details ?? [] as $detail)
                                                            <tr>
                                                                <td>{{ $report->balance_type == 'In' ? $detail->chart_account->name .' ('.$report->date.')' : '' }}</td>
                                                                <td>{{ $report->balance_type == 'In' ? $detail->amount : '' }}</td>

                                                                <td>{{ $report->balance_type == 'Out' ? $detail->chart_account->name .' ('.$report->date.')' : '' }}</td>
                                                                <td>{{ $report->balance_type == 'Out' ? $detail->amount : '' }}</td>
                                                            </tr>

                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td>{{ $report->balance_type == 'In' ? $report->transactionable_type .' ('.$report->date.')' : '' }}</td>
                                                            <td>{{ $report->balance_type == 'In' ? $report->amount : '' }}</td>
                                                            <td>{{ $report->balance_type == 'Out' ? $report->transactionable_type .' ('.$report->date.')' : '' }}</td>
                                                            <td>{{ $report->balance_type == 'Out' ? $report->amount : '' }}</td>
                                                        </tr>

                                                    @endif

                                                @empty
                                                    <tr>
                                                        <td colspan="30" class="text-center text-danger py-3"
                                                            style="background: #eaf4fa80 !important; font-size: 18px">
                                                            <strong>No records found!</strong>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            @else
                                                <tr>
                                                    <td colspan="30" class="text-center text-danger py-3"
                                                        style="background: #eaf4fa80 !important; font-size: 18px">
                                                        <strong>No records found!</strong>
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>

                                            <tfoot>
                                            <tr>
                                                <th><strong>Income Total:</strong></th>
                                                <th>
                                                    {{ number_format($income_total,2) }}
                                                </th>
                                                <th><strong>Expense Total:</strong></th>
                                                <th>
                                                    {{ number_format($expense_total,2) }}
                                                </th>
                                            </tr>

                                            <tr>
                                                <th colspan="7">
                                                    <a  href="{{ route('dokani.reports.income-expense') }}?export_type=income-expense-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                        title="Excel" style="padding-left: 10px" target="_blank">
                                                        <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                                    </a>&nbsp;&nbsp;&nbsp;
                                                    <a href="{{ route('dokani.reports.income-expense') }}?export_type=income-expense-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                       title="Pdf" target="_blank">
                                                        <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}" alt="">
                                                    </a>
                                                </th>
                                            </tr>
                                            </tfoot>

                                        </table>

                                    </div>

                                    @if($reports != null)
                                        @include('partials._paginate',['data'=> $reports])
                                    @endif
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

