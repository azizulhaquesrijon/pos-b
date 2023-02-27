@extends('layouts.master')
@section('title', 'Purchase Create')
@section('page-header')
    <i class="fa fa-plus"></i> Purchase Create
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

        .calculation_tr{
            width: 16%;
            padding: 3px !important;
            border: 0px !important;
            font-size: 16px;
            font-weight: 500;
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



        .pos_table .pos_tbody {
            display: block;
            max-height: 300px;
            overflow-y: scroll;
        }

        .pos_table .pos_thead, .pos_table .pos_tbody .pos_tr {
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


    </style>
@endpush

@section('content')


    @include('partials._supplier-form')
    <div class="main-content-inner">

        <div class="page-content">

            <!-- DYNAIC CONTENT FROM VIEWS -->
            <div class="page-header">
                <h4 class="page-title"><i class="fa fa-list"></i> Purchase Create</h4>
                <div class="btn-group">

                </div>
            </div>

                <div class="row mb-1">

                    <div class="col-md-8 col-md-offset-2" style="position: relative;">
                        <div class="form-group">
                            <div class="input-group">
                            <span class="input-group-addon">
                                Supplier
                            </span>
                                <select id="supplier"
                                        class="form-control chosen-select-100-percent"
                                        data-placeholder="--Select Supplier--" required>
                                    <option></option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $supplier->id == $order->supplier->id ? 'selected' : '' }}
                                                data-pre_due="{{ $supplier->balance }}">
                                            {{ $supplier->name }}
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
                               id="search" placeholder="🔎︎ Product Name" autocomplete="off">

                        <div class="loader" style="right:0;display: none"></div>
                        <div class="product-search"></div>
                    </div>

                    <div class="ajax-loader" style="visibility: hidden;">
                        <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 2%;position: relative;right: 54px;top: 7px;"/>
                    </div>

                </div>
            <form action="{{ route('dokani.create.purchase') }}" method="POST">
                @csrf
                @include('purchases.orders._inc.account-modal')
                <input type="hidden" name="supplier_id" value="{{ $order->supplier->id }}">
                <input type="hidden" name="purchase_order_id" value="{{ $order->id }}">
                <div class="row m-2">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered pos_table" id="purchase-table" style="background-color: white; color: black !important; margin-bottom: 0px !important">
                                <thead style="border-bottom: 3px solid #346cb0 !important;" class="pos_thead">
                                <tr style="border: none; background: #dfdfdf !important; color: black !important;" class="pos_tr">
                                    <th width="4%" class="text-center">SL</th>
                                    <th width="10%">Product Name</th>
                                    <th width="10%">Product Code</th>
                                    <th width="10%">Product Description</th>
                                    @if(optional(auth()->user()->businessProfile)->has_expiry_date == 0)
                                        <th width="10%">Expiry Date</th>
                                    @endif
                                    <th width="12%" class="text-center">Quantity</th>
                                    <th width="10%">Unit</th>
                                    <th width="10%">Purchase Price</th>
                                    <th width="10%" class="text-left">Sub Total</th>
                                    <th width="4%" class="text-center"><i class="far fa-times-circle fa-lg"></i></th>
                                </tr>
                                </thead>

                                <tbody style="display: block;height: 30vh;overflow: auto;" class="pos_tbody">
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

                                                        <input class="form-control product_qty" type="number"
                                                               onkeyup="updateCart(this)" name="product_qty[]"
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
                                                    class="subtotal">{{ old('product_price')[$key] }}</strong></td>
                                            <td style="width: 2%">
                                                <a href="#" class="text-danger" onclick="removeField(this)">
                                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @foreach ($order->purchsase_order_details as $orderDetail )
                                    <tr class="mgrid">
                                        <td width="4%">
                                            <span class="serial">{{ $loop->iteration }}</span>
                                            <input type="hidden" class="tr_product_id" name="product_ids[]"
                                                value="{{ $orderDetail->product_id }}" />
                                            <input type="hidden" name="product_titles[]"
                                                value="{{ $orderDetail->product->name }}" />
                                            <input type="hidden" name="product_codes[]"
                                                value="{{ $orderDetail->product->code }}" />
                                        </td>
                                        <td style="width: 10%"> {{ $orderDetail->product->name }} </td>
                                        <td style="width: 10%"> {{ $orderDetail->product->barcode }} </td>
                                        <td style="width: 10%">
                                            <input type="text" name="description[]" placeholder="Add Description" value="" class="form-control" autocomplete="off">
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" name="expiry_at[]" class="form-control date-picker" id="date" autocomplete="off" value="{{ $orderDetail->expire_date }}">
                                        </td>

                                       
                                        
                                        <td style="width: 12%">
                                            <div class="form-group">
                                                <div class="input-group">

                                                    <input class="form-control product_qty" type="number"
                                                        onkeyup="updateCart(this)" name="product_qty[]"
                                                        value="{{ $orderDetail->quantity }}">

                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 10%"> {{ $orderDetail->product->unit->name }} </td>
                                        <td style="width: 10%">
                                            <input type="text" name="product_price[]" class="form-control product-cost"
                                                onkeyup="updateCart(this)" value="{{ $orderDetail->price }}"
                                                autocomplete="off">
                                        </td>
                                        <td style="width: 10%"><strong
                                                class="subtotal">{{ $orderDetail->total_amount }}</strong>
                                                <input type="hidden" value="{{ $orderDetail->total_amount }}" name="subtotal[]">
                                        </td>
                                        <td style="width: 2%">
                                            <a href="#" class="text-danger" onclick="removeField(this)">
                                                <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
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
                                                   id="total" value="{{ $order->payable_amount }}" readonly="">
                                        </div>
                                    </td>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Previous Due
                                                </label>
                                            </div>
                                            <input type="text" class="form-control total text-right" name="previous_due"
                                                   id="previous_due" value="{{ $order->supplier->balance }}" readonly>
                                        </div>
                                    </td>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Discount
                                                </label>
                                            </div>
                                            <input type="text" class="form-control discount text-right"
                                                   placeholder="Discount" name="discount" value="0" id="discount">
                                        </div>
                                    </td>

                                </tr>

                                {{-- <input type="hidden" name="payable_amount" value="" id="grand_total"> --}}

                                <tr>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">

                                        <div class="radio" style="margin-top: 8px !important;background: #d7d7d7;padding: 2px;">
                                            <span style="margin-left: 14px;font-weight: 500;font-size: 18px">Print :</span>
                                            <label>
                                                <input name="invoice_type" value="pos-invoice" type="radio" class="ace"
                                                       {{ optional($invoice)->invoice_type == 1 ? 'checked' : '' }} required="required">
                                                <span class="lbl"> POS</span>
                                            </label>

                                            <label>
                                                <input name="invoice_type" value="normal-invoice" type="radio"
                                                       {{ optional($invoice)->invoice_type != 1 ? 'checked' : '' }} class="ace">
                                                <span class="lbl"> Normal</span>
                                            </label>
                                        </div>
{{--                                        <div class="input-group input-group-sm">--}}
{{--                                            <div class="input-group-addon" style="line-height: 17px">--}}
{{--                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">--}}
{{--                                                    Account Type <em class="text-danger">*</em>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
{{--                                            <select name="account_id" id="account_type_id" class="form-control select2"--}}
{{--                                                    data-placeholder="-- Select Account Type --" required>--}}
{{--                                                <option value=""></option>--}}
{{--                                                @foreach (account() as $key => $type)--}}
{{--                                                    <option value="{{ $key }}" {{ $type == 'Cash' ? 'selected' : '' }}>{{ $type }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}

{{--                                            <div class="input-group-addon" style="line-height: 17px">--}}
{{--                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">--}}
{{--                                                    <div style="font-size: 13px">--}}
{{--                                                        <strong class="balance">{{ cashBalance() }}</strong>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="ajax-loader-acc" style="visibility: hidden;">--}}
{{--                                                        <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 7%;position: absolute;left: 365px; top: 0px;"/>--}}
{{--                                                    </div>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}

{{--                                            <input type="hidden" class="acc_balance" value="0">--}}

{{--                                        </div>--}}
                                    </td>


                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Due Amount
                                                </label>
                                            </div>
                                            <input type="text" class="form-control text-right"
                                                   placeholder="Due Amount" name="due_amount" id="due_amount" value="{{ $order->due_amount + $order->supplier->balance }}" readonly="">
                                        </div>
                                    </td>

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon" style="line-height: 17px">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Paid Amount
                                                </label>
                                            </div>
                                            <input type="text" class="form-control paid_amount text-right" placeholder="Paid Amount"
                                                   name="paid_amount" id="paid_amount" value="{{ $order->paid_amount }}" required readonly>
                                        </div>
                                    </td>

                                </tr>

                                <tr>
                                    <td>

                                    </td>
                                    <td></td>

                                    <td style="width: 33.33%;padding: 5px">
                                        <div class="col-md-6" style="padding: 0 !important;">
                                            @if (hasPermission('dokani.purchases.index', $slugs))
                                                <a href="{{ route('dokani.purchases.index') }}" class="btn btn-sm btn-success btn-block" style="width: 100%; border-radius: 0px !important; background-color: #cdffbe !important; border-color: #cdffbe; color: black !important;">
                                                    <i class="fas fa-list"></i> LIST
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-md-6" style="padding: 0 !important;">
                                            <a href="#add-new" role="button" data-toggle="modal"
                                               class="btn btn-sm btn-primary btn-block" style="background-color: #0044ff !important; border-color: #0044ff !important; border-radius: 0px !important;">
                                                <i class="far fa-money"></i> PAY
                                            </a>
                                        </div>
                                    </td>
                                </tr>


                            </table>


{{--                            <table style="float: right; border: 1px solid #c6c5c5; width: 32%">--}}
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
{{--                                            <span class="input-group-addon" style="padding-right: 21px">--}}
{{--                                                Account Type <em class="text-danger">*</em>--}}
{{--                                            </span>--}}
{{--                                            <select name="account_id" id="account_type_id" class="form-control select2"--}}
{{--                                                    data-placeholder="-- Select Account Type --" required>--}}
{{--                                                <option value=""></option>--}}
{{--                                                @foreach (account() as $key => $type)--}}
{{--                                                    <option value="{{ $key }}">{{ $type }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                            <span class="input-group-addon">--}}
{{--                                                <code class="balance"></code>--}}
{{--                                            </span>--}}
{{--                                            <div class="ajax-loader-acc" style="visibility: hidden;">--}}
{{--                                                <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 7%;position: absolute;right: 36px; top: 1px;"/>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <input type="hidden" class="acc_balance" value="0">--}}
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
{{--                                    <td style="background: antiquewhite;">--}}
{{--                                        <div class="radio">--}}
{{--                                            <label>--}}
{{--                                                <input name="invoice_type" value="pos-invoice" type="radio" class="ace" required="required">--}}
{{--                                                <span class="lbl"> POS Print</span>--}}
{{--                                            </label>--}}

{{--                                            <label>--}}
{{--                                                <input name="invoice_type" value="normal-invoice" type="radio" checked="" class="ace">--}}
{{--                                                <span class="lbl"> Normal Print</span>--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}

{{--                                <tr>--}}
{{--                                    <td style="width: 33.33%;padding: 5px">--}}
{{--                                        <input type="submit" id="submit_btn" class="btn btn-primary" value="Submit">--}}
{{--                                    </td>--}}
{{--                                </tr>--}}

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
{{--    <script src="{{ asset('assets/custom_js/purchase_table_field.js') }}"></script>--}}
    <script src="{{ asset('assets/js/jquery.ba-throttle-debounce.js') }}"></script>

    @include('purchases._inc.new-script')

    @include('partials._account-balance-condition-script')

    <script>


        $(document).ready(function(){
            let id = $('#supplier').find(':selected').val();
            if (!id){
                $(".save-btn").prop("disabled", true);
                $(".save-btn").attr("title","Please Select a Supplier");
            }
            else {
                $(".save-btn").prop("disabled", false);
                $(".save-btn").attr("title","Submit");
            }
        });


        $('#supplier').on('change', function() {

            var pre_due = $(this).find(':selected').data('pre_due')
            console.log(pre_due)
            $('#previous_due').val(pre_due);

            calculate();

            let id = $(this).val();

            $('.supplier_id').val(id)

            if (!id){
                $(".save-btn").prop("disabled", true);
                $(".save-btn").attr("title","Please Select a Supplier");
            }
            else {
                $(".save-btn").prop("disabled", false);
                $(".save-btn").attr("title","Submit");
            }

        });

        function GetInfo(object) {
            let _this = $(object);
            let product_id = _this.closest('.single-product-li').find('.product_id').text();
            let product_title = _this.closest('.single-product-li').find('.product-title').text();
            let product_code = _this.closest('.single-product-li').find('.sku-code').text();
            let product_price = Number(_this.closest('.single-product-li').find('.product-price').text()).toFixed(2);
            let unit = _this.closest('.single-product-li').find('.product-unit').text();
            let description = _this.closest('.single-product-li').find('.description').text();
            let table = $('#purchase-table tbody');
            let length = $('#purchase-table tbody tr').length + 1;

            if (description == 'null'){
                description = ''
            }

            addPurchaseItem(length, product_id, product_title, product_code, 1, product_price,unit,description, table)
        }

        $('#supplierBtn').click(function() {
            if ($('#name').val() != '') {
                $('#name').css('border', '1px solid gray');
                $('#supplierBtn').prop('disabled', true);
                $('#loader').addClass('loader')
                setTimeout($.post('{!! route('dokani.suppliers.store') !!}', $('form#supplier-form').serialize(), function(
                    response) {
                        let data = response.data;
                        $('#supplier').append('<option value=' + data.id + ' selected>' + data.name +
                            '</option>')
                        $("#supplier").trigger("chosen:updated");
                        $('#myModal2').modal('hide');
                        $('#supplierBtn').prop('disabled', false);
                        $('#name').val('');
                        $('#loader').removeClass('loader')
                    },
                    'json'
                ), 3000);

            } else {
                $('#loader').removeClass('loader')
                $('#name').css('border', '1px solid red');
                console.log('alert')
            }
        });


        // $('#paid_amount').on('keyup',function() {
        //     let balance = Number($('.balance').text());
        //     let paid_amount = Number($('#paid_amount').val());
        //     console.log(balance)
        //     if (paid_amount > balance){
        //
        //         toastr.warning('No available balance');
        //
        //         $("#submit_btn").prop("disabled", true);
        //     }
        //     else {
        //
        //         $("#submit_btn").prop("disabled", false);
        //     }
        //
        // });


    </script>

    <script>
        $(document).ready(function() {

            let i = 0
            let rowno = 0;
            $("#addrow").on("click", function() {

                rowno = $("#accountTable tbody tr").length + 1;
                console.log(rowno)
                const rowItem = `<tr>
                                <th width="30%">
                                        <select name="account_ids[]" id="account_id${rowno}" style="width: 100%"
                                                class="form-control select2" data-placeholder="- Select Account -"
                                                aria-hidden="true" onchange="bankStatement(${rowno})" required>
                                            <option value=""></option>
                                            @foreach (accountInfo() as $type)
                                                <option value="{{ $type->id }}" data-balance = "{{ $type->balance }}"
                                                        data-name ="{{ $type->name }}"
                                                    {{ $type->balance <= 0 ? 'disabled' : '' }}>
                                                    {{ $type->name .' ('.$type->balance.')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                </th>

                                <th style="display: flex">
                                        <input name="amount[]" type="text" onkeyup="totalAmount(${rowno})"
                                               placeholder="Enter Amount" class="form-control pamount" required/>&nbsp;
                                        <input name="check_no[]" type="text" style="display: none"
                                               placeholder="Enter Check No" class="form-control bankAccount${rowno}"/>&nbsp;
                                        <input name="check_date[]" type="text" style="display: none"  autocomplete="off"
                                               placeholder="Enter Check Date" class="form-control date-picker bankAccount${rowno}"/>
                                    </th>

                                    <th width="7%">
                                     <button type="button" style="background-color: transparent;border: none;" class="remove-row" title="Remove"
                                                ><i class="far fa-times-circle fa-lg text-danger"></i></button>
                                                <br>
                                    <label>
                                        <input onclick="bankStatement(${rowno})"
                                               class="ace ace-checkbox-2 bankStatement${rowno}" type="checkbox">
                                        <span class="lbl">Info.</span>
                                    </label>
                                </th>

                                </tr>`;

                $("#accountTable").append(rowItem);
                $('.select2').select2();
                $('.date-picker').datepicker({
                    autoclose: true,
                    format:'yyyy-mm-dd',
                    todayHighlight: true
                }).next().on(ace.click_event, function(){
                    $(this).prev().focus();
                });
            });



            $("#accountTable").on("click", ".remove-row", function(event) {

                $(this).closest("tr").remove();
                $('.select2').select2();
                i--
                totalAmount()
            });



        });


        function bankStatement(id) {

            if($('.bankStatement'+id).is(":checked")){
                $('.bankAccount'+id).show()
            }else {
                $('.bankAccount'+id).hide()
            }
        }

        function totalAmount(id) {
            let total_val = Number($('.total_amount').val());
            let paid_amount = Number($('#paid_amount').val());
            let available_balance = Number($('#account_id'+id).find(':selected').data('balance'))
            console.log(available_balance)
            var total = 0

            $('.pamount').each(function() {
                let amount = Number($(this).val())

                if (total_val > paid_amount){
                    toastr.warning('This amount over the paid amount')
                }

                if (available_balance < amount){

                    toastr.warning('No available amount')
                    amount = available_balance
                    $(this).val(amount)
                }
                total += amount
                // $('#paid_amount').val(total)
            })
            $(".total_amount").val(total);
            $(".paid_amount").text(total);
            $("#paid_amount").val(total);

            calculate();

        }



    </script>


@endsection


