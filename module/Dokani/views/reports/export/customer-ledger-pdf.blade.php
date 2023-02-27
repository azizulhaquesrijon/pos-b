<!doctype html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Ledger Report</title>

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
        <h4 style="line-height: 0;margin-top:0;  font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            {{ $customer->name }}
        </h4>
        <h5 style="line-height: -5px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            Customer Ledger Report
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
        <th>Source ID</th>
        <th>Description</th>
        <th class="text-right">Debit/Sales</th>
        <th class="text-right">Credit/Receive</th>
        <th class="text-right">Balance</th>
    </tr>
    </thead>

    <tbody>

    @if (request('customer_id'))
        <tr>
            <th colspan="6"><b>Opening Balance</b></th>
            <th class="">{{ $customer->opening_due }}</th>
        </tr>
    @endif

    @php
        $total_debit_amount = 0;
        $total_credit_amount = 0;
        $total_due_amount = $customer->opening_due;
    @endphp

    @foreach ($reports as $key => $item)

        @php
            $collection_paid_amount = $item->source_type == 'Collection' ? optional($item->source)->paid_amount : 0 ;
            $total_due_amount = optional($item->source)->payable_amount - optional($item->source)->paid_amount ;
            //$total_due_amount = $total_due_amount - $collection_paid_amount;
            $total_debit_amount += optional($item->source)->payable_amount;
            $total_credit_amount += optional($item->source)->paid_amount;
        @endphp
        <tr class="body-wi">
            <th>{{ $loop->iteration }}</th>
            <th>{{ $item->date }}</th>
            <th>{{ $item->source_id }}</th>
            <th>{{ $item->source_type }}</th>
            <th class="text-right">{{ number_format(optional($item->source)->payable_amount,2) }}</th>
            <th class="text-right">{{ number_format(optional($item->source)->paid_amount,2) }}</th>
            <th class="text-right">{{ number_format($total_due_amount,2) }}</th>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr style="background: #c6c6c6">
        <th colspan="4"><strong>Total Amount:</strong></th>
        <th class="text-right">
            {{ number_format($total_debit_amount,2) }}
        </th>
        <th class="text-right">
            {{ number_format($total_credit_amount,2) }}
        </th>
        <th class="text-right">
            {{ number_format($total_due_amount,2) }}
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


