@extends('layouts.master')


@section('title', 'Stock Adjustment Details')



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
                        <i class="fa fa-plus-circle"></i> Stock Adjustment Details
                    </h4>

                    <span class="widget-toolbar">
                        <a href="javascript:void(0)" onclick="print()">
                            <i class="fa fa-print"></i> Print
                        </a>
                    </span>
                    <span class="widget-toolbar">
                        <a href="{{ route('dokani.stock-adjustments.index') }}">
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
                                            <h4>{{ optional(optional($stock_adjustment->company)->businessProfile)->shop_name }}
                                            </h4>
                                            <p>{{ optional(optional($stock_adjustment->company)->businessProfile)->shop_address }}
                                            </p>
                                            <p>
                                                {{ optional(optional($stock_adjustment->company)->businessProfile)->business_mobile ?? auth()->user()->mobile }},
                                                {{ optional(optional($stock_adjustment->company)->businessProfile)->business_email ?? auth()->user()->email }}
                                            </p>
                                        </div>

                                        <!-- panel title -->
                                        <h6 style="width: 100%;text-align: center;margin-top: 15px;">
                                            <b style="font-size: 15px;">
                                                Stock Adjustment Invoice
                                            </b>
                                        </h6>

                                        <hr>

                                        <!-- BRANCH INFO -->
                                        <div class="customerInfo" style="width: 50%;float: left;">
                                            <h5><b><u>Branch's Information : </u></b></h5>

                                            <p class="supplier"><b>Name : </b>
                                                {{ optional($stock_adjustment->branch)->name ?? 'Head Office' }}
                                            </p>

                                            <p class="supplier"><b>Mobile : </b>
                                                {{ optional($stock_adjustment->branch)->mobile ?? auth()->user()->mobile }}</p>

                                            @if(optional($stock_adjustment->branch)->address)
                                                <p class="supplier"><b>Address : </b>
                                                    {{ optional($stock_adjustment->branch)->address }}
                                                </p>
                                            @endif
                                        </div>



                                        <!-- invoice info -->
                                        <div class="invoiceInfo" style="width: 50%; float: left;margin-top: 5px;">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td> Invoice No : </td>
                                                    <td>{{ $stock_adjustment->invoice_no }}</td>
                                                </tr>
                                                <tr>
                                                    <td> Date : </td>
                                                    <td>{{ $stock_adjustment->date }}</td>
                                                </tr>
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
                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important" width="10%">Adjustment type</th>
                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important" width="8%">Quantity</th>
                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important" width="10%">Unit Price</th>

                                                    <th style="border: 1px solid #f0d9d9; text-align: center !important" width="10%">Unit </th>

                                                    <th style="border: 1px solid #f0d9d9; text-align: right !important" width="12%" align="right">Total Price (&#x09F3;)</th>
                                                </tr>
                                            </thead>


                                            <tbody>


                                                @foreach ($stock_adjustment->details as $item)
                                                    <tr>
                                                        <td style="text-align: center">{{ $loop->iteration }}</td>
                                                        <td>
                                                            {{ optional($item->product)->name }}
                                                        </td>

                                                        <td style="text-align: center">
                                                            {{ $item->adjustment_type }}
                                                        </td>

                                                        <td style="text-align: center">
                                                            {{ number_format($item->quantity, 2, '.', '') }}
                                                        </td>

                                                        <td style="text-align: center">
                                                            {{ number_format($item->unit_price, 2) }} &#x09F3;
                                                        </td>

                                                        <td style="text-align: center">
                                                            {{ optional(optional($item->product)->unit)->name }}
                                                        </td>

                                                        <td class="text-right">
                                                            {{ number_format($item->sub_total, 2) }}
                                                            &#x09F3;
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <?php $colspan = 6 ?>
                                                <tr>
                                                    <td colspan="{{ $colspan }}" style="text-align: right; border:none !important">Total
                                                        :</td>
                                                    <th style="text-align: right; border:none !important">
                                                        {{ number_format($stock_adjustment->sub_total, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @if (!$stock_adjustment->is_approved && request('is_approved') == 0)
                                    <div class="pull-right">
                                        <form action="{{ route('dokani.stock-adjustments.approve-submit', $stock_adjustment->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="is_approved" value="1">
                                            <div class="col-sm-4">
                                                <button class="btn btn-sm btn-success" type="submit">
                                                    <i class="fa fa-save"></i> Approve
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                            </div>
                            <br>
                            <hr>
                            <br>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')


@endsection
