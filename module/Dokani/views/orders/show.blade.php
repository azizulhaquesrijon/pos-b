@extends('layouts.master')


@section('title', 'Order Details')



@push('style')
    <style>
        #print_body {
            background-color: #fff;
            /* padding: 10px 20px; */
            overflow: hidden;
        }

        .company-info {
            color: #000;
        }

        .company-info h3 {
            font-weight: bold;
            margin-bottom: 0;
        }

        .table {
            box-shadow: none !important;
        }

        .table-bordered>thead>tr>th,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>tbody>tr>td,
        .table-bordered>tfoot>tr>td {
            border: .4px solid #f0d9d9;
            padding: 4.5px;
        }



        .admitted {
            color: #0cb634;
        }

        .company-info p {
            margin-bottom: 2px;
        }

        .patient {
            margin: 0px;
        }

        p {
            margin: 0px 5px 0px;
        }

        . {
            /* background: greenyellow; */
            background: #63bee8;
            box-shadow: none;
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }

        @media print {
            .company-info h4 {
                font-weight: bold;
                margin-bottom: 0;
            }

            .company-info p {
                margin-bottom: 2px;
            }

            .no-print {
                display: none !important;
            }

            .widget-box {
                border: none !important;
                width: 100%;
            }
        }

    </style>
@endpush

