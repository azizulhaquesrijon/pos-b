@extends('layouts.master')
@section('title', 'Sale Edit')
@section('page-header')
    <i class="fa fa-plus"></i> Sale Edit
@stop
@push('style')


    <style>
        .list-group>p {
            color: black
        }

        .single-product{
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            height: 130px;

        }

        .single-product-li{
            border: 1px solid #4b500e;
            padding: 5px;

        }

        .single-product:hover{
            border:1px solid #212529;
        }

        .single-product-li:hover{
            border:1px solid #212529;
        }

        .loader {
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #3498db;
            width: 16px;
            height: 16px;
            -webkit-animation: spin 2s linear infinite;
            /* Safari */
            animation: spin 2s linear infinite;
        }


        [type="checkbox"]:checked,
        [type="checkbox"]:not(:checked) {
            position: absolute;
            left: -9999px;
        }
        [type="checkbox"]:checked + label,
        [type="checkbox"]:not(:checked) + label
        {
            position: relative;
            padding-left: 28px;
            cursor: pointer;
            line-height: 20px;
            display: inline-block;
            color: #666;
        }
        [type="checkbox"]:checked + label:before,
        [type="checkbox"]:not(:checked) + label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 23px;
            height: 22px;
            border: 1px solid rgb(129, 129, 129);
            background: #fff;
        }
        [type="checkbox"]:checked + label:after,
        [type="checkbox"]:not(:checked) + label:after {
            content: '';
            width: 15px;
            height: 14px;
            background: #00a69c;
            position: absolute;
            top: 4px;
            left: 4px;
            -webkit-transition: all 0.2s ease;
            transition: all 0.2s ease;
        }
        [type="checkbox"]:not(:checked) + label:after {
            opacity: 0;
            -webkit-transform: scale(0);
            transform: scale(0);
        }
        [type="checkbox"]:checked + label:after {
            opacity: 1;
            -webkit-transform: scale(1);
            transform: scale(1);
        }


        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .search-product {
            display: block;
            position: relative;
            overflow-x: hidden;
            overflow-y: scroll;
            max-height: 240px;
            margin: 0 4px 4px 0;
            padding: 0 0 0 4px;
            width: 100%;
        }

        table tbody {
            display: block;
            max-height: 300px;
            overflow-y: scroll;
        }

        table thead, table tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #ffffff;
        }

        ::-webkit-scrollbar-thumb {
            background: #c2d9ec;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #b9d9f4;
        }

        ::-webkit-scrollbar {
            height: 4px;
            width: 6px;
            border: 1px solid #f1f1f1;
        }


        .widget-header {
            padding: 15px !important;
        }

        .input-group-addon {
            background-color: #dfdfdf !important;
            color: black !important;
            font-weight: 500 !important
        }




    </style>
@endpush

