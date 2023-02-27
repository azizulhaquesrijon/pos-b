@extends('layouts.master')
@section('title', 'Sale Create')
@section('page-header')
    <i class="fa fa-plus"></i> Sale Create
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/pos/pos.css') }}"/>
    <style>
        body {
            color: black !important
        }
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


        .modal-open .modal {
            padding-right: 287px !important;
        }


        .accordion {
            background-color: #d0d1ff;;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            transition: 0.4s;
        }

        .active, .accordion:hover {
            background-color: #ccc;
        }

        .accordion:after {
            content: '\002B';
            color: #777;
            font-weight: bold;
            float: right;
            margin-left: 5px;
        }

        .active:after {
            content: "\2212";
        }

        .panel {
            padding: 0 18px;
            background-color: white;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
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



        .calculation_tr{
            width: 16%;
            padding: 3px !important;
            border: 0px !important;
            font-size: 14px;
            font-weight: 500;
        }


        .checkbox, .radio {
            position: relative;
            display: block;
            margin-top: 0px !important;
            margin-bottom: 0px !important;
        }


        input[type=checkbox].ace.ace-switch+.lbl::before {

            height: 16px !important;
        }

        input[type=checkbox].ace.ace-switch.ace-switch-3+.lbl::after {
            line-height: 19px !important;
            height: 72% !important;
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
            padding: 10px !important;
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


    <div class="row">


        <!-- Sale Create  -->
        <form id="saleSubmitForm" action="{{ route('dokani.sales.store') }}" method="post" target="_blank">
            @csrf

            <input type="hidden" name="source" value="Website">
            <input type="hidden" name="sale_from" value="POS Sale">
            <input type="hidden" name="pos_method" value="POS">
            @if(auth()->user()->type == 'owner')
            <input type="hidden" name="inv_prefix" class="inv_prefix" value="S">
            @else
            <input type="hidden" name="inv_prefix" class="inv_prefix" value="{{ $branches->first()->short_name }}">
            @endif
            <input type="hidden" name="no_due_mandatory" class="no_due_mandatory" value="{{ $business_setting->is_mandatory_no_due ?? 0 }}">
            <div class="col-md-8">

            @include('partials._alert_message')

            {{-- ACCOUNT MODEL  --}}
            @include('sales.sales._inc.pos-modal')


            @include('sales.sales._inc.pos-customer-modal')

            <!-- heading -->
                <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">

                    <div class="widget-header widget-header-small" style="color: black;border-bottom: 2px solid black">

                        @if (hasBranchSystem())
                            <div class="col-md-4" style="position: relative;">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="ace-icon fa fa-list"></i>
                                        </span>
                                        <select id="branch_id" name="branch_id"
                                                class="form-control chosen-select-100-percent"
                                                data-placeholder="--Select Branch--" onchange="getProductByCategory()" required>
                                            @if(auth()->user()->type == "owner")
                                                <option value="" data-inv_prefix="S">Main Branch</option>
                                            @endif
                                            @foreach ($branches as $id => $branch)
                                                <option value="{{ $branch->id }}" data-employees="{{ $branch->employee }}" data-inv_prefix="{{ $branch->short_name }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                            <div class="col-md-4 branch-wise-employee" style="position: relative;">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="ace-icon fa fa-users"></i>
                                        </span>
                                        <select id="employee_id" name="employee_id"
                                                class="form-control select2"
                                                data-placeholder="--Sale By Employee--">
                                                <option value="">-Select-</option>
                                            @foreach ($employees as $id => $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                        <div class="" style="position: absolute;right: 3px;top: 10px;">
                            <a href="#add-customer" class="btn btn-primary btn-xs" role="button" data-toggle="modal">
                                Select Customer <i class="fa fa-user"></i>
                            </a>
                        </div>

                        <table style="width: 100%">

                            <tr>
                                <td class="calculation_tr">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Customer:
                                        </div>
                                        <div class="col-md-8">
                                            <span class="customer_name" style="color: darkblue;">{{ optional(defaultCustomer())->name }} ⇒ {{ optional(defaultCustomer())->mobile }}</span>
                                        </div>
                                    </div>

                                </td>
                                <td class="calculation_tr">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Refer Customer:
                                        </div>
                                        <div class="col-md-8">
                                            <span class="refer_customer_name" style="color: darkblue;"></span>
                                        </div>
                                    </div>

                                </td>
                                <td class="calculation_tr">
                                    <div class="row">
                                        <div class="col-md-3">
                                            Note:
                                        </div>
                                        <div class="col-md-9">
                                            <span class="note" style="color: darkblue;"></span>
                                        </div>
                                    </div>

                                </td>
                                <td class="calculation_tr">
                                    <div class="row">
                                        <div class="col-md-3">
                                            Date:
                                        </div>
                                        <div class="col-md-9">
                                            <span class="pos_date" style="color: darkblue;">{{ now() }}</span>
                                        </div>
                                    </div>

                                </td>

                            </tr>

                        </table>

                    </div>


                    <div class="pos-sale">
                        <table class="table table-bordered" id="pos-table" style="background-color: white; color: black !important; margin-bottom: 0px !important">
                            <thead class="pos-thead">
                            <tr style="border: none; background: #dfdfdf !important; color: black !important;" class="pos-tr">
                                <th class="text-center" style="width: 15%">Product Name</th>
                                <th class="text-center" style="width: 10%">Barcode</th>
                                <th class="text-center" style="width: 8%">Stock</th>
                                <th class="text-center" style="width: 18%" class="text-center">Quantity</th>
                                <th class="text-center" style="width: 15%" class="text-center">Sale Price</th>
                                <th class="text-center" style="width: 24%">Discount</th>
                                <th class="text-center" style="width: 8%" class="text-center">VAT %
                                    <input type="hidden"  name="default_vat"  id="default_vat" class="default_vat" value="{{ $business_setting->vat ?? 0 }}">
                                </th>
                                <th style="width: 15%">Subtotal</th>
                                <th style="width: 2%;"><i class="far fa-times-circle fa-lg"></i></th>
                            </tr>
                            </thead>
                            <tbody style="color: black !important" class="pos-tbody">
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

                        </table>

{{--                        <div class="" style="position: absolute;right: 16px;bottom: 97px;">--}}
{{--                            <a href="#add-new" class="btn btn-success btn-xs" style="border-radius: 50%;" role="button" data-toggle="modal">--}}
{{--                                <i class="fa fa-eye"></i>--}}
{{--                            </a>--}}
{{--                        </div>--}}


                        <table style="width: 100%">

                            <tfoot>

                            <tr>
                                <td class="calculation_tr">
                                    <div class="row">
                                        <div class="col-md-7">
                                            Subtotal:
                                        </div>
                                        <div class="col-md-5">
                                            <span class="pos-subtotal">0.00</span>
                                            <input type="hidden" name="sub_total" class="form-control text-right" id="all_subtotal">
                                        </div>
                                    </div>

                                </td>
                                <td class="calculation_tr">
                                    <div class="row">
                                        <div class="col-md-7">
                                            Payable:
                                        </div>
                                        <div class="col-md-5">
                                            <span class="payable">0.00</span>
                                        </div>
                                    </div>

                                </td>
                                <td class="calculation_tr">
                                    <div class="row">
                                        <div class="col-md-7">
                                            VAT Amount:
                                        </div>
                                        <div class="col-md-5">
                                            <span class="total_vat">0.00</span>
                                        </div>
                                    </div>

                                </td>

                                <td class="calculation_tr">
                                    <div class="row">
                                        <div class="col-md-7">
                                            Paid Amount:
                                        </div>
                                        <div class="col-md-5">
                                            <span class="paid_amount">0.00</span>
                                        </div>
                                    </div>

                                </td>

                            </tr>

                            <tr>

                                <td class="calculation_tr">
                                    <div class="row">
                                        <div class="col-md-7">
                                            Discount:
                                        </div>
                                        <div class="col-md-5">
                                            <span class="discount">0.00</span>
                                        </div>
                                    </div>

                                </td>

                                <td class="calculation_tr">

                                    <div class="row">

                                        <div class="col-md-7">
                                            Due Amount:
                                        </div>
                                        <div class="col-md-5">
                                            <span class="due_amount">0.00</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="calculation_tr">

                                    <div class="row">

                                        <div class="col-md-7">
                                            Delivery Charge:
                                        </div>
                                        <div class="col-md-5">
                                            <span class="delivery_charge">0.00</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="calculation_tr" style="background-color: #a8ceee">

                                    <div class="row">

                                        <div class="col-md-6">
                                            Point: <span class="used_point">0.00</span>
                                        </div>
                                        <div class="col-md-6">
                                            Print: <span class="print_method">
                                                {{ optional($invoice)->invoice_type == 1 ? 'POS' : 'Normal' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>



                            </tr>

                            </tfoot>
                        </table>


                        <div class="col-md-6" style="padding: 0 !important;">
                            @if (hasPermission('dokani.sales.index', $slugs))
                                <a href="{{ route('dokani.sales.index') }}" class="btn btn-sm btn-success btn-block" style="width: 100%; border-radius: 0px !important; background-color: #cdffbe !important; border-color: #cdffbe; color: black !important;">
                                    <i class="fas fa-list"></i> LIST
                                </a>
                            @endif
                        </div>
                        <div class="col-md-6" style="padding: 0 !important;">
                            <a href="#add-new" class="btn btn-sm btn-primary btn-block" role="button" data-toggle="modal" style="background-color: #0044ff !important; border-color: #0044ff !important; border-radius: 0px !important;">
                                <i class="far fa-money"></i> PAY
                            </a>
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
            </div>
        </form>


        <div class="col-md-4">
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small" style="background: #ffffff !important; border-bottom: 3px solid #0030d1">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-barcode"></i>
                                </span>
                                <input class="form-control product-search-input" type="text" placeholder="Barcode/Product Name...">
                                <div class="loader" style="right:0;display: none"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-list"></i>
                                </span>
                                <select name="category_id" id="category" class="product-searchform-control chosen-select-100-percent"
                                        data-placeholder="--Select Category--" onchange="getProductByCategory()">
                                    <option value="">--All Category--</option>
                                    @foreach ($categories as $id => $name)
                                        <option value="{{ $id }}"> {{ $name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="product-search"></div>
                <div class="product-list" style="background: white !important">
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

            let stock = _this.closest('.single-product').find('.product_stock').text();

            let table = $('#pos-table tbody');
            let length = $('#pos-table tbody tr').length + 1;

            // add item into the sale table
            addItem(length, product_id, product_title, product_code, 1, product_price,vat, stock , table)
            calculateSubtotal()
        }

        function GetInfo(object) {
            let _this = $(object);

            let product_id = _this.closest('.single-product-li').find('.product_id').text();
            let product_title = _this.closest('.single-product-li').find('.product-title').text();

            let product_code = _this.closest('.single-product-li').find('.sku-code').text();
            let product_price = _this.closest('.single-product-li').find('.product-price').text();
            let vat = _this.closest('.single-product-li').find('.product-vat').text();
            let stock = _this.closest('.single-product-li').find('.product-stock').text();
            let table = $('#pos-table tbody');
            let length = $('#pos-table tbody tr').length + 1;

            // add item into the sale table
            addItem(length, product_id, product_title, product_code, 1, product_price, vat, stock, table)
        }

        // {{--$('#customerBtnn').click(function() {--}}

            // let name = $('#name').val()
            // let mobile = $('#mobile').val()
            // let address = $('#customer_address').val()
            // let current_balance = $('#opening_due').val()
            // let refer_by_customer_id = $('#refer_by_customer_id').find(':selected').val();
            // let refer_by_user_id = $('#refer_by_user_id').find(':selected').val();
            // let cus_area_id = $('#cus_area_id').find(':selected').val();
            // let cus_category_id = $('#cus_category_id').find(':selected').val();
            // console.log(cus_category_id)
            // $.ajax({
                // type:'GET',
                // url: "{{ url('dokani/customer/create/ajax') }}",
                // data: {

                    // name: $('#name').val(),
                    // mobile: $('#mobile').val(),
                    // address: $('#customer_address').val(),
                    // opening_due: $('#opening_due').val(),
                    // refer_by_customer_id: $('#refer_by_customer_id').find(':selected').val(),
                    // cus_area_id: $('#cus_area_id').find(':selected').val(),
                    // cus_category_id: $('#cus_category_id').find(':selected').val()
                // },
                // beforeSend: function(){
                //     $('.ajax-loader').css("visibility", "visible");
                // },
                // success:function(data) {
                    // console.log(data)
                // },
                // complete: function(){
                //     $('.ajax-loader').css("visibility", "hidden");
                // }
            // });
        // });




        // $('#customerBtn').click(function() {
            // if ($('#name').val() != '') {
                // $('#name').css('border', '1px solid gray');
                // $('#customerBtn').prop('disabled', true);
                // $('#loader').addClass('loader')
                // setTimeout($.post('{!! route('dokani.customers.store') !!}', $('form#customer-form').serialize(), function(
                    // response) {
                        // let data = response.data;

                        // if (data != []) {
                            // $('#customer').append('<option value=' + data.id + ' selected>' + data.name +
                                // '</option>')
                            // $("#customer").trigger("chosen:updated");
                            // $('#myModal2').modal('hide');
                            // $('#customerBtn').prop('disabled', false);
                            // $('#name').val('');
                            // $('#loader').removeClass('loader')
                        // } else {
                            // $('#loader').removeClass('loader')
                        // }

                    // },
                    // 'json'
                // ), 3000);

            // } else {
                // $('#loader').removeClass('loader')
                // $('#name').css('border', '1px solid red');
                // console.log('alert')
            // }
        // });



        $('#customer').on('change', function() {

            var point = $(this).find(':selected').data('point');
            var discount = $(this).find(':selected').data('discount');
            let option = $(this).find('option:selected').text();

            $('.customer_name').text(option);

            $('.customer_own_discount').val(discount);

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

            // $('#use_point').on('keyup', function () {
            //
            //     let customer_point = Number($('#customer').find(':selected').data('point'));
            //
            //     if (customer_point){
            //         let use_point = Number($(this).val());
            //         let new_point = customer_point - use_point;
            //
            //         if (new_point < 0){
            //
            //             toastr.warning('No available point')
            //             $(this).val(customer_point)
            //             $('.customer_point').text(0);
            //             $('#customer_point').val(0);
            //
            //         }else {
            //
            //             $('.customer_point').text(new_point);
            //             $('#customer_point').val(new_point);
            //         }
            //
            //     }
            //     else {
            //         toastr.warning('Select customer first')
            //         $(this).val(null)
            //     }
            //
            //
            //
            // })

            if( $(this).is(":checked") ){

                $('.point_show').show()
            }
            else {
                $('.point_show').hide()
            }
        });


        $('#pos_date').on('change',function() {

            let option = $(this).val();
            $('.pos_date').text(option)
        });


        $('#refer_customer').on('change',function() {

            let option = $(this).find('option:selected').text();
            $('.refer_customer_name').text(option)
        });

        $('#note').on('keyup',function() {

            let option = $(this).val();
            $('.note').text(option)
        });




        $('#branch_id').on('change',function() {

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
        });







        function updateDiscount(object)
        {
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
                    $('.used_point').text(point);
                    $('#customer_point').val(update_point);

                }


            }
            else {
                toastr.warning('Customer have no point')
                $(object).val(null)
            }



            calculateTotalDiscount()
        }




        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }


        $('.print').on('click',function () {
            if($("input[type='radio'].print").is(':checked')) {
                let value = $("input[type='radio'].print:checked").val();
                if (value == 'pos-invoice'){
                    $('.print_method').text('POS')
                }else if (value == 'normal-invoice'){
                    $('.print_method').text('Normal')
                }
            }
        })




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
                                    <th>
                                        <select name="account_ids[]" id="account_id${rowno}" style="width: 100%" required
                                                class="form-control select2" data-placeholder="- Select Account -"
                                                aria-hidden="true" onchange="bankStatement(${rowno})">
                                            <option value=""></option>
                                            @foreach (accountInfo() as $type)
                                                <option value="{{ $type->id }}" data-balance = "{{ $type->balance }}"
                                                data-name ="{{ $type->name }}">
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

                                        <input name="check_date[]" type="text" style="display: none"
                                           placeholder="Enter Check Date" class="form-control date-picker bankAccount${rowno}"/>
                                    </th>

                                    <th>
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




        function totalAmount(id) {
            let total_val = Number($('.total_amount').val());
            let paid_amount = Number($('#paid_amount').val());
            // let payable = Number($('#payable').val());
            let available_balance = Number($('#account_id'+id).find(':selected').data('balance'))
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

            // $(".change_amount").html(total - payable);
            // $("#change_amount").val(total - payable);

            calculateDueAmount()

        }





        // $('.delivery_charge_btn').click(function() {
        //
        //     let root = $(this).closest('div')
        //
        //     root.find('.delivery_charge').attr('type', 'text').focus()
        //
        //     $(this).css({"display" : "none"})
        // });
        //
        //
        // $('.delivery_charge').blur(function() {
        //
        //     let root = $(this).closest('div')
        //     root.find('.delivery_charge_btn').css({"display" : "block"})
        //     $(this).attr('type', 'hidden')
        //
        //
        // })
        //
        //
        // $('.paid_amount_btn').click(function() {
        //
        //     let root = $(this).closest('div')
        //
        //     root.find('.paid_amount').attr('type', 'text').focus()
        //
        //     $(this).css({"display" : "none"})
        // });
        //
        //
        // $('.paid_amount').blur(function() {
        //
        //     let root = $(this).closest('div')
        //     root.find('.paid_amount_btn').css({"display" : "block"})
        //     $(this).attr('type', 'hidden')
        //
        //
        // })




    </script>



    <!--- KEYBOARD SHORTCUT --->

    <script>

        $(document).ready(function(){
            $(document).keydown(function( e ){
                //Footer modal open
                keyboardShortcut(
                    {
                        selector: e,
                        key: 'b',
                        ctrl: true
                    },function() {
                        $('#add-new').modal('show');
                        $('#add-customer').modal('hide');
                    }
                )
                //Delivery charge field focus
                keyboardShortcut(
                    {
                        selector: e,
                        key: 'q',
                        ctrl: true
                    },function() {
                        $('#delivery_charge').focus();
                    }
                )
                // Header modal open
                keyboardShortcut(
                    {
                        selector: e,
                        key: 'm',
                        ctrl: true
                    },function() {
                        $('#add-customer').modal('show');
                        $('#add-new').modal('hide');
                    }
                )
                // Modal close
                keyboardShortcut(
                    {
                        selector: e,
                        key: 'x',
                        ctrl: true
                    },function() {
                        $('#add-customer').modal('hide');
                        $('#add-new').modal('hide');
                        $('#myModal2').modal('hide');
                    }
                )

                // Note field focus
                keyboardShortcut(
                    {
                        selector: e,
                        key: 'y',
                        ctrl: true
                    },function() {
                        $('#note').focus();
                    }
                )

                // Add Customer
                keyboardShortcut(
                    {
                        selector: e,
                        key: 'i',
                        ctrl: true
                    },function() {
                        $('#myModal2').modal('show');
                        $('#add-customer').modal('hide');
                        $('#add-new').modal('hide');
                    }
                )
            })
        })



        $('#saleSubmitForm').keypress((e) => {
            var customer = $('#customer').find(':selected').val();

            if (customer){
                if (e.which === 13) {
                    $('#saleSubmitForm').submit();
                }
            }else {

                toastr.warning('Select a customer')
            }
        })


        $('.save-btn').on('click', function(e) {
            e.preventDefault();
            let account = $('#account_id1');
            let amount = $('.pamount');
            let customer = $('#customer');

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

            if(error<1){
                setTimeout(function() {
                    window.location.reload();
                },1000);
                $('#saleSubmitForm').submit();
            }
        });




        $('.pos-date-picker').datepicker({
            autoclose: true,
            format:'yyyy-mm-dd H:m:A',
            todayHighlight: true
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });

    </script>




@endsection
