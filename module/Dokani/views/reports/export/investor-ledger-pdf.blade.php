<!doctype html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Investor Ledger Report</title>

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
            Investor Ledger Report
        </h5>
        <h6 style="line-height: -5px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            <b>{{ fdate(request('date', now())) }}</b>
        </h6>
    </div>
</htmlpageheader>



<table class="table table-striped table-bordered table-hover" style="width: 100%;" border="1">
    <thead>
    <tr style="background: #c1c1c1 !important;">
        <th>Sl</th>
        <th>Investor Name</th>
        <th class="text-right"> Date</th>
        <th class="text-right">Description</th>
        <th class="text-right">In</th>
        <th class="text-right">Out</th>
        <th class="text-right">Balance</th>
    </tr>
    </thead>

    <tbody>

    @php
        $total_in = 0;
        $total_out = 0;
        $total_balance = 0;
    @endphp

    @foreach ($reports as $key => $item)
        @php
            $total_in += $in = $item->balance_type == 'In' ? $item->amount : 0.00;
            $total_out += $out = $item->balance_type == 'Out' ? $item->amount : 0.00;
            $total_balance += $in - $out;
        @endphp
        <tr>
            <th>{{ $loop->iteration }}</th>
            <th>{{ optional(optional($item->investor)->g_party)->name }}</th>
            <th class="text-right">{{ $item->date }}</th>
            <th class="text-right">{{ $item->description }}</th>
            <th class="text-right">{{ $item->balance_type == 'In' ? $item->amount : 0.00 }}</th>
            <th class="text-right">{{ $item->balance_type == 'Out' ? $item->amount : 0.00 }}</th>
            <th class="text-right">{{ $total_balance }}</th>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr style="background: #c1c1c1 !important;">
        <th colspan="4"><strong>Total Qty:</strong></th>
        <th class="text-right">
            {{ $total_in }}
        </th>
        <th class="text-right">
            {{ $total_out }}
        </th>
        <th class="text-right">
            {{ $total_balance }}
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



