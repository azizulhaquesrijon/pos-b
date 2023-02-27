<!doctype html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cash Flow Report</title>

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
            style="line-height: 0;margin-top:0px;margin-bottom:0;">
            {{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->shop_name : optional(auth()->user()->businessProfileByUser)->shop_name }}
        </h3>
        <h4 style="line-height: 0;margin-top:0;">
            {{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->business_mobile : optional(auth()->user()->businessProfileByUser)->business_mobile }}
        </h4>
        <h5 style="line-height: -5px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            Cash Flow Report
        </h5>
        <h6 style="line-height: -5px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            <b>{{ fdate(request('date', now())) }}</b>
        </h6>
    </div>
</htmlpageheader>



<table class="table table-striped table-bordered table-hover" style="width: 100%;" border="1">
    <thead>
    <tr style="background: #c6c6c6">
        <th>Sl</th>
        <th>Date</th>
        <th>Description</th>
        <th>Account Name</th>
        <th class="text-center">IN</th>
        <th class="text-center">OUT</th>
        <th class="text-right">Amount</th>
    </tr>
    </thead>

    <tbody>

    @php
        $total = 0;
        $in_total = 0;
        $out_total = 0;
    @endphp
    @foreach($reports as $key => $item)

        @php
            $in = $item->balance_type == 'In' ? $item->amount : 0;
            $out = $item->balance_type == 'Out' ? $item->amount : 0;
            $total = $total + ($in - $out);
            $in_total += $in;
            $out_total += $out;
        @endphp
        <tr class="body-wi">
            <td >{{ $loop->iteration }}</td>
            <td class="body-wi">{{ $item->date }}</td>
            <td class="body-wi">{{ $item->description }}</td>
            <td class="body-wi">{{ optional($item->account)->name }}</td>
            <td class="text-center body-wi">{{ number_format($in,2) }}</td>
            <td class="text-center body-wi">{{ number_format($out,2) }}</td>
            <td class="text-right body-wi">{{ number_format($total,2) }}</td>
        </tr>
    @endforeach
    </tbody>

    <tfoot style="background: #c1c1c1">
    <tr>
        <th colspan="4"><strong>Total:</strong></th>
        <th class="text-center">
            {{ $in_total }}
        </th>
        <th class="text-center">
            {{ $out_total }}
        </th>
        <th class="text-right">
            {{ $total }}
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

