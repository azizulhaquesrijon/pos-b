<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>Invoice - 0000{{ $purchase['id'] ?? 0 }}</title>

    <style>
        @media print {
            .page-break {
                display: block;
                page-break-before: always;
            }
        }

        #invoice-POS {
            padding: 0;
            margin: 0 auto;
            left: 0;
            width: 71mm;
            background: #FFF;
            font-family: monospace;
        }

        #invoice-POS ::selection {
            background: #f31544;
            color: #FFF;
        }

        #invoice-POS ::moz-selection {
            background: #f31544;
            color: #FFF;
        }

        #invoice-POS h1 {
            font-size: 1.2em;
            color: #222;
        }

        #invoice-POS h2 {
            font-size: 13px;
        }
        .company-logo {
            /* width: 60px; */
            margin: 0px auto;
            margin-top: 5px;
            text-align: center
        }

        #invoice-POS h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
        }

        #invoice-POS p {
            font-size: 13px;
            color: #666;
            line-height: 1.5em;
        }

        #invoice-POS #top,
        #invoice-POS #mid,
        #invoice-POS #bot {
            /* Targets all id with 'col-' */
            /*border-bottom: 1px solid #EEE;*/
        }

        #invoice-POS #top {
            min-height: 100px;
        }

        #invoice-POS #mid {
            min-height: 80px;
        }

        #invoice-POS #bot {
            min-height: 50px;
        }

        #invoice-POS #top .logo {
            height: 60px;
            width: 60px;
            background-size: 60px 60px;
        }

        #invoice-POS .clientlogo {
            float: left;
            height: 60px;
            width: 60px;
            background-size: 60px 60px;
            border-radius: 50px;
        }

        #invoice-POS .info {
            display: block;
            margin-left: 0;
        }

        #invoice-POS .title {
            float: right;
        }

        #invoice-POS .title p {
            text-align: right;
        }

        #invoice-POS table {
            width: 100%;
            border-collapse: collapse;
        }

        #invoice-POS .tabletitle {
            font-size: .5em;
            background: #EEE;
        }

        #invoice-POS .service {
            border-bottom: 1px solid #EEE;
        }

        #invoice-POS .item {
            width: 24mm;
            padding-left: 2px;
        }

        #invoice-POS .itemtext {
            font-size: 13px;
            /*font-size: .5em;*/
        }

        #invoice-POS #legalcopy {
            margin-top: 5mm;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }
        }

        h2 {
            margin: 3px;
        }

        p {
            margin: 3px;
        }

        body {
            background: #fafafa !important;
        }

    </style>

    <script>
        window.console = window.console || function(t) {};
    </script>
    <script>
        if (document.location.search.match(/type=embed/gi)) {
            window.parent.postMessage("resize", "*");
        }
    </script>
</head>

