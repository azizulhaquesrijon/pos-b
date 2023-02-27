@extends('layouts.master')
@section('title', 'Sale Create')
@section('page-header')
    <i class="fa fa-plus"></i> Sale Create
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/pos/pos.css') }}" />
    <style>
        .list-group>p {
            color: black
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

    @include('partials._customer-form')


    <div class="row">


        <!-- Sale Create  -->
        <form id="saleSubmitForm" action="{{ route('dokani.sales.store') }}" method="post">
            @csrf

            <input type="hidden" name="source" value="Website">
            <div class="col-md-7">

                @include('partials._alert_message')

                <!-- heading -->
                <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                    <div class="widget-header widget-header-small">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="ace-icon fa fa-user"></i>
                                        </span>
                                        <select name="customer_id" id="customer"
                                            class="form-control chosen-select-100-percent"
                                            data-placeholder="--Select Customer--" required>
                                            <option></option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                        data-point = {{ $customer->point }}
                                                    {{ $customer->is_default == 1 ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <span class="input-group-addon">
                                            <a href="#" data-toggle="modal" data-target="#myModal2">
                                                <i class="ace-icon fa fa-plus-circle fa-lg"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="ace-icon fa fa-money"></i>
                                        </span>
                                        <select name="account_type_id" id="account_type_id"
                                            class="form-control chosen-select-100-percent"
                                            data-placeholder="--Select Account--" required>
                                            <option></option>
                                            @foreach (accountType() as $key => $item)
                                                <option value="{{ $key }}" {{ $item == 'Cash' ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-addon">
                                                <code class="balance">{{ cashBalance() }}</code>
                                            </span>
                                        <div class="ajax-loader-acc" style="visibility: hidden;">
                                            <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 7%;position: absolute;right: 36px; top: 1px;"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="ace-icon fa fa-sticky-note"></i> Note
                                        </span>
                                    <input type="text" name="note" class="form-control" placeholder="Type Account Note">
                                </div>
                            </div>

                            <div class="col-lg-5 col-lg-offset-1">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="ace-icon fa fa-calendar bigger-110"></i>
                                        </span>
                                        <input type="text" name="date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                            class="form-control date-picker" autocomplete="off">
                                    </div>

                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="ace-icon fa fa-user"></i>
                                        </span>
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
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="space"></div>
                    <div class="pos-sale">
                        <table class="table table-bordered" id="pos-table">
                            <thead>
                                <tr style="border: none">
                                    {{-- <th style="width: 5%">SL</th> --}}
                                    <th style="width: 15%">Product Name</th>
                                    <th style="width: 10%">Barcode</th>
                                    <th style="width: 18%" class="text-center">Quantity</th>
                                    <th style="width: 15%" class="text-center">Sale Price</th>
                                    <th style="width: 24%">Discount</th>
                                    <th style="width: 8%" class="text-center">VAT %</th>
                                    <th style="width: 15%">Subtotal</th>
                                    <th style="width: 5%;"><i class="fa fa-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody>
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
                                            <td style="width:20%"> {{ old('product_titles')[$key] }} </td>
                                            <td style="width:20%"> {{ old('product_codes')[$key] }} </td>
                                            <td style="width:20%">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <a href="#" onclick="Decrease(this)"><i
                                                                    class="ace-icon fa fa-minus"
                                                                    style="color: rgb(126, 3, 3)"></i></a>
                                                        </span>
                                                        <input class="form-control product_qty" type="number"
                                                            onkeyup="updateCart(this)" name="product_qty[]"
                                                            value="{{ old('product_qty')[$key] }}">
                                                        <span class="input-group-addon">
                                                            <a href="#" onclick="Increase(this)"><i
                                                                    class="ace-icon fa fa-plus"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width:20%">
                                                <input type="text" name="product_price[]" class="form-control product-cost"
                                                    onkeyup="updateCart(this)" value="{{ old('product_price')[$key] }}"
                                                    autocomplete="off">
                                            </td>
                                            <td style="widht:12%"><strong
                                                    class="product_vat">{{ old('product_vat')[$key] }}</strong></td>
                                            <td style="widht:12%"><strong
                                                    class="subtotal">{{ old('product_price')[$key] }}</strong></td>
                                            <td style="widht:5%">
                                                <a href="#" class="text-danger" onclick="removeField(this)">
                                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>




                            <!-- Calculate Area -->
                            <tfoot>
                                <tr>
                                    {{-- <th>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Total
                                                </label>
                                            </div>
                                            <input type="number" class="form-control total text-right" name="payable_amount" id="total" readonly>
                                        </div>
                                    </th> --}}
                                    <th>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Subtotal
                                                </label>
                                            </div>
                                            <input type="number" class="form-control total text-right" id="subtotal" readonly>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <label class="input-group-text"
                                                        style="width: 105px; text-align: left; height: 13px;">
                                                    Discount
                                                </label>
                                            </div>
                                            <input type="number" class="form-control text-right discount" name="discount" value="0" id="discount" autocomplete="off" readonly>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <label class="input-group-text"
                                                       style="width: 105px; text-align: left; height: 13px;">
                                                    Total VAT
                                                </label>
                                            </div>
                                            <input type="number" class="form-control total text-right" name="total_vat" id="total_vat" readonly>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Delivery Charge
                                                </label>
                                            </div>
                                            <input type="number" class="form-control text-right delivery_charge" name="delivery_charge" value="0" id="delivery_charge" autocomplete="off" />
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <label class="input-group-text" style="width: 105px; text-align: left; height: 13px;">
                                                    Payable
                                                </label>
                                            </div>
                                            <input type="number" class="form-control total text-right" name="payable_amount" id="total" readonly>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <label class="input-group-text"
                                                    style="width: 105px; text-align: left; height: 13px;">
                                                    Paid Amount
                                                </label>
                                            </div>
                                            <input type="number" class="form-control text-right paid_amount" name="paid_amount" id="paid_amount" autocomplete="off" required />
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <label class="input-group-text"
                                                        style="width: 105px; text-align: left; height: 13px;">
                                                    Change Amount
                                                </label>
                                            </div>
                                            <input type="number" class="form-control text-right change_amount" name="change_amount" id="change_amount" autocomplete="off"/>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <label class="input-group-text"
                                                    style="width: 105px; text-align: left; height: 13px;">
                                                    Due Amount
                                                </label>
                                            </div>
                                            <input type="number" class="form-control text-right due_amount" name="due_amount" id="due_amount" autocomplete="off" readonly />
                                        </div>
                                    </th>
                                    {{-- <th style="background: aquamarine;padding: 10px;"> --}}
                                    <th>
                                        <div class="radio">
                                            <label style="padding-left: 10px">
                                                <input name="" class="point" type="checkbox" id="test3">
                                                <label for="test3"><b>Use Point</b></label>
                                            </label>
                                            <span style="float: right;font-size: 15px;color: #666666; display:none;" class="point_show">
                                                <b>Customer Point: <span class="customer_point"></span></b><br>
                                                <input type="hidden" name="customer_point" id="customer_point" value="">
                                                <input type="number" name="point" class="form-control use_point" id="use_point" value="" onchange="updateDiscount(this)">
                                            </span>
                                        </div>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>


                        <div class="radio">
                            <label>
                                <input name="invoice_type" value="pos-invoice" type="radio" class="ace"
                                    {{ optional($invoice)->invoice_type == 1 ? 'checked' : '' }} required="required">
                                <span class="lbl"> POS Print</span>
                            </label>

                            <label>
                                <input name="invoice_type" value="normal-invoice" type="radio"
                                    {{ optional($invoice)->invoice_type != 1 ? 'checked' : '' }} class="ace">
                                <span class="lbl"> Normal Print</span>
                            </label>
                        </div>

                    </div>
                    <!-- INPUTS -->

                    <div class="row sell-pos">
                        <div class="row">

                            <div class="product-item">

                            </div>
                        </div>
                    </div>

                </div>
                <div class="space"></div>
                <div class="row px-1">
                    <div class="col-sm-6" style="padding: 0 !important;">
                        <button type="submit" id="submit_btn" class="btn btn-sm btn-primary btn-block save-btn"><i
                                class="fa fa-save"></i> Save</button>
                    </div>
                    <div class="col-sm-6" style="padding: 0 !important;">
                        @if (hasPermission('dokani.sales.index', $slugs))
                        <a href="{{ route('dokani.sales.index') }}" class="btn btn-sm btn-success btn-block"
                            style="width: 100%">List</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>



        <div class="col-md-5">
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small" style="background: rgb(6, 53, 94)">
                    <div class="row" style="margin-top: 10px">
                        <div class="col-lg-6">
                            <div class="form-group">

                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ace-icon fa fa-barcode"></i>
                                    </span>
                                    <input class="form-control product-search-input" type="text" placeholder="Barcode/Product Name...">
{{--                                    onkeyup="getProductInfo(this,event)" placeholder="Barcode/Product Name...">--}}

                                    <div class="loader" style="right:0;display: none"></div>
                                </div>

                                <div class="ajax-loader" style="visibility: hidden;">
                                    <img src="{{ asset('assets/images/loading2.gif') }}" class="img-responsive" style="width: 29px;position: relative;left: 325px;bottom: 31px;z-index: 2;"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{-- <label for="exampleInputName2">Category</label> --}}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ace-icon fa fa-list"></i>
                                    </span>
                                    <select name="category_id" id="category" class="form-control chosen-select-100-percent"
                                        data-placeholder="--Select Category--" onchange="getProductByCategory(this)">
                                        <option value="">--All Category--</option>
                                        @foreach ($categories as $id => $name)
                                            <option value="{{ $id }}"> {{ $name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="product-search"></div>
                <div class="product-list">
                    @include('partials/_card', ['products' => $products])
                </div>

                <div class="space"></div>
            </div>
        </div>
    </div>


@endsection

@section('js')

    <script src="{{ asset('assets/custom_js/table_field.js') }}?v={{ time() }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/js/jquery.ba-throttle-debounce.js') }}"></script>

    @include('sales.sales._inc.script')
    @include('partials._account-balance-script')

    <script>
        // $('#customer').on('change', function() {
        //
        //     var pre_due = $(this).find(':selected').data('pre_due');
        //     $('#previous_due').val(pre_due);
        //     calculate();
        // });

        function calculate_total() {

            var total = parseFloat($('#total').val());
            var pre_due = parseFloat($('#previous_due').val());
            var discount = parseFloat($('#discount').val());

            var grand_total = (total + pre_due) - discount;

            $('#grand_total').val(grand_total);


        }

        function GetProduct(object) {
            let _this = $(object);

            let product_id = _this.closest('.single-product').find('.product_id').text();
            let product_title = _this.closest('.single-product').find('.product-title').text();

            let product_code = _this.closest('.single-product').find('.sku-code').text();
            let product_price = _this.closest('.single-product').find('.product-price').text();
            let vat = _this.closest('.single-product').find('.product-vat').text();

            let table = $('#pos-table tbody');
            let length = $('#pos-table tbody tr').length + 1;

            // add item into the sale table
            addItem(length, product_id, product_title, product_code, 1, product_price,vat , table)
        }

        function GetInfo(object) {
            let _this = $(object);

            let product_id = _this.closest('.single-product-li').find('.product_id').text();
            let product_title = _this.closest('.single-product-li').find('.product-title').text();

            let product_code = _this.closest('.single-product-li').find('.sku-code').text();
            let product_price = _this.closest('.single-product-li').find('.product-price').text();
            let vat = _this.closest('.single-product-li').find('.product-vat').text();
            let table = $('#pos-table tbody');
            let length = $('#pos-table tbody tr').length + 1;

            // add item into the sale table
            addItem(length, product_id, product_title, product_code, 1, product_price, vat, table)
        }



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



        $('#refer_customer').on('change', function () {
            let customer_id = $('#customer').find(':selected').val();
            let refer_customer_id = $(this).find(':selected').val();

            if (customer_id == refer_customer_id){

                toastr.warning('Do not refer same customer')
                $("#refer_customer > option:selected").attr("selected",false);
                $('#refer_customer > option[value=""]').prop('selected', true);
            }
        })


        $(".point").change(function(){

            $('#use_point').on('keyup', function () {

                let customer_point = Number($('#customer').find(':selected').data('point'));

                if (customer_point){
                    let use_point = Number($(this).val());
                    let new_point = customer_point - use_point;

                    if (new_point < 0){

                        toastr.warning('No available point')
                        $(this).val(customer_point)
                        $('.customer_point').text(0);
                        $('#customer_point').val(0);

                    }else {

                        $('.customer_point').text(new_point);
                        $('#customer_point').val(new_point);
                    }

                }
                else {
                    toastr.warning('Select customer first')
                    $(this).val(null)
                }



            })

            if( $(this).is(":checked") ){

                $('.point_show').show()
            }
            else {
                $('.point_show').hide()
                $('#use_point').val(null)
                $('#customer_point').text(customer_point);
            }
        });





        function updateDiscount() {

            let point = Number($('#use_point').val());

            console.log(point)
            var discount = Number($('#discount').val());

            let point_value = '{{ optional($point)->point_value }}'

            let total_discount = (point * point_value) + discount

            $('#discount').val(total_discount)
            calculate()

        }

    </script>

@endsection
