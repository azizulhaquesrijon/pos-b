<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ optional($saleReturnExchange->sale)->invoice_no }}</title>




        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            @media print {
                #invoice {
                    margin-top: 0 !important;
                    margin-bottom: 0 !important;
                    padding-top: 2px !important;
                }

                .company-logo {
                    margin-top: 0 !important;
                    padding-top: 0 !important;
                }


                .no-print,
                .no-print * {
                    display: none !important;
                }
            }

            body {
                background: #efefef;
                font-family: 'Fira Code';
                font-size: .6rem;
                font-weight: 600;
            }

            .row:after {
                content: "";
                display: table;
                clear: both;
            }

            .col-2 {
                float: left;
                width: 16.6666666667%;
            }

            .col-3 {
                float: left;
                width: 25%;
            }

            .col-4 {
                float: left;
                width: 33.3333333333%;
            }

            .col-6 {
                float: left;
                width: 50%;
            }

            .col-8 {
                float: left;
                width: 66.6666666666%;
            }

            .col-9 {
                float: left;
                width: 75%;
            }

            .col-10 {
                float: left;
                width: 83.3333333333%;
            }

            #invoice {
                background: #ffffff;
                width: 302.36px;
                min-height: 100px;
                margin: 0 auto;
                margin-top: 10px;
                margin-bottom: 50px;
                padding: 5px;
            }

            .container {
                height: 100%;
            }

            .company-logo {
                width: 60px;
                margin: 0px auto;
                margin-top: 5px;
            }

            .logo {
                width: 100%;
            }

            .company-info {
                font-size: .6rem;
                font-weight: 700;
                text-align: center;
            }

            .receipt-heading {
                text-align: center;
                font-weight: 700;
                margin: 0 auto;
                margin-top: 10px;
                max-width: 450px;
                position: relative;
            }

            .receipt-heading:before {
                content: "";
                display: block;
                width: 70px;
                height: 2px;
                background: #18181b;
                left: 0;
                top: 50%;
                position: absolute;
            }

            .receipt-heading:after {
                content: "";
                display: block;
                width: 70px;
                height: 2px;
                background: #18181b;
                right: 0;
                top: 50%;
                position: absolute;
            }

            .invoice-info {
                margin-top: 10px;
                margin-bottom: 10px;
            }

            .invoice-info .date {
                font-size: .5rem;
                font-weight: bold;
                text-align: right;
            }

            table {
                width: 100%;
            }

            table,
            th {
                border-top: 1px solid black;
                border-bottom: 1px solid black;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 5px;
            }

            .text-center {
                text-align: center;
            }

            .text-left {
                text-align: left;
            }

            .text-right {
                text-align: right;
            }

            .invoice-price {
                padding: 5px;
                margin-top: 10px;
                margin-bottom: 10px;
                text-align: right;
            }

            .footer {
                margin-top: 10px;
                font-size: .5rem;
                padding: 1rem;
                text-align: center;
            }


            .no-print {
                text-align: center;
                margin-top: 10px;
            }

            .no-print .btn {
                border: none;
                color: white;
                padding: 11px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                cursor: pointer;
                border-radius: 5px;
            }

            .no-print .btn.btn-print {
                background-color: #4CAF50;
            }

            .no-print .btn.btn-back {
                background-color: rgb(250, 0, 0);
            }

        </style>
    </head>

    <body>


        <div class="no-print">
            <a href="javascript:void(0)" onclick="window.print()" class="btn btn-print">Print</a>
            <a href="{{ route('dokani.sale-return-exchanges.index') }}" class="btn btn-back">Back</a>
        </div>

        <section id="invoice">
            <div class="container">
                <?php $dokan_user = \App\Models\User::query()->where('id', dokanId())->first() ?>
                <div class="company-logo">
                    @if (file_exists($dokan_user->image))
                        <img src="{{ asset($dokan_user->image) }}" alt="Logo" class="logo">
                    @else
                        <b>{{ optional($business_settings)->shop_name }}</b>
                    @endif
                </div>




                    <div class="company-info">
                        <p>{{ optional($business_settings)->shop_name }}</p>
                        <p>{{ optional($business_settings)->shop_address }}</p>
                        <p>{{ optional($business_settings)->business_mobile ?? auth()->user()->mobile }}</p>
                    </div>




                <p class="receipt-heading"> Customer Receipt </p>




                <div class="invoice-info">
                    <div class="row">
                        <div class="col-6 invoice-no">
                            Invoice No: {{ optional($saleReturnExchange->sale)->invoice_no }}
                        </div>
                        <div class="col-6 date">
                            Date: {{ $saleReturnExchange->created_at->format('d M, Y h:i A') }}
                        </div>
                    </div>
                    <p>Customer Phone: {{ optional($saleReturnExchange->customer)->mobile }}</p>
                </div>




                <div class="product-info">
                    <table>

                        <thead>
                            <tr style="background-color:#FFF7F7">
                                <th colspan="6">Return</th>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th class="text-left">Item Details</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($saleReturnExchange->saleReturnExchangeDetails as $saleReturnExchangeDetail)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        {{ optional(optional($saleReturnExchangeDetail->saleReturn)->product)->name }}{{ optional(optional($saleReturnExchangeDetail->saleReturn)->fabric)->name ? '-' . optional(optional($saleReturnExchangeDetail->saleReturn)->fabric)->name : '' }}{{ optional(optional($saleReturnExchangeDetail->saleReturn)->color)->name ? '-' . optional(optional($saleReturnExchangeDetail->saleReturn)->color)->name : '' }}{{ optional(optional($saleReturnExchangeDetail->saleReturn)->size)->name ? '-' . optional(optional($saleReturnExchangeDetail->saleReturn)->size)->name : '' }}
                                    </td>
                                    <td class="text-center">{{ optional(optional(optional($saleReturnExchangeDetail->saleReturn)->product)->unit)->name }}</td>
                                    <td class="text-center">{{ number_format(optional($saleReturnExchangeDetail->saleReturn)->quantity, 2, '.', '') }}</td>
                                    <td class="text-center">{{ number_format(optional($saleReturnExchangeDetail->saleReturn)->sale_price, 2, '.', '') }}</td>
                                    <td class="text-right">{{ number_format(optional($saleReturnExchangeDetail->saleReturn)->subtotal, 2, '.', '') }}</td>
                                </tr>
                            @endforeach
                        </tbody>



                        <thead>
                            <tr style="background-color:#F5FFF5">
                                <th colspan="6">Exchange</th>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th class="text-left">Item Details</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($saleReturnExchange->saleReturnExchangeDetails as $saleReturnExchangeDetail)
                                @if ($saleReturnExchangeDetail->saleExchange )
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            {{ optional(optional($saleReturnExchangeDetail->saleExchange)->product)->name }}{{ optional(optional($saleReturnExchangeDetail->saleExchange)->fabric)->name ? '-' . optional(optional($saleReturnExchangeDetail->saleExchange)->fabric)->name : '' }}{{ optional(optional($saleReturnExchangeDetail->saleExchange)->color)->name ? '-' . optional(optional($saleReturnExchangeDetail->saleExchange)->color)->name : '' }}{{ optional(optional($saleReturnExchangeDetail->saleExchange)->size)->name ? '-' . optional(optional($saleReturnExchangeDetail->saleExchange)->size)->name : '' }}
                                        </td>
                                        <td class="text-center">{{ optional(optional(optional($saleReturnExchangeDetail->saleExchange)->product)->unit)->name }}</td>
                                        <td class="text-center">{{ number_format(optional($saleReturnExchangeDetail->saleExchange)->quantity, 2, '.', '') }}</td>
                                        <td class="text-center">{{ number_format(optional($saleReturnExchangeDetail->saleExchange)->sale_price, 2, '.', '') }}</td>
                                        <td class="text-right">{{ number_format(optional($saleReturnExchangeDetail->saleExchange)->subtotal, 2, '.', '') }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>

                    </table>


                    <div class="invoice-price">
                        <div class="row">
                            <div class="col-9">
                                <p>Subtotal: </p>
                            </div>
                            <div class="col-3">
                                <p>{{ number_format($saleReturnExchange->subtotal, 2, '.', '') }}</p>
                            </div>
                        </div>
                        ------------
                        <div class="row">
                            <div class="col-9">
                                <p>Discount:</p>
                            </div>
                            <div class="col-3">
                                @php $discount = $saleReturnExchange->total_return_discount_amount - $saleReturnExchange->total_exchange_discount_amount; @endphp
                                <p><span style="font-size: 7px;">(-)</span> {{ number_format($discount, 2, '.', '') }}</p>
                            </div>
                        </div>
                        ------------
                        <div class="row">
                            <div class="col-9">
                                <p>Rounding:</p>
                            </div>
                            <div class="col-3">
                                <p><span style="font-size: 7px">(+/-)</span> {{ number_format($saleReturnExchange->rounding, 2, '.', '') }}</p>
                            </div>
                        </div>
                        ------------
                        <div class="row">
                            <div class="col-9">
                                <p>Total Payable:</p>
                            </div>
                            <div class="col-3">
                                <p>{{ number_format($saleReturnExchange->payable_amount, 2, '.', '') }}</p>
                            </div>
                        </div>
                        ------------
                        <div class="row">
                            <div class="col-9">
                                <p>Paid Amount: </p>
                                <p>Change:</p>
                            </div>
                            <div class="col-3">
                                <p>{{ number_format($saleReturnExchange->paid_amount, 2, '.', '') }}</p>
                                <p>{{ number_format($saleReturnExchange->change_amount, 2, '.', '') }}</p>
                            </div>
                        </div>
                    </div>

                </div>




                <div class="footer">
                    <p>Thanks For Purchase with {{ optional(auth()->user()->businessProfile)->shop_name }}</p>
                    <p>For any queries complaints or feedback.</p>
                    <p>Please call {{ auth()->user()->mobile }}</p>
                </div>
            </div>
        </section>


        <script>
            window.print()
        </script>
    </body>

</html>
