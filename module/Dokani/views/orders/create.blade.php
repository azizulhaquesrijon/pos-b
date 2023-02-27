@extends('layouts.master')
@section('title', 'Order Create')
@section('page-header')
    <i class="fa fa-plus"></i> Order Create
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



        .calculation_tr{
            width: 16%;
            padding: 3px !important;
            border: 0px !important;
            font-size: 16px;
            font-weight: 500;
        }




    </style>
@endpush

@section('content')


    @include('partials._customer-form')
    <div class="main-content-inner">

        <div class="page-content">

            <!-- DYNAIC CONTENT FROM VIEWS -->
            <div class="page-header">
                <h4 class="page-title"><i class="fa fa-list"></i> Order Create</h4>
                <div class="btn-group">

                </div>
            </div>

            <form action="{{ route('dokani.orders.store') }}" method="POST">
                @csrf

                <div class="row mb-1">

                    <input type="hidden" name="default_vat" class="default_vat" value="{{ $business_setting->vat ?? 0 }}" id="">
                    <div class="col-md-8 col-md-offset-2" style="position: relative;">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    Customer
                                </span>
                                <select name="customer_id" id="customer"
                                        class="form-control chosen-select-100-percent"
                                        data-placeholder="--Select Customer--" required>
                                    <option></option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                                data-pre_due="{{ $customer->balance }}" {{ $customer->dokan_id == null ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <span class="input-group-addon">
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal2">
                                        <i class="ace-icon fa fa-plus-circle fa-lg"></i>
                                    </a>
                                </span>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="row mb-1">

                    <div class="col-md-8 col-md-offset-2" style="position: relative;">
                        <input type="text" class="form-control product-search-input"
                               style="border-radius: 20px !important;padding: 20px"
                               id="search" placeholder="🔎︎ Product Name"
                               onkeyup="getProductInfo(this,event)" autocomplete="off">

                        <div class="loader" style="right:0;display: none"></div>
                        <div class="product-search" id="product-search">

                        </div>
                    </div>

                    <div class="ajax-loader" style="visibility: hidden;">
                        <img src="{{ asset('assets/images/loading2.gif') }}" class="img-responsive" style="width: 29px;position: relative;right: 54px;top: 7px;"/>
                    </div>

                </div>
                <div class="row m-2">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover"  id="order-table">
                                <thead style="border-bottom: 3px solid #346cb0 !important">
                                <tr style="background: #e1ecff !important; color:black !important">
                                    <th width="3%" class="text-center">SL</th>
                                    <th width="20%">Product Name</th>
                                    <th width="15%">Product Code</th>
                                    <th width="15%">Product Description</th>
                                    <th width="5%" class="text-center">Quantity</th>
                                    <th width="10%" class="text-right">Sale Price</th>
                                    <th width="10%" class="text-right">VAT %</th>
                                    <th width="10%" class="text-right">Total VAT</th>
                                    <th width="10%" class="text-right">Sub Total</th>
                                    <th width="2%" class="text-center"><i class="fa fa-trash"></i></th>
                                </tr>
                                </thead>

                                <tbody style="display: block;height: 30vh;overflow: auto;">
                                @if (old('product_ids'))
                                    @foreach (old('product_ids') as $key => $item)
                                        <tr class="mgrid">
                                            <td width="3%">
                                                <span class="serial">{{ $key + 1 }}</span>
                                                <input type="hidden" class="tr_product_id" name="product_ids[]"
                                                       value="{{ $item }}" />
                                                <input type="hidden" name="product_titles[]"
                                                       value="{{ old('product_titles')[$key] }}" />
                                                <input type="hidden" name="product_codes[]"
                                                       value="{{ old('product_codes')[$key] }}" />
                                            </td>
                                            <td style="width: 25%"> {{ old('product_titles')[$key] }} </td>
                                            <td style="width: 25%"> {{ old('product_codes')[$key] }} </td>
                                            <td style="width: 5%">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <a href="javascript:void(0)" onclick="DecreaseOrder(this)"><i
                                                                    class="ace-icon fa fa-minus"
                                                                    style="color: rgb(126, 3, 3)"></i></a>
                                                        </span>
                                                        <input class="form-control product_qty" type="number"
                                                                name="product_qty[]"
                                                               value="{{ old('product_qty')[$key] }}">
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width: 10%">
                                                <input type="text" name="product_price[]" class="form-control product-cost"
                                                       onkeyup="updateCart(this)" value="{{ old('product_price')[$key] }}"
                                                       autocomplete="off">
                                            </td>
                                            <td style="width: 10%"><strong
                                                    class="product_vat">{{ old('product_vat')[$key] }}</strong></td>
                                            <td style="width: 10%"><strong
                                                    class="subtotal">{{ old('product_price')[$key] }}</strong></td>
                                            <td style="width: 2%">
                                                <a href="#" class="text-danger" onclick="removeField(this)">
                                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
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
                                            <input type="text" class="form-control total text-right" name="sub_total"
                                                   id="total" value="0" readonly="">
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
                                                   id="total_vat" value="0" readonly>
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
                                                   onkeyup="calculatePayable()" name="delivery_charge" value="0"
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
                                                   id="previous_due" value="{{ customerDue() }}" readonly>
                                        </div>
                                    </td>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Discount
                                                </label>
                                            </div>
                                            <input type="number" class="form-control text-right discount" name="discount" value="0" id="discount" autocomplete="off">
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
                                            <input type="number" class="form-control text-right paid_amount" onkeyup="calculateDueAmount()"
                                                   name="paid_amount" id="paid_amount" autocomplete="off" required/>
                                        </div>
                                    </td>


                                </tr>

                                <tr>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Due Amount
                                                </label>
                                            </div>
                                            <input type="number" class="form-control text-right due_amount" name="due_amount"
                                                   id="due_amount" autocomplete="off" readonly />
                                        </div>
                                    </td>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Payable Amount
                                                </label>
                                            </div>
                                            <input type="number" class="form-control text-right payable_amount" name="payable_amount"
                                                   id="payable_amount" autocomplete="off" readonly />
                                        </div>
                                    </td>



                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="radio col-md-6" style="margin-top: 8px !important;">
                                            <span style="margin-left: 14px;font-weight: 500;font-size: 18px">Print :</span>
                                            <label>
                                                <input name="invoice_type" value="pos-invoice" type="radio" class="ace"
                                                       {{ optional($business_setting)->invoice_type == 1 ? 'checked' : '' }} required="required">
                                                <span class="lbl"> POS</span>
                                            </label>

                                            <label>
                                                <input name="invoice_type" value="normal-invoice" type="radio"
                                                       {{ optional($business_setting)->invoice_type != 1 ? 'checked' : '' }} class="ace">
                                                <span class="lbl"> Normal</span>
                                            </label>
                                        </div>
                                        {{-- <div class="col-md-6" style="padding: 0 !important;">
                                            @if (hasPermission('dokani.sales.index', $slugs))
                                                <a href="{{ route('dokani.sales.index') }}" class="btn btn-sm btn-success btn-block" style="width: 100%; border-radius: 0px !important; background-color: #cdffbe !important; border-color: #cdffbe; color: black !important;">
                                                    <i class="fas fa-list"></i> LIST
                                                </a>
                                            @endif
                                        </div> --}}
                                        <div class="col-md-6" style="padding: 0 !important;">
                                            <button type="submit"
                                                    class="btn btn-sm btn-primary btn-block" style="background-color: #0044ff !important; border-color: #0044ff !important; border-radius: 0px !important;">
                                                <i class="far fa-check-circle"></i> SUBMIT
                                            </button>
                                        </div>
                                    </td>

                                </tr>



                            </table>
{{--                            <table style="float: right; border: 1px solid #c6c5c5; width: 32%; padding: 10px">--}}
{{--                                <tr>--}}
{{--                                    <th style="width: 33.33%;padding: 5px">--}}
{{--                                        <div class="input-group">--}}
{{--                                            <span class="input-group-addon" style="padding-right: 34px">--}}
{{--                                                Total Amount--}}
{{--                                            </span>--}}
{{--                                            <input type="text" class="form-control total text-right" name="payable_amount"--}}
{{--                                                   id="total" value="0" readonly="">--}}
{{--                                        </div>--}}

{{--                                    </th>--}}

{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <th style="width: 33.33%;padding: 5px">--}}
{{--                                        <div class="input-group">--}}
{{--                                            <span class="input-group-addon" style="padding-right: 57px">--}}
{{--                                                Total VAT--}}
{{--                                            </span>--}}
{{--                                            <input type="number" class="form-control total text-right" name="total_vat"--}}
{{--                                                   id="total_vat" value="0" readonly>--}}
{{--                                        </div>--}}

{{--                                    </th>--}}

{{--                                </tr>--}}
{{--                                <tr>--}}

{{--                                    <th style="width: 33.33%;padding: 5px">--}}
{{--                                        <div class="input-group">--}}
{{--                                            <span class="input-group-addon">--}}
{{--                                                Delivery Charge--}}
{{--                                            </span>--}}
{{--                                            <input type="number" class="form-control text-right delivery_charge"--}}
{{--                                                   name="delivery_charge" value="0" id="delivery_charge" autocomplete="off"/>--}}
{{--                                        </div>--}}
{{--                                    </th>--}}

{{--                                </tr>--}}

{{--                                <tr>--}}
{{--                                    <th style="width: 33.33%;padding: 5px">--}}
{{--                                        <div class="input-group">--}}
{{--                                            <span class="input-group-addon" style="padding-right: 35px">--}}
{{--                                                Previous Due--}}
{{--                                            </span>--}}
{{--                                            <input type="number" class="form-control total text-right" name="previous_due"--}}
{{--                                                   id="previous_due" value="0" readonly>--}}
{{--                                        </div>--}}
{{--                                    </th>--}}

{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <th style="width: 33.33%;padding: 5px">--}}
{{--                                        <div class="input-group">--}}
{{--                                            <span class="input-group-addon" style="padding-right: 67px">--}}
{{--                                                Discount--}}
{{--                                            </span>--}}
{{--                                            <input type="text" class="form-control discount text-right"--}}
{{--                                                   placeholder="Discount" name="discount" value="0" id="discount">--}}
{{--                                        </div>--}}

