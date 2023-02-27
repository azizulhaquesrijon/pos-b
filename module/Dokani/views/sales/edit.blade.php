@extends('layouts.master')
@section('title', 'Edit Sale')
@section('page-header')
    <i class="fa fa-plus"></i> Edit Sale
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
    </style>
@endpush

@section('content')


    @include('partials._customer-form')
    <div class="main-content-inner">

        <div class="page-content">

            <!-- DYNAIC CONTENT FROM VIEWS -->
            <div class="page-header">
                <h4 class="page-title"><i class="fa fa-list"></i> Edit Sale</h4>
                <div class="btn-group">

                </div>
            </div>

            <form action="{{ route('dokani.sales.update',$sale->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-1">

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
                                        <option value="{{ $customer->id }}" {{ ($sale->customer_id == $customer->id) ? 'selected' : '' }}
                                        data-pre_due="{{ $customer->balance }}">
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

{{--                    <div class="col-md-8 col-md-offset-2" style="position: relative;">--}}
{{--                        <input type="text" class="form-control product-search-input"--}}
{{--                               style="border-radius: 20px !important;padding: 20px"--}}
{{--                               id="search" placeholder="🔎︎ Product Name"--}}
{{--                               onkeyup="getProductInfo(this,event)" autocomplete="off">--}}

