@extends('layouts.master')


@section('title', 'Purchase Details')



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
        .company-logo {
            /* width: 60px; */
            margin: 0px auto;
            margin-top: 5px;
            text-align: center
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
                        <i class="fa fa-plus-circle"></i> Purchase Details
                    </h4>

                    <span class="widget-toolbar">
                        <a href="javascript:void(0)" onclick="print()">
                            <i class="fa fa-print"></i> Print
                        </a>
                    </span>
                    <span class="widget-toolbar">
                        <a href="{{ route('dokani.purchases.index') }}">
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
                                        <?php $dokan_user = \App\Models\User::query()->where('id', dokanId())->first() ?>
                                        <div class="company-logo">
                                            @if (file_exists($dokan_user->image))
                                                <img src="{{ asset($dokan_user->image) }}" alt="Logo" style="width: 175px;">
                                            @else
                                                <b>{{ optional($business_settings)->shop_name }}</b>
                                            @endif
                                        </div>


                                        <!-- company info -->
                                        <div class="company-info text-center">
                                            <h4>{{ optional(optional($purchase->company)->businessProfile)->shop_name }}
                                            </h4>
                                            <p>{{ optional(optional($purchase->company)->businessProfile)->shop_address }}
                                            </p>
                                            <p>{{ optional(optional($purchase->company)->businessProfile)->business_mobile ?? auth()->user()->mobile }}
                                                {{ optional(optional($purchase->company)->businessProfile)->business_email ? ', '.optional(optional($purchase->company)->businessProfile)->business_email : '' }}
                                            </p>
                                        </div>



                                        <!-- panel title -->
                                        <h6 style="width: 100%;text-align: center;margin-top: 15px;">
                                            <b style="font-size: 15px;">
                                                Purchase Invoice
                                            </b>
                                        </h6>


                                        <hr>



                                        <!-- patient info -->
                                        <div class="customerInfo" style="width: 50%;float: left;">
                                            <h5><b><u>Supplier's Information : </u></b></h5>
                                            {{-- <p><b>ID : </b>
                                                {{ optional($purchase->supplier)->id }}</p> --}}
                                            <p class="supplier"><b>Name : </b>
                                                {{ optional($purchase->supplier)->name }}</p>

                                            <p class="supplier"><b>Mobile : </b>
                                                {{ optional($purchase->supplier)->mobile }}</p>

                                            @if(optional($purchase->supplier)->address)
                                                <p class="supplier"><b>Address : </b>
                                                    {{ optional($purchase->supplier)->address }}</p>
                                            @endif
                                        </div>






                                        <!-- invoice info -->
                                        <div class="invoiceInfo" style="width: 50%; float: left;margin-top: 5px;">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td> Invoice No : </td>
                                                    <td>{{ $purchase->invoice_no }}</td>
                                                </tr>
                                                @if($purchase->reference_no)
                                                <tr>
                                                    <td> Reference No : </td>
                                                    <td>{{ $purchase->reference_no }}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td> Purchase Date : </td>
                                                    <td>{{ $purchase->created_at->format('Y-m-d H:i A') }}</td>
                                                </tr>

                                                <tr>
                                                    <td> Account : </td>
                                                    <td>
                                                        @foreach($purchase->multi_accounts as $account)
                                                            {{ $loop->first ? '' : ', ' }}
                                                            {{ optional($account->account)->name  }}
                                                        @endforeach
                                                    </td>
                                                </tr>

                                                @foreach($purchase->multi_accounts as $account)
                                                    @if(optional($account->account)->name == 'Bank')
                                                        <tr>
                                                            <td>Bank Account Info. : </td>
                                                            <td>
                                                                Check No : <strong>{{ $account->check_no }}</strong>, Check Date : <strong>{{ $account->check_date }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
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
                                                    <th style="border: 1px solid #f0d9d9;" width="15%">Barcode</th>
                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important"
                                                        width="15%">Purchase Price</th>
                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important"
                                                        width="8%">Quantity </th>

                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important"
                                                        width="10%">Unit </th>

                                                    <th style="border: 1px solid #f0d9d9; text-align: right !important"
                                                        width="12%" align="right">Total Price (&#x09F3;)</th>
                                                </tr>
                                            </thead>


                                            <tbody>


                                                @foreach ($purchase->details as $item)
                                                    <tr>
                                                        <td style="text-align: center">{{ $loop->iteration }}</td>
                                                        <td>
                                                            {{ optional($item->product)->name }}
                                                        </td>
                                                        <td>
                                                            {{ optional($item->product)->barcode }}
                                                        </td>
                                                        <td style="text-align: center">
                                                            {{ number_format($item->price, 2) }} &#x09F3;
                                                        </td>
                                                        <td style="text-align: center">
                                                            {{ $item->quantity }}
                                                        </td>

                                                        <td style="text-align: center">
                                                            {{ optional(optional($item->product)->unit)->name }}
                                                        </td>

                                                        <td class="text-right">
                                                            {{ number_format($item->price * $item->quantity, 2) }}
                                                            &#x09F3;
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <?php $colspan = 6 ?>
                                                <tr>
                                                    <td colspan="{{ $colspan }}" style="text-align: right; border:none !important">Total
                                                        :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($purchase->payable_amount - $purchase->previous_due + $purchase->discount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                                <tr>
                                                    <td style="border: none !important; font-size: 15px" colspan="5">
                                                        Paid Amount : <span> {{ getInWord($purchase->paid_amount) }}
                                                    .</span>
                                                    </td>
                                                    <td style="text-align: right; border:none !important">
                                                        Discount :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($purchase->discount, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>


                                                <tr>
                                                    <td style="border: none !important; font-size: 15px" colspan="5">
                                                        Due Amount :<span>
                                                        <strong>{{ $purchase->due_amount > 0 ? getInWord($purchase->due_amount) : 'BDT '.$purchase->due_amount }}</strong>
                                                    </span>
                                                    </td>
                                                    <td style="text-align: right; border:none !important">
                                                        Previous Due :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($purchase->previous_due, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td colspan="{{ $colspan }}" style="text-align: right; border:none !important">
                                                        Grand Total :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($purchase->payable_amount - $purchase->discount, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td colspan="{{ $colspan }}" style="text-align: right; border:none !important">
                                                        Paid :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($purchase->paid_amount, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="{{ $colspan }}" style="text-align: right; border:none !important">
                                                        Total Due :
                                                    </td>

                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($purchase->due_amount, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            <h5> Paid Amount : <span> {{ getInWord($purchase->paid_amount) }}--}}
{{--                                                    .</span>--}}
{{--                                            </h5>--}}
{{--                                            <h5> Due Amount :<span>--}}
{{--                                                <strong>{{ $purchase->due_amount > 0 ? getInWord($purchase->due_amount) : 'BDT '.$purchase->due_amount }}</strong>--}}
{{--                                                    </span>--}}
{{--                                            </h5>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
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
                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">{{ optional($purchase->user)->name }}
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

    <script>
        window.print()
    </script>

@endsection
