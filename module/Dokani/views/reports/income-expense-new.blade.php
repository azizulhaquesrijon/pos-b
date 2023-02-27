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
                            <div class="col-sm-12">
                                <form action="">
                                    <table class="table">
                                        <tbody>

                                        <tr>
                                            <td>
                                                <div class="col-md-10">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" name="from" class="form-control date-picker" value="{{ request('from') }}" autocomplete="off" placeholder="From" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-exchange"></i>
                                                        </span>
                                                        <input type="text" name="to" class="form-control date-picker" value="{{ request('to') }}" autocomplete="off" placeholder="To" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-corner">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                    <a href="{{ request()->url() }}" class="btn btn-sm">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
                                                </div>
                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
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

                                            @if(request()->filled('from') || request()->filled('to'))
                                                @php
                                                    $saleReports = $reports->where('transactionable_type', 'Sale')->groupBy('date')->toArray();
                                                @endphp
                                                @foreach ($saleReports as $key => $saleReport)
                                                    <tr>
                                                        <td>{{ isset($saleReport[0]) ? $saleReport[0]['transactionable_type'] : '' }}</td>
                                                        <td>{{ array_sum(array_column($saleReport, 'amount')) }}</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                                @php
                                                    $purchaseReports = $reports->where('transactionable_type', 'Purchase')->groupBy('date')->toArray();
                                                @endphp
                                                @foreach ($purchaseReports as $key => $purchaseReport)
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{ isset($purchaseReport[0]) ? $purchaseReport[0]['transactionable_type'] : '' }}</td>
                                                        <td>{{ array_sum(array_column($purchaseReport, 'amount')) }}</td>
                                                    </tr>
                                                @endforeach
                                                @php
                                                    $voucherReports = $reports->where('transactionable_type', 'Income');
                                                @endphp

                                                @foreach ($voucherReports as $key => $voucherReport)
                                                    @php
                                                        $voucherDetails = \Module\Dokani\Models\VoucherPaymentDetail::query()
                                                                            ->whereIn('voucher_payment_id', $voucherReports->pluck('transactionable_id'))
                                                                            ->with('chart_account')
                                                                            ->get()
                                                                            ->groupBy('chart_of_account_id', 'created_at')->toArray();
                                                    @endphp

                                                    @foreach($voucherDetails as $voucherDetail)
                                                        <tr>
                                                            <td>{{ isset($voucherDetail[0]) ? $voucherDetail[0]['chart_account']['name'] : '' }}</td>
                                                            <td>{{ array_sum(array_column($voucherDetail, 'amount')) }}</td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    @endforeach

                                                @endforeach

                                                @php
                                                    $voucherReportsExpense = $reports->where('transactionable_type', 'Expense');
                                                @endphp

                                                @foreach ($voucherReportsExpense as $key => $voucherReport)
                                                    @php
                                                        $voucherExpenseDetails = \Module\Dokani\Models\VoucherPaymentDetail::query()
                                                                            ->whereIn('voucher_payment_id', $voucherReportsExpense->pluck('transactionable_id'))
                                                                            ->with('chart_account')
                                                                            ->get()
                                                                            ->groupBy('chart_of_account_id', 'date')->toArray();
                                                    @endphp

                                                    @foreach($voucherExpenseDetails as $voucherExpenseDetail)
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{ isset($voucherExpenseDetail[0]) ? $voucherExpenseDetail[0]['chart_account']['name'] : '' }}</td>
                                                            <td>{{ array_sum(array_column($voucherExpenseDetail, 'amount')) }}</td>
                                                        </tr>
                                                    @endforeach

                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="30" class="text-center text-danger py-3"
                                                        style="background: #eaf4fa80 !important; font-size: 18px">
                                                        <strong>No records found!</strong>
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>

{{--                                            <tfoot>--}}
{{--                                                <tr>--}}
{{--                                                    <th><strong>Income Total:</strong></th>--}}
{{--                                                    <th>--}}
{{--                                                        {{ number_format($income_total,2) }}--}}
{{--                                                    </th>--}}
{{--                                                    <th><strong>Expense Total:</strong></th>--}}
{{--                                                    <th>--}}
{{--                                                        {{ number_format($expense_total,2) }}--}}
{{--                                                    </th>--}}
{{--                                                </tr>--}}
{{--    --}}
{{--                                                <tr>--}}
{{--                                                    <th colspan="7">--}}
{{--                                                        <a  href="{{ route('dokani.reports.income-expense') }}?export_type=income-expense-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"--}}
{{--                                                            title="Excel" style="padding-left: 10px" target="_blank">--}}
{{--                                                            <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">--}}
{{--                                                        </a>&nbsp;&nbsp;&nbsp;--}}
{{--                                                        <a href="{{ route('dokani.reports.income-expense') }}?export_type=income-expense-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"--}}
{{--                                                           title="Pdf" target="_blank">--}}
{{--                                                            <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}" alt="">--}}
{{--                                                        </a>--}}
{{--                                                    </th>--}}
{{--                                                </tr>--}}
{{--                                            </tfoot>--}}

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

