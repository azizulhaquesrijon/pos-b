<!doctype html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profit Report</title>

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
            Profit Report
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
        <th class="text-center">Description</th>
        <th class="text-center">Quantity</th>
        <th class="text-right">Sale Price</th>
        <th class="text-right">Purchase Price</th>
        <th class="text-right">Profit</th>
    </tr>
    </thead>

    <tbody>

    @php
        $profit_total = 0;
        $sale_total = 0;
        $purchase_total = 0;
        $grand_purchase_total = 0;
        $qty_total = 0;
    @endphp
    @foreach($reports as $key => $item)

        @php
            // $grand_total += $total = $item->payable_amount * $item->product->sell_price;
             $purchase = 0;
             foreach ($item->details as $detail){
                 $purchase += $detail->buy_price * $detail->quantity;

             }
             $profit_total += $item->payable_amount - $purchase;
             $sale_total += $item->payable_amount;
             $grand_purchase_total += $purchase;
             $qty_total += $item->total_qty;
        @endphp
        <tr>
            <th>{{ $loop->iteration }}</th>
            <th class="text-center">{{ 'Sale ('.$item->date.')' }}</th>
            <th class="text-center">{{ $item->total_qty ?? 0 }}</th>
            <th class="text-right">{{ number_format($item->payable_amount,2) }}</th>
            <th class="text-right">
                {{ number_format($purchase,2) }}
            </th>
            <th class="text-right">
                {{ number_format($item->payable_amount - $purchase,2) }}
            </th>


        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <th colspan="5" class="text-right" style="border-bottom: 1px solid transparent;"><strong>Gross Profit:</strong></th>
        <th class="text-right">
            {{ number_format($profit_total,2) }}
        </th>
    </tr>
    <tr>
        <th colspan="5" class="text-right" style="border-bottom: 1px solid transparent;"><strong>Total Expense:</strong></th>
        <th class="text-right">
            {{ '(-) '.number_format($expense,2) ?? 0 }}
        </th>
    </tr>

    <tr style="background: #c1c1c1 !important;">
        <th colspan="5" class="text-right"><strong>Net Profit:</strong></th>
        <th class="text-right">
            {{ number_format($profit_total - $expense,2) }}
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




