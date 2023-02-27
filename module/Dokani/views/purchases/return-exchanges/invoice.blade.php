<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ optional($purchaseReturnExchange->purchase)->invoice_no }}</title>




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
                }
            }

            body {
                background: #efefef;
                font-family: 'Fira Code';
                font-size: 15px;
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

            .col-6 {
                float: left;
                width: 50%;
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
                width: 210mm;
                height: 297mm;
                margin: 0 auto;
                margin-top: 50px;
                margin-bottom: 50px;
                padding: 20px;
            }

            .container {
                padding: 5px 50px;
                height: 100%;
                position: relative;
            }

            .company-logo {
                width: 180px;
                margin: 0px auto;
            }

            .logo {
                width: 100%;
            }

            .company-info {
                font-size: 15px;
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
                width: 130px;
                height: 2px;
                background: #18181b;
                left: 0;
                top: 50%;
                position: absolute;
            }

            .receipt-heading:after {
                content: "";
                display: block;
                width: 130px;
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
                text-align: right;
            }

            table {
                width: 100%;
            }

            table, th {
                border-top: 1px solid black;
                border-bottom: 1px solid black;
                border-collapse: collapse;
            }

            th, td {
                padding: 10px;
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
                padding: 10px;
                margin-top: 10px;
                margin-bottom: 10px;
                text-align: right;
            }

            .footer {
                position: absolute;
                right: 0;
                bottom: 0 !important;
                left: 0;
                padding: 1rem;
                text-align: center;
            }
        </style>
    </head>

    <body>
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




                <p class="receipt-heading"> Supplier Receipt </p>




                <div class="invoice-info">
                    <div class="row">
                        <div class="col-6 invoice-no">
                            Invoice No: {{ $purchaseReturnExchange->invoice_no }}
                            <p>Supplier Phone: {{ optional($purchaseReturnExchange->supplier)->mobile }}</p>
                        </div>
                        <div class="col-6 date">
                            @if (optional($purchaseReturnExchange->purchase)->reference_no)
                            Purchase Reference: {{ optional($purchaseReturnExchange->purchase)->reference_no }}
                            <br>
                            @endif
                            Purchase Invoice: {{ optional($purchaseReturnExchange->purchase)->invoice_no }}
                            <br>
                            Date: {{ $purchaseReturnExchange->created_at->format('d M, Y h:i A') }}
                        </div>
                    </div>
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
                            @foreach ($purchaseReturnExchange->purchaseReturns as $purchaseReturn)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        {{ $purchaseReturn->product->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $purchaseReturn->product->unit->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $purchaseReturn->quantity }}
                                    </td>
                                    <td class="text-center">
                                        {{ $purchaseReturn->purchase_price }}
                                    </td>
                                     <td class="text-center">
                                        {{ $purchaseReturn->subtotal }}
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>

                        @if ($purchaseReturnExchange->purchaseExchanges)
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
                        @foreach ($purchaseReturnExchange->purchaseExchanges as $purchaseExchanges)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    {{ $purchaseExchanges->product->name }}
                                </td>
                                <td class="text-center">
                                    {{ $purchaseExchanges->product->unit->name }}
                                </td>
                                <td class="text-center">
                                    {{ $purchaseExchanges->quantity }}
                                </td>
                                <td class="text-center">
                                    {{ $purchaseExchanges->price }}
                                </td>
                                <td class="text-center">
                                    {{ $purchaseExchanges->total_amount }}
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                    @endif

                    </table>


                    <div class="invoice-price">
                        <div class="row">
                            <div class="col-10">
                                <p>Subtotal: </p>
                            </div>
                            <div class="col-2">
                                <p>{{ number_format($purchaseReturnExchange->subtotal, 2, '.', '') }}</p>
                            </div>
                        </div>
                        ------------
                        <div class="row">
                            <div class="col-10">
                                <p>Discount:</p>
                            </div>
                            <div class="col-2">
                                @php $discount = $purchaseReturnExchange->total_return_discount_amount - $purchaseReturnExchange->total_exchange_discount_amount; @endphp
                                <p><span style="font-size: 7px;">(-)</span> {{ number_format($discount, 2, '.', '') }}</p>
                            </div>
                        </div>
                        ------------
                        <div class="row">
                            <div class="col-10">
                                <p>Rounding:</p>
                            </div>
                            <div class="col-2">
                                <p><span style="font-size: 7px">(+/-)</span> {{ number_format($purchaseReturnExchange->rounding, 2, '.', '') }}</p>
                            </div>
                        </div>
                        ------------
                        <div class="row">
                            <div class="col-10">
                                <p>Total Payable:</p>
                            </div>
                            <div class="col-2">
                                <p>{{ number_format($purchaseReturnExchange->payable_amount, 2, '.', '') }}</p>
                            </div>
                        </div>
                        ------------
                        <div class="row">
                            <div class="col-10">
                                <p>Paid Amount: </p>
                                <p>Change:</p>
                            </div>
                            <div class="col-2">
                                <p>{{ number_format($purchaseReturnExchange->paid_amount, 2, '.', '') }}</p>
                                <p>{{ number_format($purchaseReturnExchange->change_amount, 2, '.', '') }}</p>
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

        <script type="text/javascript">
            // window.onafterprint = window.close;
            window.print();
        </script>
    </body>

</html>