{{--                                    </th>--}}

{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <th style="width: 33.33%;padding: 5px">--}}
{{--                                        <div class="input-group">--}}
{{--                                            <span class="input-group-addon" style="padding-right: 44px">--}}
{{--                                                Paid Amount--}}
{{--                                            </span>--}}
{{--                                            <input type="text" class="form-control paid_amount text-right" placeholder="Paid Amount"--}}
{{--                                                   name="paid_amount" id="paid_amount" value="0">--}}
{{--                                        </div>--}}
{{--                                    </th>--}}
{{--                                </tr>--}}

{{--                                <tr>--}}
{{--                                    <th style="width: 33.33%;padding: 5px">--}}
{{--                                        <div class="input-group">--}}
{{--                                            <span class="input-group-addon" style="padding-right: 52px">--}}
{{--                                                Due Amount--}}
{{--                                            </span>--}}
{{--                                            <input type="text" class="form-control due_amount text-right"--}}
{{--                                                   placeholder="Due Amount" name="due_amount" id="due_amount" value="0" readonly="">--}}
{{--                                        </div>--}}
{{--                                    </th>--}}
{{--                                </tr>--}}

{{--                                <tr>--}}
{{--                                    <th style="width: 33.33%;padding: 5px">--}}
{{--                                        <div class="input-group">--}}
{{--                                            <span class="input-group-addon" style="padding-right: 21px">--}}
{{--                                                Account Type <em class="text-danger">*</em>--}}
{{--                                            </span>--}}
{{--                                            <select name="account_type_id" id="" class="form-control select2"--}}
{{--                                            data-placeholder="-- Select Account Type --" required >--}}
{{--                                            <option value=""></option>--}}
{{--                                                @foreach (accountType() as $key => $type)--}}
{{--                                                    <option value="{{ $key }}">{{ $type }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </th>--}}
{{--                                </tr>--}}

{{--                            <tr>--}}
{{--                                <td style="width: 33.33%;padding: 5px">--}}
{{--                                    <input type="submit" class="btn btn-primary" value="Submit">--}}
{{--                                </td>--}}
{{--                            </tr>--}}

{{--                            </table>--}}


                        </div>
                    </div>
                </div>

            </form>

        </div>




    </div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    @include('orders._inc.order-script')
    <script>


        function GetInfo(object) {
            let _this = $(object);

            let product_id = _this.closest('.single-product-li').find('.product_id').text();
            let product_title = _this.closest('.single-product-li').find('.product-title').text();

            let product_code = _this.closest('.single-product-li').find('.sku-code').text();
            let product_price = _this.closest('.single-product-li').find('.product-price').text();
            let vat = _this.closest('.single-product-li').find('.product-vat').text();
            // console.log(vat)
            let table = $('#order-table tbody');
            let length = $('#order-table tbody tr').length + 1;

            // add item into the sale table
            addProduct(length, product_id, product_title, product_code, 1, product_price, vat, table)
        }

        $('#customer').on('change', function() {

            var pre_due = $(this).find(':selected').data('pre_due');
            $('#previous_due').val(pre_due);
            calculate();
        });

        $('#customerBtn').click(function() {
            if ($('#name').val() != '') {
                $('#name').css('border', '1px solid gray');
                $('#customerBtn').prop('disabled', true);
                // $('#loader').addClass('loader')
                setTimeout($.post('{!! route('dokani.customers.store') !!}', $('form#customer-form').serialize(), function(
                    response) {
                        let data = response.data;

                        location.reload();

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
    </script>

@endsection
