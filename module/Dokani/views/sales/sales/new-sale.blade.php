@extends('layouts.master')

@section('title', 'Sale Create')

@section('page-header')
    <i class="fa fa-plus"></i> Sale Create
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

            <!-- DYNAMIC CONTENT FROM VIEWS -->
            <div class="page-header">
                <h4 class="page-title"><i class="fa fa-list"></i> Sale Create</h4>
                <div class="btn-group">

                </div>
            </div>




            <form action="{{ route('dokani.create.sale') }}" method="POST" id="saleSubmitForm">
                @csrf
                <div class="row mb-1" style="background: rgb(245 245 245); border-bottom: 2px solid; padding: 10px">
                    <div class="col-md-3" style="position: relative;">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    Customer <sup style="color: red">*</sup>
                                </span>
                                <select id="customer"
                                        class="form-control chosen-select-100-percent"
                                        data-placeholder="--Select Customer--" required>
                                    <option></option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                                data-pre_due="{{ $customer->balance }}"
                                                data-point = "{{ $customer->point }}"
                                            {{ $customer->is_default == 1 ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3" style="position: relative;">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    Courier <sup style="color: red">*</sup>
                                </span>
                                <select name="courier_id" id="courier"
                                    class="form-control chosen-select-100-percent"
                                    data-placeholder="--Select Courier--">
                                    <option></option>
                                    @foreach ($couriers as $courier)
                                        <option value="{{ $courier->id }}">
                                            {{ $courier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @if (hasBranchSystem())
                        <div class="col-md-3" style="position: relative;">
                            <div class="form-group">
                                <div class="input-group">
                                <span class="input-group-addon">
                                    Branch <sup style="color: red">*</sup>
                                </span>
                                    <select id="branch_id"
                                            class="form-control chosen-select-100-percent"
                                            data-placeholder="--Select Branch--" required>
                                        @if(auth()->user()->type == "owner")
                                            <option value="">Main Branch</option>
                                        @endif
                                        @foreach ($branches as $id => $branch)
                                            <option value="{{ $branch->id }}" data-employees="{{ $branch->employee }}" data-inv_prefix="{{ $branch->short_name }}">
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-3" style="position: relative;">
                        <div class="form-group">
                            <div class="input-group">
                            <span class="input-group-addon">
                                Sale By
                            </span>
                                <select id="employee_id"
                                        class="form-control select2"
                                        data-placeholder="--Select Employee--" required>
                                    <option>-Select Employee-</option>
                                    @foreach ($employees as $id => $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
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
                        <div class="product-search" style="position: absolute;z-index: 999;background: white;"></div>
                    </div>
                    <div class="ajax-loader" style="visibility: hidden;">
                        <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 2%;position: relative;right: 54px;top: 7px;"/>
                    </div>
                </div>


                @include('sales.sales._inc.account-modal')
                <input type="hidden" name="source" value="Website">
                <input type="hidden" name="sale_from" value="New Sale">
                <input type="hidden" name="customer_id" class="customer_id" value="{{ customerId() }}">
                <input type="hidden" name="branch_id" class="branch_id" value="">
                @if(auth()->user()->type == 'owner')
                <input type="hidden" name="inv_prefix" class="inv_prefix" value="S">
                @else
                <input type="hidden" name="inv_prefix" class="inv_prefix" value="{{ $branches->first()->short_name }}">
                @endif
                <input type="hidden" name="no_due_mandatory" class="no_due_mandatory" value="{{ $business_setting->is_mandatory_no_due ?? 0 }}">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered pos_table" id="pos-table" style="background-color: white; color: black !important; margin-bottom: 0px !important">
                                <thead style="border-bottom: 3px solid #346cb0 !important;" class="pos_thead">
                                    <tr style="border: none; background: #dfdfdf !important; color: black !important;" class="pos_tr">
                                        <th class="text-center" width="3%" class="text-center">SL</th>
                                        <th class="text-center" width="15%">Product Name</th>
                                        <th class="text-center" width="10%">Product Code</th>
                                        <th class="text-center" width="5%">Stock</th>
                                        <th class="text-center" width="10%">Product Description</th>
                                        <th class="text-center" width="10%" class="text-center">Quantity</th>
                                        <th class="text-center" width="5%" class="text-center">Unit</th>
                                        <th class="text-center" width="10%" class="text-right">Sale Price</th>
                                        <th class="text-center" style="width: 15%" class="text-center">Discount ৳</th>
                                        <th class="text-center" width="10%" class="text-right">VAT %
                                            <input type="hidden"  name="default_vat"  id="default_vat" class="default_vat" value="{{ $business_setting->vat ?? 0 }}">
                                        </th>
                                        <th class="text-center" width="10%" class="text-right">Sub Total</th>
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
                                            <td style="width: 20%"> {{ old('product_titles')[$key] }} </td>
                                            <td style="width: 15%"> {{ old('product_codes')[$key] }} </td>
                                            <td style="width: 15%">
                                                <input type="text" name="description[]" class="form-control"
                                                       value="{{ old('description')[$key] }}"
                                                       autocomplete="off">
                                            </td>
                                            <td style="width: 5%">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <a href="javascript:void(0)" onclick="DecreaseOrder(this)"><i
                                                                    class="ace-icon fa fa-minus"
                                                                    style="color: rgb(126, 3, 3)"></i></a>
                                                        </span>
                                                        <input class="form-control product_qty" type="number"
                                                               onkeyup="updateCart(this)" name="product_qty[]"
                                                               value="{{ old('product_qty')[$key] }}">
                                                        <span class="input-group-addon">
                                                            <a href="javascript:void(0)" onclick="IncreaseOrder(this)"><i
                                                                    class="ace-icon fa fa-plus"></i></a>
                                                        </span>
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
                                                   id="subtotal" value="0" readonly="">
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
                                            <input type="number" class="form-control text-right discount" name="discount" value="0" id="discount" autocomplete="off" readonly>
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
                                                   name="paid_amount" id="paid_amount" autocomplete="off" required readonly />
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
                                            <input type="number" class="form-control total text-right" name="payable_amount"
                                                   id="payable" readonly>
                                        </div>
                                    </td>

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
                                                    Refer Customer
                                                </label>
                                            </div>
                                            <select name="refer_customer_id" id="refer_customer"
                                                    class="form-control select2"
                                                    data-placeholder="--Select Refer Customer--">
                                                <option></option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>

                                </tr>


                                <tr style="background-color: #d7d7d7 !important; color: black">

                                    <td style="width: 35%; padding: 3px !important; border: 0px !important;">

                                      <div class="radio" style="margin-top: 8px !important;">
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
                                    </td>

                                    <td style="padding: 10px;">
                                        <div class="radio">
                                            <label style="padding-left: 10px">
                                                <input name="" class="point" type="checkbox" id="test3">
                                                <label for="test3" style="font-size: 18px; font-weight: 500; color: #000000 !important">Use Point : <span class="customer_point">{{ customerPoint() }}</span></label>
                                            </label>

                                            <span style="float: right;font-size: 15px;color: #666666; display:none;" class="point_show">
                                                <input type="hidden" name="customer_point" id="customer_point">
                                                <input type="number" name="point" class="form-control" value="" id="use_point" placeholder="Enter Point" onkeyup="updateDiscount(this)" style="height: 28px !important;">
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="radio" style="padding: 10px;">
                                            <label style="padding-left: 10px;">
                                                <input name="is_cod" class="" type="checkbox" id="test2">
                                                <label for="test2"><b style="color: black">Cash on Delivery</b></label>
                                            </label>

                                        </div>
                                    </td>

                                </tr>

                                <tr>
                                    <td></td>
                                    <td></td>

                                    <td style="width: 33.33%;padding: 5px">
                                        <div class="col-md-6" style="padding: 0 !important;">
                                            @if (hasPermission('dokani.sales.index', $slugs))
                                                <a href="{{ route('dokani.sales.index') }}" class="btn btn-sm btn-success btn-block" style="width: 100%; border-radius: 0px !important; background-color: #cdffbe !important; border-color: #cdffbe; color: black !important;">
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



                        </div>
                    </div>
                </div>

            </form>

        </div>




    </div>

@endsection

@section('js')
{{--    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>--}}
<script src="{{ asset('assets/js/jquery.ba-throttle-debounce.js') }}"></script>
    @include('orders._inc.script')

    @include('partials._account-balance-script')

    <script>

        $(document).ready(function(){
            let id = $('#customer').find(':selected').val();
            if (!id){
                $(".save-btn").prop("disabled", true);
                $(".save-btn").attr("title","Please Select a Customer");
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
            let product_price = _this.closest('.single-product-li').find('.product-price').text();
            let vat = _this.closest('.single-product-li').find('.product-vat').text();
            let buy_price = _this.closest('.single-product-li').find('.buy-price').text();
            let description = _this.closest('.single-product-li').find('.description').text();
            let unit = _this.closest('.single-product-li').find('.product-unit').text();
            let stock = _this.closest('.single-product-li').find('.product-stock').text();

            let table = $('#pos-table tbody');
            let length = $('#pos-table tbody tr').length + 1;

            if (description == 'null'){
                description = ''
            }

            // add item into the sale table
            addProduct(length, product_id, product_title, product_code, 1, product_price, vat,buy_price, unit, stock, description, table)
        }

        $('#customer').on('change', function() {

            var pre_due = $(this).find(':selected').data('pre_due');

            $('#previous_due').val(pre_due);
            calculatePayable()

            let id = $(this).find(':selected').val();
            $('.customer_id').val(id);

            if (!id){
                $(".save-btn").prop("disabled", true);
                $(".save-btn").attr("title","Please Select a Customer");
            }
            else {
                $(".save-btn").prop("disabled", false);
                $(".save-btn").attr("title","Submit");
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
            let customer_id         = $('#customer').find(':selected').val();
            let refer_customer_id   = $(this).find(':selected').val();

            if (customer_id == refer_customer_id){

                toastr.warning('Do not refer same customer')
                $("#refer_customer > option:selected").attr("selected",false);
                $('#refer_customer > option[value=""]').prop('selected', true);
            }
        })


        $('#branch_id').on('change', function () {

            let branch_id         = $('#branch_id').find(':selected').val();

            $('.branch_id').val(branch_id);

            //     Branch wise Employee
            let inv_prefix = $(this).find('option:selected').data('inv_prefix');
            let employees = $(this).find('option:selected').data('employees');

            $('.inv_prefix').val(inv_prefix);

            $('#employee_id').empty();
            $('#employee_id').append(
                `<option value="" selected>- Select -</option>`);

            $(employees).each(function (index, employee) {
                $('#employee_id').append(
                    `<option value="${ employee.id }">${ employee.name }</option>`);
            })

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


<script>

    $('#refer_by').change(function(){

        let value = $("input[type=radio][name=refer_by]:checked").val()

        if (value == 'user'){
            $('#user_refer').show()
            $('#customer_refer').hide()
        }
        else if (value == 'customer') {
            $('#user_refer').hide()
            $('#customer_refer').show()
        }
    });
</script>


<script>
    $(document).ready(function() {

        let i = 0
        let rowno = 0;
        $("#addrow").on("click", function() {

            rowno = $("#accountTable tbody tr").length + 1;

            const rowItem = `<tr>
                                <th width="30%">
                                        <select name="account_ids[]" id="account_id${rowno}" style="width: 100%"
                                                class="form-control select2" data-placeholder="- Select Account -"
                                                aria-hidden="true" onchange="bankStatement(${rowno})" required>
                                            <option value=""></option>
                                            @foreach (accountInfo() as $type)
                                            <option value="{{ $type->id }}" data-balance = "{{ $type->balance }}"
                                               data-name ="{{ $type->name }}"
                                               {{ $type->name == 'Cash' ? 'selected' : '' }}>
                                               {{ $type->name .' ('.$type->balance.')' }}
                                            </option>
                                            @endforeach
                                            </select>
                                            </th>

    <th style="display: flex">
            <input name="amount[]" type="text" onkeyup="totalAmount()"
                   placeholder="Enter Amount" class="form-control pamount" required/>&nbsp;
            <input name="check_no[]" type="text" style="display: none"
                   placeholder="Enter Check No" class="form-control bankAccount${rowno}"/>&nbsp;
            <input name="check_date[]" type="text" style="display: none" autocomplete="off"
                   placeholder="Enter Check Date" class="form-control date-picker bankAccount${rowno}"/>
        </th>

    <th width="7%">
    <button type="button" class="remove-row" style="background-color: transparent;border: none;" title="Remove"
             ><i class="far fa-times-circle fa-lg text-danger rotate"></i></button>
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


    function totalAmount() {
        let total_val = Number($('.total_amount').val());
        let paid_amount = Number($('#paid_amount').val());
        var total = 0

        $('.pamount').each(function() {
            let amount = Number($(this).val())
            total += amount
            if (total_val > paid_amount){

                toastr.warning('This amount over the paid amount')

            }
            // $('#paid_amount').val(total)
        })
        $(".total_amount").val(total);
        $(".paid_amount").text(total);
        $("#paid_amount").val(total);

        calculateDueAmount()
    }


    $('.save-btn').on('click', function(e) {
            e.preventDefault();
            let account = $('#account_id1');
            let amount = $('.pamount');
            let customer = $('#customer');
            let branch = $('#branch_id');

            let noDueMandatory = $('.no_due_mandatory');
            let dueAmount = $('#due_amount');

            let error = 0;

            if(Number(noDueMandatory.val()) == 1){
                if(Number(dueAmount.val()) > 0){
                    toastr.warning('Due Is not Applicable');
                    error +=1;
                }
            }
            if(account.val() == ""){
                toastr.warning('Select a Account');
                error +=1;
            }
            if(amount.val() == ""){
                toastr.warning('Select a Payment Amount');
                error +=1;
            }
            if(customer.val() == ""){
                toastr.warning('Select a Customer');
                error +=1;
            }
            // if(branch.val() == ""){
            //     toastr.warning('Select a Branch');
            //     error +=1;
            // }

            if(error<1){
                setTimeout(function() {
                    window.location.reload();
                },1000);
                $('#saleSubmitForm').submit();
            }
        });




</script>


@endsection