<body translate="no">
<?php $dokan_user = \App\Models\User::query()->where('id', dokanId())->first() ?>

    <div id="invoice-POS">
        <div class="container">
            <div class="company-logo">
                @if (file_exists($dokan_user->image))
                    <img src="{{ asset($dokan_user->image) }}" alt="Logo" class="logo" style="width: 175px;">
                @else
                    <b>{{ optional($business_settings)->shop_name }}</b>
                @endif
            </div>
        </div>

        <center id="top">
            <div class="info">


                <h1 style="margin-top: -10px;">{{ optional(auth()->user()->businessProfileByUser)->shop_name }}</h1>
                <p style="margin-top: -13px; color: black !important;">
                    {{ optional(auth()->user()->businessProfileByUser)->shop_address }} <br>
                    {{ optional(auth()->user()->businessProfileByUser)->business_mobile ?? auth()->user()->mobile }} </br>
                </p>
                <p style="float: left; font-size: 12px; color: black !important;">Date:
                    {{ \Carbon\Carbon::now()->timezone('Asia/Dhaka')->format('d M, Y, g:i A') }}</p>
                <p style="float: left; font-size: 12px; color: black !important;">Invoice No:
                    0000{{ $purchase->id ?? 0 }}</p>
                <p style="float: left; font-size: 12px; color: black !important;">Supplier:
                    {{ optional($purchase->supplier)->name }}
                </p>
            </div>
            <!--End Info-->
        </center>
        <!--End InvoiceTop-->
        <!--End Invoice Mid-->

        <div id="bot">

            <div id="table">
                <table>
                    <tr class="tabletitle">
                        <td class="item">
                            <h2>Item</h2>
                        </td>
                        <td class="Rate">
                            <h2>Rate</h2>
                        </td>
                        <td class="Hours" style="padding-left: 5px;">
                            <h2>Qty</h2>
                        </td>
                        <td class="Rate text-right">
                            <h2 class="text-right">Amount</h2>
                        </td>
                    </tr>
                    @foreach ($purchase->details as $key => $item)
                        <tr class="service">
                            <td class="tableitem">
                                <p class="itemtext" style="font-size: 12px !important; color: black !important;">
                                    {{ optional($item->product)->name }}
                                    <!-- <span style="font-size: 10px !important;">(9)</span> -->
                                </p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext" style=" color: black !important;">
                                    {{ number_format(optional($item->product)->purchase_price, 2) }}</p>
                            </td>
                            <td class="tableitem" style="padding-left: 5px; color: black !important;">
                                <p class="itemtext" style=" color: black !important;">{{ $item->quantity }}</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext" style="float:right;color: black !important;">
                                    {{ number_format($item->total_amount, 2) }}</p>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <table>
                    <tr class="tabletitle" style="margin: 0px;">
                        <td style="width: 35%"></td>
                        <td class="Rate" style="text-align: right;padding-right: 10px;">
                            <h2>Sub Total</h2>
                        </td>
                        <td class="payment">
                            <h2> {{ number_format($purchase->payable_amount, 2) }}</h2>
                        </td>
                    </tr>
                    <tr class="tabletitle">
                        <td style="width: 35%"></td>
                        <td class="Rate" style="text-align: right;padding-right: 10px;">
                            <h2>Discount</h2>
                        </td>
                        <td class="payment">
                            <h2>{{ number_format($purchase->discount, 2) }}</h2>
                        </td>
                    </tr>
                    <tr class="tabletitle">
                        <td class="Rate" colspan="2" style="text-align: right;padding-right: 10px;">
                            <h2>Invoice Total</h2>
                        </td>
                        <td class="payment">
                            <h2> {{ number_format($purchase->payable_amount, 2) }}</h2>
                        </td>
                    </tr>
                    <tr class="tabletitle">
                        <td class="Rate" colspan="2" style="text-align: right;padding-right: 10px;">
                            <h2>Received</h2>
                        </td>
                        <td class="payment">
                            <h2> {{ number_format($purchase->paid_amount, 2) }}</h2>
                        </td>
                    </tr>
                    <tr class="tabletitle">
                        <td class="Rate" colspan="2" style="text-align: right;padding-right: 10px;">
                            <h2>Due</h2>
                        </td>
                        <td class="payment">
                            <h2> {{ number_format($purchase->due_amount, 2) }}
                            </h2>
                        </td>
                    </tr>
                </table>
            </div>
            <!--End Table-->
            <center id="top">
                <div class="info">
                    <h2 style="font-size: 14px; margin-top: 20px;">Thanks For Coming</h2>
                </div>
                <!--End Info-->
            </center>
        </div>
        <!--End InvoiceBot-->
    </div>
    <div class="no-print" style="text-align: center; margin-bottom: 40px;">
        <button type="button" onclick="window.print()"
            style=" background-color: #4CAF50; border: none;color: white; padding: 11px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;cursor: pointer; border-radius: 5px;">
            Print </button>
        <a onclick="window.close();"
            style=" background-color: red; border: none;color: white; padding: 11px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;cursor: pointer; border-radius: 5px;">Close</a>
        <!-- <a href="{{ url()->previous() }}" style=" background-color: red; border: none;color: white; padding: 11px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;cursor: pointer; border-radius: 5px;">Close</a> -->
    </div>
    <!--End Invoice-->
</body>

<script>
    window.print()
</script>

</html>