@section('content')
    <div class="row">

        <div class="col-xs-12">
            <div class="widget-box">
                <div class="widget-header no-print">
                    <h4 class="widget-title">
                        <i class="fa fa-plus-circle"></i> Order Details
                    </h4>

                    <span class="widget-toolbar">
                        <a href="javascript:void(0)" onclick="print()">
                            <i class="fa fa-print"></i> Print
                        </a>
                    </span>
                    <span class="widget-toolbar">
                        <a href="{{ route('dokani.orders.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i>
                            List
                        </a>
                    </span>
                </div>

                <div class="widget-body">
                    <div class="widget-main">


                        <div class="row">

                            <div id="print_body" class="col-xs-12">
                                <div id="customer_info" style="padding: 0 10px;">
                                    <div class="row">


                                        <!-- company info -->
                                        <div class="company-info text-center">
                                            <h4>{{ optional($business_settings)->shop_name }}
                                            </h4>
                                            <p>{{ optional($business_settings)->shop_address }}
                                            </p>
                                            <p>{{ optional($business_settings)->business_mobile ?? auth()->user()->mobile }},
                                                {{ optional($business_settings)->business_email }}
                                            </p>
                                        </div>



                                        <!-- panel title -->
                                        <h6 style="width: 100%;text-align: center;margin-top: 15px;">
                                            <b style="font-size: 15px;">
                                                Order Invoice
                                            </b>
                                        </h6>


                                        <hr>



                                        <!-- patient info -->
                                        <div class="customerInfo" style="width: 50%;float: left;">
                                            <h6><b><u>Customer's Information : </u></b></h6>
                                            <p><b>ID : </b>
                                                {{ optional($order->customer)->id }}</p>
                                            <p class="supplier"><b>Name : </b>
                                                {{ optional($order->customer)->name }}</p>

                                            <p class="supplier"><b>Mobile : </b>
                                                {{ optional($order->customer)->mobile }}</p>
                                        </div>






                                        <!-- invoice info -->
                                        <div class="invoiceInfo" style="width: 50%; float: left;margin-top: 5px;">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td> Invoice No : </td>
                                                    <td>{{ $order->invoice_no }}</td>
                                                </tr>
                                                <tr>
                                                    <td> Date : </td>
                                                    <td>{{ $order->created_at->format('Y-m-d h:i A') }}</td>
                                                </tr>


                                            </table>
                                        </div>



                                    </div>
                                </div>





                                <br>

                                <div class="invoice-content">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="border: none !important">
                                            <thead>

                                            <tr class="heading">
                                                <th style="border: 1px solid #f0d9d9; text-align: center" width="5%">SL
                                                </th>
                                                <th style="border: 1px solid #f0d9d9;" width="25%">Item</th>
                                                <th style="border: 1px solid #f0d9d9; text-align: center !important"
                                                    width="15%">Order Price</th>
                                                <th style="border: 1px solid #f0d9d9; text-align: center !important"
                                                    width="15%">Quantity </th>

                                                <th style="border: 1px solid #f0d9d9; text-align: right !important"
                                                    width="15%" align="right">Total Price (&#x09F3;)</th>
                                            </tr>
                                            </thead>


                                            <tbody>


                                            @foreach ($order->order_details as $item)
                                                <tr>
                                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                                    <td>
                                                        {{ optional($item->product)->name. ' ' }}
                                                        {{ $item->description ? '('.$item->description.')' : '' }}
                                                    </td>
                                                    <td style="text-align: center">
                                                        {{ number_format($item->price, 2) }} &#x09F3;
                                                    </td>
                                                    <td style="text-align: center">
                                                        {{ $item->quantity.' '.$item->product->unit->name }}
                                                    </td>

                                                    <td class="text-right">
                                                        {{ number_format($item->price * $item->quantity, 2) }}
                                                        &#x09F3;
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td colspan="4" style="text-align: right; border:none !important">Total
                                                    :</td>
                                                <th style="text-align: right; border:none !important">
                                                    {{ number_format($order->payable_amount, 2) }}
                                                    &#x09F3;</th>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="text-align: right; border:none !important">
                                                    Total VAT(+)
                                                    :</td>
                                                <th style="text-align: right; border:none !important">
                                                    {{ number_format($order->total_vat, 2) }}
                                                    &#x09F3;</th>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="text-align: right; border:none !important">
                                                    Delivery Charge(+)
                                                    :</td>
                                                <th style="text-align: right; border:none !important">
                                                    {{ number_format($order->delivery_charge, 2) }}
                                                    &#x09F3;</th>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="text-align: right; border:none !important">
                                                    Discount(-) :</td>
                                                <th style="text-align: right; border:none !important">
                                                    {{ number_format($order->discount, 2) }}
                                                    &#x09F3;
                                                </th>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="text-align: right; border:none !important">
                                                    Total Payable :</td>
                                                <th style="text-align: right; border:none !important">
                                                    {{ number_format($order->payable_amount + $order->delivery_charge - $order->discount + $order->total_vat , 2, '.', '') }}
                                                    &#x09F3;
                                                </th>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="text-align: right; border:none !important">
                                                    Previous Due
                                                    :</td>
                                                <th style="text-align: right; border:none !important">
                                                    {{ number_format($order->previous_due, 2) }}
                                                    &#x09F3;</th>
                                            </tr>

                                            <tr>
                                                <td colspan="4" style="text-align: right; border:none !important">
                                                    Paid :</td>
                                                <th style="text-align: right; border:none !important">
                                                    {{ number_format($order->paid_amount, 2) }}
                                                    &#x09F3;
                                                </th>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="text-align: right; border:none !important">
                                                    Due :</td>
                                                <th style="text-align: right; border:none !important">
                                                    {{ number_format($order->due_amount, 2) }}
                                                    &#x09F3;
                                                </th>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5> Paid Amount : BDT<span> {{ number_format($order->paid_amount, 2) }}
                                                    .</span>
                                            </h5>
                                            <h5> Due Amount : BDT<span>
                                                <strong>{{ getInWord($order->due_amount) }}</strong>
                                                    </span></h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="print-footer"
                                     style="margin-top: 40px;overflow: hidden;width: 100%;padding: 0 10px;">
                                    <div class="sign" style="width: 100%; overflow: hidden;">
                                        <div class="company_sign" style="width: 33%; float: left;">
                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">
                                                &nbsp;</h5>
                                            <h5
                                                style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">
                                                Received By</h5>
                                        </div>
                                        <div class="company_sign" style="width: 33%; float: left;">
                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">
                                                {{ optional($order->user)->name }}
                                            </h5>
                                            <h5
                                                style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">
                                                Prepared By
                                            </h5>
                                        </div>

                                        <div class="company_sign" style="width: 33%; float: left;">
                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">
                                                &nbsp;</h5>
                                            <h5
                                                style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">
                                                Authorized By</h5>
                                        </div>
                                    </div>
                                    <div class="copyright" style="padding: 0px !important;">
                                        <br>
                                        <div class="copyright-section">
                                            <p class="pull-left">NB: This is system generated report.</p>
                                            <p class="design_band pull-right">Powered By: <a href="#"> Smart Software
                                                    LTD.</a></p>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <br>
                            <div id="second_copy" style="width: 100%;overflow: hidden;">
                                <!-- Load First Copy -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')



@endsection

