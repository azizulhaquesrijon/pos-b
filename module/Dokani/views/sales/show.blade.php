@extends('layouts.master')


@section('title', 'Sale Details')



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
                        <i class="fa fa-plus-circle"></i> Sale Details
                    </h4>

                    <span class="widget-toolbar">
                        <a href="javascript:void(0)" onclick="print()">
                            <i class="fa fa-print"></i> Print
                        </a>
                    </span>
                    <span class="widget-toolbar">
                        <a href="{{ route('dokani.sales.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i>
                            List
                        </a>
                    </span>
                </div>

                <div class="widget-body">
                    <div class="widget-main">


                        <div class="row">

                            <div id="print_body" class="col-xs-12 {{ $sale->due_amount == 0 ? 'watermarked' : '' }} ">
                                <div id="customer_info" style="padding: 0 10px;">
                                    <div class="row">


                                        <!-- company info -->
                                    @if(auth()->user()->type == 'owner')
                                        <div class="company-info text-center">
                                            <h4>{{ optional($business_settings)->shop_name }}</h4>
                                            <p>{{ optional($business_settings)->shop_address }}</p>
                                            <p>{{ optional($business_settings)->business_mobile ?? auth()->user()->mobile }}
                                                {{ optional($business_settings)->business_email ? ', '.optional($business_settings)->business_email : '' }}
                                            </p>
                                        </div>
                                    @else
                                        <div class="company-info text-center">
                                            <h4>{{ optional($business_settings)->shop_name }}</h4>
                                            <p>{{ $branch->address }}</p>
                                            <p>Phone: {{ $branch->phone_number ?? auth()->user()->mobile }}
                                                | Email: {{ $branch->email }}
                                            </p>
                                        </div>
                                    @endif



                                        <!-- panel title -->
                                        <h6 style="width: 100%;text-align: center;margin-top: 15px;">
                                            <b style="font-size: 15px;">
                                                Sale Invoice
                                            </b>
                                        </h6>


                                        <hr>



                                        <!-- patient info -->
                                        <div class="customerInfo" style="width: 50%;float: left;">
                                            <h6><b><u>Customer's Information : </u></b></h6>

                                            <p class="supplier"><b>Name : </b>
                                                {{ optional($sale->customer)->name }}</p>

                                            <p class="supplier"><b>Mobile : </b>
                                                {{ optional($sale->customer)->mobile }}</p>
                                            @if(optional($sale->customer)->address)
                                                <p class="supplier"><b>Address : </b>
                                                    {{ optional($sale->customer)->address }}</p>
                                            @endif
                                        </div>






                                        <!-- invoice info -->
                                        <div class="invoiceInfo" style="width: 50%; float: left;margin-top: 5px;">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td> Invoice No : </td>
                                                    <td>{{ $sale->invoice_no }}</td>
                                                </tr>
                                                <tr>
                                                    <td> Date : </td>
                                                    <td>{{ $sale->created_at->format('Y-m-d h:i A') }}</td>
                                                </tr>

                                                <tr>
                                                    <td> Account : </td>
                                                    <td>
                                                        @foreach($sale->multi_accounts as $account)
                                                            {{ $loop->first ? '' : ', ' }}
                                                            {{ optional($account->account)->name  }}
                                                        @endforeach
                                                    </td>
                                                </tr>

                                                @foreach($sale->multi_accounts as $account)
                                                    @if(optional($account->account)->name == 'Bank')
                                                        <tr>
                                                            <td>Bank Account Info. : </td>
                                                            <td>
                                                                Check No : <strong>{{ $account->check_no }}</strong>, Check Date : <strong>{{ $account->check_date }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach


                                                @if (optional($sale->courier)->name)
                                                <tr>
                                                    <td> Courier Name : </td>
                                                    <td>{{ optional($sale->courier)->name }}</td>
                                                </tr>
                                                @endif


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
                                                        width="15%">Sale Price</th>
                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important"
                                                        width="8%">Quantity </th>

                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important"
                                                        width="7%">Unit </th>
                                                    @if(optional(auth()->user()->businessProfile)->is_category_show == 1)
                                                        <th>Category</th>
                                                    @endif

                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important"
                                                        width="15%">Discount </th>

                                                    <th style="border: 1px solid #f0d9d9; text-align: right !important"
                                                        width="15%" align="right">Total Price (&#x09F3;)</th>
                                                </tr>
                                            </thead>


                                            <tbody>


                                                @foreach ($sale->details as $item)
                                                    <tr>
                                                        <td style="text-align: center">{{ $loop->iteration }}</td>
                                                        <td>
                                                            {{ optional($item->product)->name. ' ' }}
                                                            {{ optional($item->product)->description ? '('.optional($item->product)->description.')' : '' }}
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

                                                        @if(optional(auth()->user()->businessProfile)->is_category_show == 1)
                                                            <td style="text-align: center">
                                                                {{ optional(optional($item->product)->category)->name }}
                                                            </td>
                                                        @endif

                                                        <td style="text-align: center">
                                                            {{ $item->discount.' '.'৳' }}
                                                        </td>

                                                        <td class="text-right">
                                                            {{-- {{ number_format($item->price * $item->quantity - $item->discount * $item->quantity, 2) }} --}}
                                                            {{ number_format($item->total_amount, 2) }}
                                                            &#x09F3;
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <?php optional(auth()->user()->businessProfile)->is_category_show == 1 ? $colspan = 7 : $colspan = 6 ?>
                                                <tr>
                                                    <td colspan="{{ $colspan }}" style="text-align: right; border:none !important">Total
                                                        :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($sale->payable_amount - $sale->total_vat - $sale->previous_due, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                                <tr>
                                                    <td style="border: none !important; font-size: 15px" colspan="{{ $colspan - 1 }}">
                                                        @if($sale->note)
                                                            Note : <span> {{ $sale->note }}</span>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: right; border:none !important">
                                                        Total VAT(+)
                                                        :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($sale->total_vat, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                                <tr>
                                                    <td style="text-align: right; border:none !important" colspan="{{ $colspan }}" >
                                                        Delivery Charge(+)
                                                        :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($sale->delivery_charge, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                                <tr>
                                                    <td style="text-align: right; border:none !important" colspan="{{ $colspan }}">
                                                        Discount(-) :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($sale->discount, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td colspan="{{ $colspan }}" style="text-align: right; border:none !important">
                                                        Previous Due(+)
                                                        :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($sale->previous_due, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                                <tr>
                                                    <td colspan="{{ $colspan }}" style="text-align: right; border:none !important">
                                                        Total Payable :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{-- {{ number_format($sale->payable_amount + $sale->delivery_charge - $sale->discount , 2, '.', '') }} --}}
                                                        {{ number_format($sale->payable_amount, 2, '.', '') }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td style="border: none !important; font-size: 15px" colspan="{{ $colspan - 1 }}">
                                                        Paid Amount : <span> {{ getInWord($sale->paid_amount) }}
                                                    .</span>
                                                    </td>
                                                    <td style="text-align: right; border:none !important">
                                                        Paid :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($sale->paid_amount, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td style="border: none !important; font-size: 15px" colspan="{{ $colspan - 1 }}">

                                                    </td>
                                                    <td style="text-align: right; border:none !important">
                                                        Change Amount:</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($sale->change_amount, 2,'.','') }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td style="border: none !important; font-size: 15px" colspan="{{ $colspan - 1 }}">
                                                        Due Amount :<span>
                                                    <strong>{{ $sale->due_amount > 0 ? getInWord($sale->due_amount) : '' }}</strong>
                                                    </span>
                                                    </td>
                                                    <td style="text-align: right; border:none !important">
                                                        Due Amount :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($sale->due_amount, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td colspan="{{ $colspan }}" style="text-align: right; border:none !important">
                                                        Method :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        <code>{{ $sale->is_cod == 1 ? 'CASH ON DELIVERY' : 'CASH ON HAND' }}</code>
                                                    </th>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-12">--}}
{{--                                            @if($sale->note)--}}
{{--                                            <h5> Note : <span> {{ $sale->note }}</span>--}}
{{--                                                @endif--}}
{{--                                            <h5> Paid Amount :<span> {{ getInWord($sale->paid_amount) }}</span>--}}
{{--                                            </h5>--}}
{{--                                            @if ($sale->due_amount > 0)--}}
{{--                                               <h5> Due Amount :<span>--}}
{{--                                                    <strong>{{ getInWord($sale->due_amount) }}</strong>--}}
{{--                                                    </span></h5>--}}
{{--                                            @endif--}}
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
                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">{{ optional($sale->user)->name }}
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
