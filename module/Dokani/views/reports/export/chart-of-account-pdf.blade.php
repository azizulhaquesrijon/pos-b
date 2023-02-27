<!doctype html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chart of Account Report</title>

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
            Chart of Account Report
        </h5>
        <h6 style="line-height: -5px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            <b>{{ fdate(request('date', now())) }}</b>
        </h6>
    </div>
</htmlpageheader>



<table class="table table-striped table-bordered table-hover" style="width: 100%;" border="1">
    <thead>
    <tr style="background: #c6c6c6">
        <th style="width: 10%">Date</th>
        <th style="width: 10%">Party Name</th>
        <th style="width: 10%">Account Name</th>
        <th class="text-center" style="width: 10%">Head Account</th>
        <th class="text-right" style="width: 20%">Income Amount</th>
        <th class="text-right" style="width: 20%">Expense Amount</th>
    </tr>
    </thead>

    <tbody>

    @php
        $income_total = 0;
        $expense_total = 0;
    @endphp
    @foreach ($vouchers as $key => $report)

        @foreach($report->details as $item)
            @php
                $income = $report->type == 'income' ? $item->amount : 0;
                $expense = $report->type == 'expense' ? $item->amount : 0;
                $income_total += $income;
                $expense_total += $expense;
            @endphp

            <tr>
                {{--                                                    <td>{{ $item->id }}</td>--}}
                <th>{{ $report->date }}</th>
                <th>{{ optional($report->party)->name }}</th>
                <th>{{ $report->type }}</th>
                <th class="text-center">{{ optional($item->chart_account)->name }}</th>
                <th class="text-right">{{ $report->type == 'income' ? $item->amount : 0 }}</th>
                <th class="text-right">{{ $report->type == 'expense' ? $item->amount : 0 }}</th>
            </tr>
        @endforeach

    @endforeach
    </tbody>

    <tfoot style="background: #c1c1c1">
    <tr>
        <th colspan="4"><strong>Total:</strong></th>
        <th class="text-center">
            {{ number_format($income_total) }}
        </th>
        <th class="text-center">
            {{ number_format($expense_total) }}
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


