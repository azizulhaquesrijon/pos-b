<!doctype html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Income Expense Report</title>

    <style>
        body {
            font-family: 'Helvetica Neue, Helvetica, Arial,sans-serif, nikosh';
            font-size: 80.25%;
        }

        @page {
            header: page-header;
            footer: page-footer;
            sheet-size: A4;
            margin-top: 130px;
            margin-left: 25px;
            margin-right: 25px;
            margin-bottom: 55px;
        }

        table,
        th,
        th {
            font-size: 10px;
        }

        table {
            border-top: none;
            border-left: none;
            border-right: none;
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
            width: 100%;
        }

        th,
        th {
            padding-left: 2px !important;
        }

        th.head {
            background-color: rgba(143, 175, 170, 0.35);
        }

        th.loop_th {
            height: 30px;
        }
        tr.body-wi{
            font-weight: lighter !important;
        }

        .text-center {
            text-align: center;
        }

    </style>

</head>

<body>

<htmlpageheader name="page-header">
    <div style="text-align: center; height: auto">
        <h3
            style="line-height: 0;margin-top:0px;margin-bottom:0;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            {{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->shop_name : optional(auth()->user()->businessProfileByUser)->shop_name }}
        </h3>
        <h4 style="line-height: 0;margin-top:0;  font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            {{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->business_mobile : optional(auth()->user()->businessProfileByUser)->business_mobile }}
        </h4>
        <h5 style="line-height: -5px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            Income Expense Report
        </h5>
        <h6 style="line-height: -5px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            <b>{{ fdate(request('date', now())) }}</b>
        </h6>
    </div>
</htmlpageheader>



<table class="table table-striped table-bordered table-hover" style="width: 100%;" border="1">
    <thead>
    <tr style="background: #c6c6c6">
    <tr>
        <th colspan="2" style="background-color: #e0e0e0;text-align: right;font-size: 12px;color: black">Income</th>
        <th colspan="2" style="background-color: #e0e0e0;text-align: left;font-size: 12px;color: black">Expense</th>
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
    @forelse ($reports as $key => $report)
            @php
                $income_total += $report->balance_type == 'In' ? $report->amount : 0;
                $expense_total += $report->balance_type == 'Out' ? $report->amount : 0;
            @endphp

{{--            @if($report->transactionable_type == 'Income' || $report->transactionable_type == 'Expense')--}}
{{--                @foreach($report->transactionable->details ?? [] as $detail)--}}
{{--                    <tr>--}}
{{--                        <th>{{ $report->balance_type == 'In' ? $report->transactionable_type . ' => '. $detail->chart_account->name .' => '.$report->date : '' }}</th>--}}
{{--                        <th>{{ $report->balance_type == 'In' ? $detail->amount : '' }}</th>--}}

{{--                        <th>{{ $report->balance_type == 'Out' ? $report->transactionable_type . ' => '. $detail->chart_account->name .' => '.$report->date : '' }}</th>--}}
{{--                        <th>{{ $report->balance_type == 'Out' ? $detail->amount : '' }}</th>--}}
{{--                    </tr>--}}

{{--                @endforeach--}}
{{--            @else--}}
                <tr>
                    <th>{{ $report->balance_type == 'In' ? $report->transactionable_type .'('.$report->date.')' : '' }}</th>
                    <th>{{ $report->balance_type == 'In' ? $report->amount : '' }}</th>
                    <th>{{ $report->balance_type == 'Out' ? $report->transactionable_type .' ('.$report->date.')' : '' }}</th>
                    <th>{{ $report->balance_type == 'Out' ? $report->amount : '' }}</th>
                </tr>

{{--            @endif--}}

            @empty
                <tr>
                    <th colspan="30" class="text-center text-danger py-3"
                        style="background: #eaf4fa80 !important; font-size: 18px">
                        <strong>No records found!</strong>
                    </th>
                </tr>
            @endforelse
    </tbody>

    <tfoot>
    <tr style="background: #c6c6c6">
        <th><strong>Income Total:</strong></th>
        <th>
            {{ number_format($income_total,2) }}
        </th>
        <th><strong>Expense Total:</strong></th>
        <th>
            {{ number_format($expense_total,2) }}
        </th>
    </tr>
    </tfoot>
</table>



<htmlpagefooter name="page-footer">
    <div align="right" style="font-size: 12px;">
        <hr>
        <i><b>{PAGENO} / {nbpg}</b></i>
    </div>
</htmlpagefooter>


</body>

</html>