{{--                        <div class="loader" style="right:0;display: none"></div>--}}
{{--                        <div class="product-search"></div>--}}
{{--                    </div>--}}

                </div>
                <div class="row m-2">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover"  id="order-table">
                                <thead style="border-bottom: 3px solid #346cb0 !important">
                                <tr style="background: #e1ecff !important; color:black !important">
                                    <th width="3%" class="text-center">SL</th>
                                    <th width="25%">Product Name</th>
                                    <th width="25%">Product Code</th>
                                    <th width="5%" class="text-center">Quantity</th>
                                    <th width="10%" class="text-right">Sale Price</th>
                                    <th width="10%" class="text-right">VAT %</th>
                                    <th width="5%" class="text-right">Total VAT</th>
                                    <th width="10%" class="text-right">Sub Total</th>
{{--                                    <th width="2%" class="text-center"><i class="fa fa-trash"></i></th>--}}
                                </tr>
                                </thead>

                                <tbody>
                                {{--                                @if (old('product_ids'))--}}
                                @foreach ($sale->sale_details as $key => $item)
                                    <tr class="mgrid">
                                        <td width="3%">
                                            <span class="serial">{{ $key + 1 }}</span>
                                            <input type="hidden" class="tr_product_id" name="product_ids[]"
                                                   value="{{ $item->product->id }}" />
                                            <input type="hidden" name="product_titles[]"
                                                   value="{{ $item->product->name[$key] }}" />
                                            <input type="hidden" name="product_codes[]"
                                                   value="{{ $item->product->barcode[$key] }}" />
                                        </td>
                                        <td style="width: 25%"> {{ $item->product->name }} </td>
                                        <td style="width: 25%"> {{ $item->product->barcode }} </td>
                                        <td style="width: 15%">
                                            <div class="form-group">
                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <a href="javascript:void(0)" onclick="DecreaseOrder(this)"><i
                                                                    class="ace-icon fa fa-minus"
                                                                    style="color: rgb(126, 3, 3)"></i></a>
                                                        </span>
                                                    <input class="form-control product_qty" type="number"
                                                           onkeyup="updateCart(this)" name="product_qty[]"
                                                           value="{{ $item->quantity }}">
                                                    <span class="input-group-addon">
                                                            <a href="javascript:void(0)" onclick="IncreaseOrder(this)"><i
                                                                    class="ace-icon fa fa-plus"></i></a>
                                                        </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" name="product_price[]" class="form-control product-cost"
                                                   onkeyup="updateCart(this)" value="{{ $item->price }}"
                                                   autocomplete="off" readonly>
                                        </td>
                                        @php
                                            $total = $item->price * $item->quantity;
                                            $total_vat = (($item->vat/100) * $item->price) * $item->quantity;
                                            $subtotal = $total + $total_vat ;
                                        @endphp
                                        <td style="width: 10%">
                                            <input type="hidden" name="product_vat[]" class="product_vat" value="{{ $item->vat }}">
                                            <strong class="product_vat">{{ $item->vat }}</strong></td>
                                        <td style="width: 5%"><strong
                                                class="sub_total_vat">{{ $total_vat }}</strong></td>
                                        <td style="width: 10%"><strong
                                                class="subtotal">{{ $subtotal }}</strong></td>
                                        <input type="hidden" name="subtotal[]"
                                               value="{{ $subtotal }}" />
{{--                                        <td style="width: 2%">--}}
{{--                                            <a href="#" class="text-danger" onclick="removeField(this)">--}}
{{--                                                <i class="ace-icon fa fa-trash-o bigger-120"></i>--}}
{{--                                            </a>--}}
{{--                                        </td>--}}
                                    </tr>
                                @endforeach
                                {{--                                @endif--}}
                                </tbody>


                            </table>


                            <table style="float: right; border: 1px solid #c6c5c5; width: 32%">
                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">
                                                Total Amount
                                            </label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control total text-right" name="payable_amount"
                                                       id="total" value="{{ $sale->payable_amount }}" readonly="">
                                            </div>
                                        </div>

                                    </th>

                                </tr>
                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">
                                                Total VAT
                                            </label>
                                            <div class="col-md-8">
                                                <input type="number" class="form-control total_vat text-right" name="total_vat"
                                                       id="total_vat" value="{{ $sale->total_vat }}" readonly>
                                                <strong class="vat_total" style="display: none">{{ $sale->total_vat }}</strong>
                                            </div>
                                        </div>

                                    </th>

                                </tr>
                                <tr>

                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">
                                                Delivery Charge
                                            </label>
                                            <div class="col-md-8">
                                                <input type="number" class="form-control text-right delivery_charge"
                                                       name="delivery_charge" id="delivery_charge" value="{{ $sale->delivery_charge }}" autocomplete="off" required />
                                            </div>
                                        </div>
                                    </th>

                                </tr>

                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">
                                                Previous Due
                                            </label>
                                            <div class="col-md-8">
                                                <input type="number" class="form-control total text-right" name="previous_due"
                                                       id="previous_due" value="{{ $sale->previous_due }}" readonly>
                                            </div>
                                        </div>

                                    </th>

                                </tr>
                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">
                                                Discount
                                            </label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control discount text-right"
                                                       placeholder="Discount" name="discount" value="{{ $sale->discount }}" id="discount">
                                            </div>
                                        </div>

                                    </th>

                                </tr>
                                <tr>
                                    <th style="width: 33.33%;padding: 5px">

                                        <div class="form-group">
                                            <label class="control-label col-md-4">
                                                Paid Amount
                                            </label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control paid_amount text-right" placeholder="Paid Amount"
                                                       name="paid_amount" id="paid_amount" value="{{ $sale->paid_amount }}">
                                            </div>
                                        </div>

                                    </th>

                                </tr>

                                <tr>
                                    <th style="width: 33.33%;padding: 5px">

                                        <div class="form-group">
                                            <label class="control-label col-md-4">
                                                Due Amount
                                            </label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control due_amount text-right"
                                                       placeholder="Due Amount" name="due_amount" id="due_amount" value="{{ $sale->due_amount }}" readonly="">
                                            </div>
                                        </div>

                                    </th>
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
                                </tr>

                                <tr>
                                    <td style="width: 33.33%;padding: 5px">
                                        <input type="submit" class="btn btn-primary" value="Submit">
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    @include('orders._inc.script')
    <script>

        function GetInfo(object) {
            let _this = $(object);

            let product_id = _this.closest('.single-product-li').find('.product_id').text();
            let product_title = _this.closest('.single-product-li').find('.product-title').text();

            let product_code = _this.closest('.single-product-li').find('.sku-code').text();
            let product_price = _this.closest('.single-product-li').find('.product-price').text();
            let vat = _this.closest('.single-product-li').find('.product-vat').text();
            console.log(vat)
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
    </script>

@endsection


