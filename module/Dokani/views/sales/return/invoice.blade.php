<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sale Return Invoice</title>
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

    <style>
        @media print {
            .d-print-none {
                display: none !important;
            }

            .margin-top {
                margin-top: 20px !important;
            }

            .d-none {
                display: block !important;
            }
        }


        table>thead>tr>th {
            border: 1px solid black;
        }


        table>tbody>tr>td {
            border: 1px solid black;
        }


        .table>thead>tr>th {
            border-bottom: 1px solid black;
        }

        table>thead>tr {
            border: 1px solid black !important;
        }

        table>tbody>tr {
            border: 1px solid black !important;
        }

        .border-none {
            border: none !important;
        }

    </style>
</head>

<body>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 margin-top" style="margin-top: 60px">

            <dl id="dt-list-1" class="dl-horizontal">
                <h5 class="text-center">
                    <strong>{{ optional($saleReturn->company)->name }}</strong>
                </h5>
                <h5 class="text-center">
                    <strong>{{ $saleReturn->date }}</strong>
                </h5>
                <h5 class="text-center">
                    <strong>Branch: {{ optional($saleReturn->branch)->name }}</strong>
                </h5>


                <h5 class="text-center" style="margin-top: 20px">
                    <strong style="font-size: 22px">Sale Return Invoice</strong>
                </h5>

                <br><br>


                {{-- <div class="row">
                    <div class="col-sm-8" style="width: 70%; float: left">
                        <strong>Bill To</strong>
                        <h4>{{ optional($saleReturn->customer)->name }}</h4>
                        <h5>Mobile:{{ optional($saleReturn->customer)->mobile }}</h5>
                        <h5>Email:{{ optional($saleReturn->customer)->email }}</h5>
                        <h5>Address:{{ optional($saleReturn->customer)->address }}</h5>
                    </div>
                    <div class="col-sm-4" style="width: 30%; float: right">

                        <strong class="text-left">Date: {{ $saleReturn->sale_date->format('d/m/Y') }}</strong><br>
                        <strong class="text-left">Invoice No: {{ $saleReturn->invoiceId }}</strong><br>
                    </div>
                </div> --}}




                <table class="table">
                    <thead>
                        <tr>
                            <th width="4%">SL</th>
                            <th width="20%">Item</th>
                            <th width="20%">Item Code</th>
                            <th width="20%">Condition</th>
                            <th width="20%">Price</th>
                            <th width="10%">Qty</th>
                            <th width="6%" class="text-right">Amount</th>
                        </tr>
                    </thead>


                    <tbody style="border: 1px solid black">
                        @php
                            $total = 0;
                        @endphp

                        @foreach ($saleReturn->details as $key => $details)
                            <tr style="border: 1px solid black">
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    {{ $details->product->name ?? '' }}
                                </td>
                                <td>
                                    {{ $details->product->product_code ?? '' }}
                                </td>
                                <td>
                                    {{ $details->condition ?? '' }}
                                </td>

                                <td>
                                    {{ $details->price ?? '' }}
                                </td>
                                <td>{{ $details->quantity }}
                                </td>
                                <td class="text-right">{{ $total += $details->price * $details->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>



                    <tfoot style="font-weight: bold !important;" class="text-right">
                        <tr style="border: 0 !important;">
                            <th class="text-right" colspan="6" style="border: 0 !important; padding: 0 !important;">
                                Subtotal :</th>
                            <th class="text-right" style="border: 0 !important; padding: 0 !important;">
                                {{ number_format($total, 2) }}</th>
                        </tr>

                        </tr>
                    </tfoot>
                </table>

            </dl>
        </div>
    </div>

    <script type="text/javascript">
        window.onafterprint = window.close;
        window.print();
    </script>

</body onclick="window.close();">

</html>
