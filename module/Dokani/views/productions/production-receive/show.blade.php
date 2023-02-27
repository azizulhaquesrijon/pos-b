@extends('layouts.master')


@section('title', 'Productions Receive Details')



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

        .watermarked {
            position: relative;
        }

        .watermarked:after {
            content: "";
            display: block;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 44%;
            left: 30%;
            background-image: url("{{ asset('assets/images/paid.png') }}");
            background-size: 20% 31%;
            background-position: 30px 30px;
            background-repeat: no-repeat;
            opacity: 0.4;

        }


        input[type="checkbox"]:checked::before {
            transform: scale(1);
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
                        <i class="fa fa-plus-circle"></i> Productions Receive Details
                    </h4>

                    <span class="widget-toolbar">
                        <a href="javascript:void(0)" onclick="print()">
                            <i class="fa fa-print"></i> Print
                        </a>
                    </span>
                    <span class="widget-toolbar">
                        @if (hasPermission('dokani.production-receives.index', $slugs))
                            <a href="{{ route('dokani.production-receives.index') }}" class="">
                                <i class="fa fa-list"></i> Porduction List
                            </a>
                        @endif
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
                                            <h4>{{ optional($business_settings)->shop_name }}</h4>
                                            <p>{{ optional($business_settings)->shop_address }}</p>
                                            <p>{{ optional($business_settings)->business_mobile ?? auth()->user()->mobile }}
                                                {{ optional($business_settings)->business_email ? ', ' . optional($business_settings)->business_email : '' }}
                                            </p>
                                        </div>



                                        <!-- panel title -->
                                        <h6 style="width: 100%;text-align: center;margin-top: 15px;">
                                            <b style="font-size: 15px;">
                                                Production Details
                                            </b>
                                        </h6>


                                        <hr>

                                        <!-- invoice info -->
                                        <div class="invoiceInfo" style="width: 50%; float: left;margin-top: 5px;">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td> Invoice Id : </td>
                                                    <td>{{ $production_receive->invoice_id }}</td>
                                                </tr>
                                                <tr>
                                                    <td> Date : </td>
                                                    <td>{{ fdate($production_receive->date, 'Y-m-d') }}</td>
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
                                                    <th style="border: 1px solid #f0d9d9; text-align: center"
                                                        width="5%">SL
                                                    </th>
                                                    <th style="border: 1px solid #f0d9d9;" width="25%">Product Name</th>

                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important"
                                                        width="8%">Quantity </th>


                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important"
                                                        width="8%">Unit Price </th>

                                                    <th style="border: 1px solid #f0d9d9; text-align: right !important"
                                                        width="15%" align="right">Comment</th>
                                                </tr>
                                            </thead>


                                            <tbody>


                                                @foreach ($production_receive->production_receive_details as $item)
                                                    <tr>
                                                        <td style="text-align: center">{{ $loop->iteration }}</td>
                                                        <td>
                                                            {{ optional($item->product)->name . ' ' }}
                                                        </td>
                                                        <td style="text-align: center">
                                                            {{ $item->quantity }} &#x09F3;
                                                        </td>
                                                        <td style="text-align: center">
                                                            {{ number_format($item->unit_price, 2, '.', '') }}
                                                        </td>
                                                        <td style="text-align: center">
                                                            {{ $item->comment }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
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
                                                {{ optional($production_receive->user)->name }}
                                                &nbsp;</h5>
                                            <h5
                                                style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">
                                                Prepared By</h5>
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
                                            <p class="design_band pull-right">Powered By: <a href="#"> Smart
                                                    Software
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

    <script>
        // window.print()
    </script>

@endsection
