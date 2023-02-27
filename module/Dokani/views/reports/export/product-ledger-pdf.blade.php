<!doctype html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Ledger Report</title>

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
            Product Ledger Report
        </h5>
        <h6 style="line-height: -5px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            <b>{{ fdate(request('date', now())) }}</b>
        </h6>
    </div>
</htmlpageheader>



<table class="table table-striped table-bordered table-hover" style="width: 100%;" border="1">
    <thead>
    <tr>
        <th>Sl</th>
        <th>Product Name</th>
        <th>Product Unit</th>
        <th class="text-right"> Date</th>
        <th class="text-right">Description</th>
        <th class="text-right">In</th>
        <th class="text-right">Out</th>
        <th class="text-right">Damage</th>
        <th class="text-right">Available Qty</th>
    </tr>
    </thead>

    <tbody>

    @php
        $total_in = 0;
        $total_out = 0;
        $total_available = 0;
        $grand_total_available = 0;
    @endphp

    @foreach ($reports as $key => $item)
        @php
            $total_in += $in = $item->stock_type == 'In' ? $item->quantity : 0.00;
            $total_out += $out = $item->stock_type == 'Out' ? $item->quantity : 0.00;
            $total_available += $in - $out;
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ optional($item->product)->name }}</td>
            <td>{{ optional(optional($item->product)->unit)->name }}</td>
            <td class="text-right">{{ $item->date }}</td>
            <td class="text-right">{{ $item->sourceable_type }}</td>
            <td class="text-right">{{ $item->stock_type == 'In' ? $item->quantity : 0.00 }}</td>
            <td class="text-right">{{ $item->stock_type == 'Out' ? $item->quantity : 0.00 }}</td>
            <td class="text-right">{{ 0.00 }}</td>
            <td class="text-right">{{ $total_available }}</td>
        </tr>
    @endforeach
    </tbody>

    <tfoot style="background: #c1c1c1 !important;">
    <tr>
        <th colspan="5"><strong>Total Qty:</strong></th>
        <th class="text-right">
            {{ $total_in }}
        </th>
        <th class="text-right">
            {{ $total_out }}
        </th>
        <th class="text-right">
            {{ 0 }}
        </th>
        <th class="text-right">
            {{ $total_available }}
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



