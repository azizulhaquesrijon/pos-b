<!doctype html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventory Report</title>

    <style>
        body {
            font-family: 'Arial,sans-serif, nikosh';
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

        th{
            font-weight: normal;
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
        <h4 style="line-height: -5px">
            Inventory Report
        </h4>
        <h4 style="line-height: 0;margin-top:0;">
            Branch : {{ dokanBranches()[request('branch_id')] ?? 'Head Office' }}
        </h4>
        <h6 style="line-height: -5px">
            Date: {{ fdate(request('date', now())) }}
        </h6>
    </div>
</htmlpageheader>



<table class="table table-striped table-bordered table-hover" style="width: 100%;" border="1">
    <thead>
    <tr style="background: #c1c1c1 !important;">
        <th>Sl</th>
        <th>P. Name</th>
        <th>P. Barcode</th>
        <th>P. Unit</th>
        <th class="text-right"> Opening Qty</th>
        <th class="text-right">Purchased Qty</th>
        <th class="text-right">Sold Qty</th>
        <th class="text-right">Sold Return Qty</th>
        <th class="text-right">Purchased Return Qty</th>
        <th class="text-right">Available Qty</th>
        <th class="text-right">Product Cost</th>
        <th class="text-right">Value</th>
    </tr>
    </thead>

    <tbody>

    @php
        $total_value = 0;
        $total_opening_qty = 0;
        $total_purchase_qty = 0;
        $total_purchase_return_qty = 0;
        $total_sold_qty = 0;
        $total_sold_return_qty = 0;
        $total_available_qty = 0;
        $total_cost = 0;
        $grand_total_cost = 0;

    @endphp
    @foreach($reports as $key => $item)

        @php
            $total_value += $total = $item->purchase_price * $item->available_qty;
            $total_opening_qty += $item->opening_qty;
            $total_purchase_qty += $item->purchased_qty;
            $total_purchase_return_qty += $item->purchase_return_qty;
            $total_sold_qty += $item->sold_qty;
            $total_sold_return_qty += $item->sold_return_qty;
            $total_available_qty += $item->available_qty;
            $total_cost += $item->purchase_price;
        @endphp
        <tr>
            <th>{{ $loop->iteration }}</th>
            <th>{{ $item->name }}</th>
            <th>{{ $item->barcode }}</th>
            <th>{{ optional($item->unit)->name }}</th>
            <th class="text-right">{{ $item->opening_qty }}</th>
            <th class="text-right">{{ $item->purchased_qty }}</th>
            <th class="text-right">{{ $item->sold_qty }}</th>
            <th class="text-right">{{ $item->sold_return_qty }}</th>
            <th class="text-right">{{ $item->purchase_return_qty }}</th>
            <th class="text-right">{{ $item->available_qty }}</th>
            <th class="text-right">{{ number_format($item->purchase_price) }}
            </th>
            <th class="text-right">
                {{ number_format($total) }}
            </th>

        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr style="background: #c1c1c1 !important;">
        <th colspan="4"><strong>Total:</strong></th>
        <th class="text-right">{{ $total_opening_qty }}</th>
        <th class="text-right">{{ $total_purchase_qty }}</th>
        <th class="text-right">{{ $total_sold_qty }}</th>
        <th class="text-right">{{ $total_sold_return_qty }}</th>
        <th class="text-right">{{ $total_purchase_return_qty }}</th>
        <th class="text-right">{{ $total_available_qty }}</th>
        <th class="text-right">{{ $total_cost }}</th>
        <th class="text-right">{{ $total_value }}</th>
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