@section('content')


    @include('partials._customer-form')
    <div class="main-content-inner">

        <div class="page-content">

            <!-- DYNAIC CONTENT FROM VIEWS -->
            <div class="page-header">
                <h4 class="page-title"><i class="fa fa-edit"></i> Sale Edit</h4>
                <div class="btn-group">

                </div>
            </div>

            <form action="{{ route('dokani.sales.update',$sale->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="source" value="Website">

                <div class="row mb-1" style="background: rgb(245 245 245); border-bottom: 2px solid; padding: 10px">

                    <div class="col-md-4 col-md-offset-1" style="position: relative;">
                        <div class="form-group">
                            <div class="input-group">
                            <span class="input-group-addon">
                                Customer
                            </span>
                                <select  id="customer" disabled="true"
                                        class="form-control chosen-select-100-percent"
                                        data-placeholder="--Select Customer--" required>
                                    <option></option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                                data-pre_due="{{ $customer->balance }}"
                                                data-point = "{{ $customer->point }}"
                                            {{ $customer->id == $sale->customer_id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="customer_id" value="{{ $sale->customer_id }}">

                                <span class="input-group-addon">
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal2">
                                    <i class="ace-icon fa fa-plus-circle fa-lg"></i>
                                </a>
                            </span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4 col-md-offset-1" style="position: relative;">
                        <div class="form-group">
                            <div class="input-group">
                            <span class="input-group-addon">
                                Courier
                            </span>
                                <select name="courier_id" id="courier"
                                        class="form-control chosen-select-100-percent"
                                        data-placeholder="--Select Courier--">
                                    <option></option>
                                    @foreach ($couriers as $courier)
                                        <option value="{{ $courier->id }}" {{ $courier->id == $sale->courier_id ? 'selected' : '' }}>
                                            {{ $courier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                </div>


{{--                <div class="row mb-1">--}}

{{--                    <div class="col-md-8 col-md-offset-2" style="position: relative;">--}}
{{--                        <input type="text" class="form-control product-search-input"--}}
{{--                               style="border-radius: 20px !important;padding: 20px"--}}
{{--                               id="search" placeholder="🔎︎ Product Name"--}}
{{--                               onkeyup="getProductInfo(this,event)" autocomplete="off">--}}

{{--                        <div class="loader" style="right:0;display: none"></div>--}}
{{--                        <div class="product-search"></div>--}}
{{--                    </div>--}}
{{--                    <div class="ajax-loader" style="visibility: hidden;">--}}
{{--                        <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 2%;position: relative;right: 54px;top: 7px;"/>--}}
{{--                    </div>--}}

{{--                </div>--}}
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="order-table" style="background-color: white; color: black !important; margin-bottom: 0px !important">
                                <thead style="border-bottom: 3px solid #346cb0 !important;">
                                <tr style="border: none; background: #dfdfdf !important; color: black !important;">
                                    <th width="3%" class="text-center">SL</th>
                                    <th width="15%">Product Name</th>
                                    <th width="10%">Product Code</th>
                                    <th width="10%">Product Description</th>
                                    <th width="5%" class="text-center">Quantity</th>
                                    <th width="10%" class="text-right">Sale Price</th>
                                    <th style="width: 15%" class="text-center">Discount ৳</th>
                                    <th width="10%" class="text-right">VAT %</th>
                                    {{--                                    <th width="10%" class="text-right">Total VAT</th>--}}
                                    <th width="10%" class="text-right">Sub Total</th>
                                    <th width="4%" class="text-center"><i class="fa fa-trash"></i></th>
                                </tr>
                                </thead>

                                <tbody style="display: block;height: 30vh;overflow: auto;">
{{--                                @if (old('product_ids'))--}}
                                    @foreach ($sale->sale_details as $key => $item)
                                        <tr class="mgrid">
                                            <td style="width:3%">
                                                <span class="serial">{{ $loop->iteration }}</span>
                                                <input type="hidden" class="tr_product_id" name="product_ids[]" value="{{ $item->product->id }}" />
                                            </td>
                                            <td style="width:15%"> {{ optional($item->product)->name }}
                                                <input type="hidden" name="product_titles[]" value="{{ optional($item->product)->name }}"/>
                                            </td>
                                            <td style="width:10%" class="text-left">
                                                <input type="hidden" name="product_codes[]" value="{{ optional($item->product)->barcode }}"/>
                                                {{ optional($item->product)->barcode }}
                                            </td>
                                            <td style="width: 10%">
                                                <input type="text" name="description[]" value="{{ optional($item->product)->description }}" placeholder="Add Description" class="form-control" autocomplete="off">
                                            </td>
                                            <td style="width:12%" class="text-left">
                                                <div class="input-group">
                                                    <input class="form-control product_qty input-sm text-center" id="product-${product_id}" type="number"
                                                           onkeyup="calculateEachRowSubtotal(this)" name="product_qty[]" value="{{ $item->quantity }}" style="width: 70px">
                                                    <code style="position: absolute;top: 4px;">{{ optional(optional($item->product)->unit)->name }}</code>
                                                </div>

                                            </td>
                                            <td style="width:10%">
                                                <input type="text" name="product_price[]" class="form-control product-cost input-sm unit-price"
                                                       onkeyup="calculateEachRowSubtotal(this)" value="{{ number_format($item->price,2) }}"
                                                       autocomplete="off">
                                                <input type='hidden' name="buy_price[]" value="{{ optional($item->product)->purchase_price }}">

                                            </td>

                                            @php
                                                $total = $item->price * $item->quantity;
                                                $total_vat = (($item->vat/100) * $item->price) * $item->quantity;
                                                $subtotal = $total + $total_vat ;
                                                $discountPercent = ($item->discount / $total) * 100;
                                            @endphp

                                            <td style="width:15%">
                                                <div class="input-group">
                                                    <input class="form-control product-discount-percent text-center input-sm"
                                                           value="{{ $discountPercent }}" onkeyup="calculateDiscountAmount(this)" type="text" placeholder="Percent">
                                                    <span class="input-group-addon" style="padding: 6px 2px !important;">
                                                        <i class="fa fa-percent"></i>
                                                    </span>
                                                    <input type="text" name="product_discount[]" value="{{ $item->discount }}" class="form-control product-discount input-sm text-center"
                                                           onkeyup="calculateDiscountPercentage(this)" autocomplete="off" placeholder="Flat">
                                                </div>
                                            </td>

                                            @php

                                            @endphp

                                            <td style="width:10%;text-align: right">
                                                <input type="hidden" name="product_vat[]" value="{{ $item->vat }}" class="product_vat"/>
                                                <strong>{{ number_format($item->vat,2) }}</strong>
                                            </td>
                                            <td style="width: 5%;display: none">
                                                <input type="hidden" name="vat_total[]" value="{{ $item->vat }}"/>
                                                <strong class="sub_total_vat">{{ $total_vat }}</strong>
                                            </td>
                                            <td style="width:10%" class="text-right">
                                                <input type="hidden" name="subtotal[]" value="{{ $item->price }}"/>
                                                <strong class="subtotal">{{ $subtotal }}</strong>
                                                <strong style="display: none" class="subtotal-without-discount-and-vat">{{ $subtotal }}</strong>
                                            </td>


                                            <td style="width:4%; text-align: right">
                                                <a href="javascript:void(0)" class="text-danger" disabled="disabled">
                                                    <i class="ace-icon fa fa-trash bigger-120"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
{{--                                @endif--}}
                                </tbody>
                            </table>


                            <table style="float: right; border: 1px solid #c6c5c5; width: 100%; margin: 0px;background: #f3f3f3">
                                <tr>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important; color: black">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Total Amount
                                                </label>
                                            </div>
                                            <input type="text" class="form-control total text-right" name="payable_amount"
                                                   id="subtotal" value="{{ number_format($sale->payable_amount,2) }}" readonly="">
                                        </div>
                                    </td>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Total VAT
                                                </label>
                                            </div>
                                            <input type="number" class="form-control total text-right" name="total_vat"
                                                   id="total_vat" value="{{ number_format($sale->total_vat,2) }}" readonly>
{{--                                            <strong class="vat_total" style="display: none">{{ $sale->total_vat }}</strong>--}}
                                        </div>
                                    </td>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Delivery Charge
                                                </label>
                                            </div>
                                            <input type="number" class="form-control text-right delivery_charge"
                                                   onkeyup="calculatePayable()" name="delivery_charge"
                                                   value="{{ number_format($sale->delivery_charge,2) }}"
                                                   id="delivery_charge" autocomplete="off" />
                                        </div>
                                    </td>

                                </tr>

                                <tr>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Previous Due
                                                </label>
                                            </div>
                                            <input type="number" class="form-control total text-right" name="previous_due"
                                                   id="previous_due" value="{{ optional($sale->customer)->balance }}" readonly>
                                        </div>
                                    </td>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Discount
                                                </label>
                                            </div>
                                            <input type="number" class="form-control text-right discount" name="discount" value="{{ $sale->discount }}" id="discount" autocomplete="off" readonly>
                                            <input type="hidden" class="form-control input-sm text-right" id="pointDiscount">
                                        </div>
                                    </td>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Paid Amount
                                                </label>
                                            </div>
                                            <input type="text" class="form-control text-right paid_amount" onkeyup="calculateDueAmount()"
                                                   name="paid_amount" value="{{ $sale->paid_amount }}" id="paid_amount" autocomplete="off" required />
                                        </div>
                                    </td>


                                </tr>

                                <tr>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Payable
                                                </label>
                                            </div>
                                            <input type="text" class="form-control total text-right" name="payable_amount"
                                                   id="payable" value="{{ $sale->payable_amount }}" readonly>
                                        </div>
                                    </td>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Due Amount
                                                </label>
                                            </div>
                                            <input type="text" class="form-control text-right due_amount" name="due_amount"
                                                   id="due_amount" autocomplete="off" value="{{ $sale->payable_amount - $sale->paid_amount }}"  readonly />
                                        </div>
                                    </td>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Refer Customer
                                                </label>
                                            </div>
                                            <select id="refer_customer" disabled="true"
                                                    class="form-control select2"
                                                    data-placeholder="--Select Refer Customer--">
                                                <option></option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ $customer->id == $sale->refer_customer_id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="refer_customer_id" value="{{ $sale->refer_customer_id }}">
                                        </div>
                                    </td>

                                </tr>


                                <tr>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Account Type <em class="text-danger">*</em>
                                                </label>
                                            </div>
                                            <select id="account_type_id" class="form-control select2" disabled="true"
                                                    data-placeholder="-- Select Account Type --" required>
                                                <option value=""></option>
                                                @foreach (account() as $key => $type)
                                                    <option value="{{ $key }}" {{ $key == $sale->account_id ? 'selected' : '' }}>{{ $type }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="account_id" value="{{ $sale->account_id }}">

                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    <div style="font-size: 13px">
                                                        <strong class="balance"></strong>
                                                    </div>
                                                    <div class="ajax-loader-acc" style="visibility: hidden;">
                                                        <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 7%;position: absolute;left: 365px; top: 0px;"/>
                                                    </div>
                                                </label>
                                            </div>

                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
{{--                                    <td style="background: aquamarine;padding: 10px;">--}}
{{--                                        <div class="radio">--}}
{{--                                            <label style="padding-left: 10px">--}}
{{--                                                <input name="" class="point" type="checkbox" id="test3">--}}
{{--                                                <label for="test3" style="font-size: 18px; font-weight: 500; color: #000000 !important">Use Point : <span class="customer_point"></span></label>--}}
{{--                                            </label>--}}

{{--                                            <span style="float: right;font-size: 15px;color: #666666; display:none;" class="point_show">--}}
{{--                                             <b>Customer Point: <span class="customer_point"></span></b><br>--}}
{{--                                                <input type="hidden" name="customer_point" id="customer_point">--}}
{{--                                                <input type="number" name="point" class="form-control" value="" id="use_point" placeholder="Enter Point" onkeyup="updateDiscount(this)" style="height: 28px !important;">--}}
{{--                                            </span>--}}


{{--                                        </div>--}}
{{--                                    </td>--}}

{{--                                    <td>--}}
{{--                                        <div class="radio" style="background: antiquewhite;padding: 10px;">--}}
{{--                                            <label style="padding-left: 10px">--}}
{{--                                                <input name="is_cod" class="" type="checkbox" id="test2">--}}
{{--                                                <label for="test2"><b>Cash on Delivery</b></label>--}}
{{--                                            </label>--}}

{{--                                        </div>--}}
{{--                                    </td>--}}

                                </tr>

                                <tr>
                                    <td>
                                        <div class="radio">
                                            <label>
                                                <input name="invoice_type" value="pos-invoice" type="radio" class="ace" required="required">
                                                <span class="lbl"> POS Print</span>
                                            </label>

                                            <label>
                                                <input name="invoice_type" value="normal-invoice" type="radio" checked="" class="ace">
                                                <span class="lbl"> Normal Print</span>
                                            </label>
                                        </div>
                                    </td>

                                    <td style="width: 33.33%;padding: 5px">
                                        <div class="col-md-6" style="padding: 0 !important;">
                                            @if (hasPermission('dokani.sales.index', $slugs))
                                                <a href="{{ route('dokani.sales.index') }}" class="btn btn-sm btn-success btn-block" style="width: 100%; border-radius: 0px !important; background-color: #cdffbe !important; border-color: #cdffbe; color: black !important;">
                                                    <i class="fas fa-list"></i> LIST
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-md-6" style="padding: 0 !important;">
                                            <button type="submit" id="submit_btn" class="btn btn-sm btn-primary btn-block save-btn" style="background-color: #0044ff !important; border-color: #0044ff !important; border-radius: 0px !important;">
                                                <i class="far fa-check-circle"></i> SUBMIT
                                            </button>
                                        </div>
                                    </td>
                                </tr>


                            </table>



                        </div>
                    </div>
                </div>

            </form>

        </div>




    </div>

@endsection

@section('js')
    {{--    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>--}}

    @include('orders._inc.script')

    @include('partials._account-balance-script')
    <script>

        function GetInfo(object) {
            let _this = $(object);

            let product_id = _this.closest('.single-product-li').find('.product_id').text();
            let product_title = _this.closest('.single-product-li').find('.product-title').text();
            let product_code = _this.closest('.single-product-li').find('.sku-code').text();
            let product_price = _this.closest('.single-product-li').find('.product-price').text();
            let vat = _this.closest('.single-product-li').find('.product-vat').text();
            let buy_price = _this.closest('.single-product-li').find('.buy-price').text();
            let unit = _this.closest('.single-product-li').find('.product-unit').text();

            let table = $('#order-table tbody');
            let length = $('#order-table tbody tr').length + 1;

            // add item into the sale table
            addProduct(length, product_id, product_title, product_code, 1, product_price, vat,buy_price,unit, table)
        }

        $('#customer').on('change', function() {

            var pre_due = $(this).find(':selected').data('pre_due');

            $('#previous_due').val(pre_due);
            calculatePayable()
        });

        $('#customerBtn').click(function() {
            if ($('#name').val() != '') {
                $('#name').css('border', '1px solid gray');
                $('#customerBtn').prop('disabled', true);
                $('#loader').addClass('loader')
                setTimeout($.post('{!! route('dokani.customers.store') !!}', $('form#customer-form').serialize(), function(
                    response) {
                        let data = response.data;

                        if (data != []) {
                            $('#customer').append('<option value=' + data.id + ' selected>' + data.name +
                                '</option>')
                            $("#customer").trigger("chosen:updated");
                            $('#myModal2').modal('hide');
                            $('#customerBtn').prop('disabled', false);
                            $('#name').val('');
                            $('#loader').removeClass('loader')
                        } else {
                            $('#loader').removeClass('loader')
                        }

                    },
                    'json'
                ), 3000);

            } else {
                $('#loader').removeClass('loader')
                $('#name').css('border', '1px solid red');
                console.log('alert')
            }
        });



        $('#customer').on('change', function() {

            var point = $(this).find(':selected').data('point');

            $('.customer_point').text(point);
            $('#customer_point').val(point);

        });

        $(".point").change(function(){

            if( $(this).is(":checked") ){

                $('.point_show').show()
            }
            else {
                $('.point_show').hide()
            }
        });



        $('#refer_customer').on('change', function () {
            let customer_id = $('#customer').find(':selected').val();
            let refer_customer_id = $(this).find(':selected').val();

            if (customer_id == refer_customer_id){

                toastr.warning('Do not refer same customer')
                $("#refer_customer > option:selected").attr("selected",false);
                $('#refer_customer > option[value=""]').prop('selected', true);
            }
        })




        function updateDiscount(object) {

            let point = Number($(object).val());
            let point_value = '{{ optional($point)->point_value }}'
            let customer_point = Number($('#customer').find(':selected').data('point'));

            if (customer_point){

                let update_point = customer_point - point;
                $('.customer_point').text(update_point)
                let total_discount = point * point_value;

                $('#pointDiscount').val(total_discount)

                if (update_point < 0){

                    toastr.warning('No available point')
                    $(object).val(customer_point)
                    $('.customer_point').text(0);
                    $('#customer_point').val(0);
                    $('#pointDiscount').val(customer_point * point_value)
                }else {

                    $('.customer_point').text(update_point);
                    $('#customer_point').val(update_point);

                }


            }
            else {
                toastr.warning('Customer have no point')
                $(object).val(null)
            }



            calculateTotalDiscount()

        }



        function calculateTotalDiscount()
        {
            let totalDiscount = 0;

            $('.product-discount').each(function () {
                totalDiscount += Number($(this).val());
            });

            let pointDiscount = Number($('#pointDiscount').val());
            totalDiscount = totalDiscount + pointDiscount;

            $('#discount').val(totalDiscount.toFixed(2));

            calculateSubtotal()
        }



    </script>

@endsection

