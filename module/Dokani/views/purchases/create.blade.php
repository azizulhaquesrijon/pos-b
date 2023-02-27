@extends('layouts.master')
{{-- Add (POS) Purchase --}}
@section('title', 'Purchase')
@section('page-header')
    <i class="fa fa-plus"></i> Purchase Create
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

        .calculation_tr{
            width: 16%;
            padding: 3px !important;
            border: 0px !important;
            font-size: 14px;
            font-weight: 500;
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

        .modal-open .modal {
            padding-right: 287px !important;
        }


    </style>
@endpush

@section('content')


@include('partials._supplier-form')

    <div class="row">

        <form action="{{ route('dokani.purchases.store') }}" method="post" id="purchaseFormSubmit" target="_blank">
            @csrf

            {{-- ACCOUNT MODEL  --}}
            @include('purchases._inc.pos-modal')

            @include('purchases._inc.pos-supplier-modal')

            <div class="{{ optional(auth()->user()->businessProfile)->has_expiry_date == 0 ? 'col-md-7' : 'col-md-6' }}">

                @include('partials._alert_message')

                <!-- heading -->
                <div class="widget-box ui-sortable-handle clearfix">
                    <div class="widget-header widget-header-small" style="color: black !important;">

                        <table style="width: 100%">

                            <tr>
                                <td class="calculation_tr">
                                    <div class="row">
                                        <div class="col-md-3">
                                            Supplier:
                                        </div>
                                        <div class="col-md-9">
                                            <span class="supplier_name" style="color: darkblue;"></span>
                                        </div>
                                    </div>

                                </td>

                                <td class="calculation_tr">
                                    <div class="row">
                                        <div class="col-md-3">
                                            Date:
                                        </div>
                                        <div class="col-md-9">
                                            <span class="pos_date" style="color: darkblue;">{{ now()->format('Y-m-d') }}</span>
                                        </div>
                                    </div>

                                </td>

                            </tr>

                        </table>
                        <div class="" style="position: absolute;right: 2px;top: 1px;">
                            <a href="#add-supplier" class="btn btn-primary btn-xs"  role="button" data-toggle="modal">
                                Select Supplier<i class="fa fa-user"></i>
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference_no">Reference No</label>
                                    <input type="text" class="form-control" name="reference_no" id="reference_no" placeholder="Reference No">
                                    <small class="reference_no_error" style="color: red"></small>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="pos-sale">
                        <table class="table table-striped" id="purchase-table">
                            <thead class="pos-thead">
                                <tr>
                                    <th style="width: 5%">SL</th>
                                    <th style="width: 20%">Product Name</th>
                                    <th style="width: 15%">BarCode</th>
                                    @if(optional(auth()->user()->businessProfile)->has_expiry_date == 0)
                                        <th style="width: 15%">Expiry Date</th>
                                    @endif
                                    <th style="width:20%" class="text-center">Quantity</th>
                                    <th style="width: 20%" class="text-center">Unit Cost</th>
                                    <th style="width: 20%">Sub Total</th>
                                    <th style="width: 5%;">
                                        <i class="far fa-times-circle fa-lg"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="pos-tbody" style="background-color: white; border-bottom: 2px solid #aaaaaa">
                                @if (old('product_ids'))
                                    @foreach (old('product_ids') as $key => $item)
                                        <tr class="mgrid pos-tr">
                                            <td style="width:5%">
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
                                            <td style="width:20%"> {{ old('expiry_at')[$key] }} </td>
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

                                            <td style="widht:10%"><strong
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


                            <!-- CALCULATE AREA -->
                            <tfoot class="pos-tfoot">

                                <tr class="pos-tr">

                                    <th class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Sub Total:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="payable_amount">0.00</span>
                                            </div>
                                        </div>

                                    </th>

                                    <th class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Previous Due:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="previous_due">0.00</span>
                                            </div>
                                        </div>

                                    </th>

                                    <th class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Discount:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="discount">0.00</span>
                                            </div>
                                        </div>

                                    </th>
                                </tr>
                                <tr>

                                    <th class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Paid Amount:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="paid_amount">0.00</span>
                                            </div>
                                        </div>

                                    </th>


                                    <th class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Due Amount:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="due_amount">0.00</span>
                                            </div>
                                        </div>

                                    </th>

                                    <input type="hidden" name="payable_amount" value="" id="grand_total">


                                    <th class="calculation_tr">
                                        <div class="row">
                                            <div class="col-md-7">
                                                Print:
                                            </div>
                                            <div class="col-md-5">
                                                <span class="print_method">
                                                    {{ optional($invoice)->invoice_type == 1 ? 'POS' : 'Normal' }}
                                                </span>
                                            </div>
                                        </div>

                                    </th>

                                </tr>
                            </tfoot>

                        </table>
{{--                        <div class="" style="position: absolute;right: 16px;bottom: 119px;">--}}
{{--                            <a href="#add-new" class="btn btn-success btn-xs" style="border-radius: 50%;" role="button" data-toggle="modal">--}}
{{--                                <i class="fa fa-eye"></i>--}}
{{--                            </a>--}}
{{--                        </div>--}}
                        <div class="col-md-6" style="padding: 0 !important;">
                            @if (hasPermission('dokani.purchases.index', $slugs))
                                <a href="{{ route('dokani.purchases.index') }}" class="btn btn-sm btn-success btn-block" style="width: 100%; border-radius: 0px !important; background-color: #cdffbe !important; border-color: #cdffbe; color: black !important;">
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

        <div class="{{ optional(auth()->user()->businessProfile)->has_expiry_date == 0 ? 'col-md-5' : 'col-md-6' }}">
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small" style="background: rgb(6, 53, 94)">



                    <div class="row" style="margin-top: 25px">

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

                <div class="product-list1">
                    @include('partials._purchase-card', ['data' => $products])
                </div>
            </div>

            <div class="space"></div>
        </div>
    </div>


@endsection

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/custom_js/purchase_table_field.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.ba-throttle-debounce.js') }}"></script>

    @include('purchases._inc.script')

    @include('partials._account-balance-condition-script')

    <script>
        $('#supplier').on('change', function() {

            var pre_due = $(this).find(':selected').data('pre_due')
            $('#previous_due').val(pre_due);
            $('.previous_due').text(pre_due);

            calculate();

        });

        $('#discount').on('keyup',function() {

            let option = $(this).val();
            $('.discount').text(option)
        });

        $('#supplier').on('change',function() {

            let option = $(this).find('option:selected').text();
            $('.supplier_name').text(option)
        });

        $('#pos_date').on('change',function() {

            let option = $(this).val();
            $('.pos_date').text(option)
        });


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

        function GetProduct(object) {
            let _this = $(object);
            let product_id = _this.closest('.single-product').find('.product_id').text();
            let product_title = _this.closest('.single-product').find('.product-title').text();
            let product_code = _this.closest('.single-product').find('.sku-code').text();
            let product_price = _this.closest('.single-product').find('.product-price').text();

            let table = $('#purchase-table tbody');
            let length = $('#purchase-table tbody tr').length + 1;

            let has_expiry_date = {{ optional(auth()->user()->businessProfile)->has_expiry_date ?? 1 }};
            let isExpireFieldAvailable = has_expiry_date == 1 ? false : true;

            // check item is added or not
            let is_item_added = true
            $('.tr_product_id').each(function(index, value) {
                if ($(this).val() == product_id) {
                    is_item_added = false;
                    let closest_tr = $(this).closest('.mgrid');
                    Increase($(this))
                    return false;
                }
            })

            if (is_item_added == true) {
                addItem(length, product_id, product_title, product_code, 1, product_price, table, isExpireFieldAvailable)
            }
        }

        function GetInfo(object) {

            let _this = $(object);
            let product_id = _this.closest('.single-product-li').find('.product_id').text();
            let product_title = _this.closest('.single-product-li').find('.product-title').text();
            let product_code = _this.closest('.single-product-li').find('.sku-code').text();
            let product_price = _this.closest('.single-product-li').find('.product-price').text();
            let table = $('#purchase-table tbody');
            let length = $('#purchase-table tbody tr').length + 1;

            // check item is added or not
            let has_expiry_date = {{ optional(auth()->user()->businessProfile)->has_expiry_date ?? 1 }};
            let isExpireFieldAvailable = has_expiry_date == 1 ? false : true;

            // check item is added or not
            let is_item_added = true
            $('.tr_product_id').each(function(index, value) {
                if ($(this).val() == product_id) {
                    is_item_added = false;
                    let closest_tr = $(this).closest('.mgrid');
                    Increase($(this))
                    return false;
                }
            })

            if (is_item_added == true) {
                addItem(length, product_id, product_title, product_code, 1, product_price, table, isExpireFieldAvailable)
            }

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
        //     let balance = Number($('.acc_balance').val());
        //     let paid_amount = Number($('#paid_amount').val());
        //
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




        // $('#reference_no').on('keyup', $.debounce(200, function(e) {
        //     let value = $(this).val();
        //     const route = `{{ route('dokani.check-purchase-refenrece-no-unique') }}`
        //     axios.get(route, {
        //         params: {
        //             value: value
        //         }
        //     })
        //     .then(function (response) {
        //         if(response.data.status == 0 && value != ''){
        //             $('.reference_no_error').text('This Reference No already exists');
        //             toastr.warning('This Reference No already exists');
        //         }else{
        //             $('.reference_no_error').text('');
        //         }
        //     })
        //     .catch(function (error) {
        //         console.log(error);
        //     })
        // }));

    </script>


    <script>
        $(document).ready(function() {

            let i = 0
            let rowno = 0;
            $("#addrow").on("click", function() {

                rowno = $("#accountTable tbody tr").length + 1;

                const rowItem = `<tr>
                                <th width="30%">
                                        <select name="account_ids[]" id="account_id${rowno}" style="width: 100%" required
                                                class="form-control select2" onchange="bankStatement(${rowno})" data-placeholder="- Select Account -" aria-hidden="true">
                                            <option value=""></option>
                                            @foreach (accountInfo() as $type)
                <option value="{{ $type->id }}" {{ $type->balance <= 0 ? 'disabled' : '' }}
                data-balance = "{{ $type->balance }}"
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

            calculate()

        }


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
                        $('#add-supplier').modal('hide');
                    }
                )
                //Delivery charge field focus
                keyboardShortcut(
                    {
                        selector: e,
                        key: 'q',
                        ctrl: true
                    },function() {
                        $('#discount').focus();
                    }
                )
                // Header modal open
                keyboardShortcut(
                    {
                        selector: e,
                        key: 'm',
                        ctrl: true
                    },function() {
                        $('#add-supplier').modal('show');
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
                        $('#add-supplier').modal('hide');
                        $('#add-new').modal('hide');
                    }
                )

            })
        })


        $('#purchaseFormSubmit').keypress((e) => {
            var supplier = $('#supplier').find(':selected').val()
            if (supplier){
                if (e.which === 13) {
                    $('#purchaseFormSubmit').submit();
                }
            }else {

                toastr.warning('Select a supplier')
            }

        })

        $('.save-btn').on('click', function(e) {
            e.preventDefault();

            let account_id = $('#account_id1');
            let amount = $('.pamount');
            let supplier = $('#supplier');
            let error = 0;

            let posTbody = $('.pos-tbody tr');

            if(posTbody.length < 1){
                toastr.warning('Please select at least one Product')
                error +=1;
            }
            if(account_id.val() == ""){
                toastr.warning('Please select an account')
                error +=1;
            }
            if(amount.val() == ""){
                toastr.warning('Type Payment Amount')
                error +=1;
            }
            if(supplier.val() == ""){
                toastr.warning('Select a supplier')
                error +=1;
            }

            if (error<1) {
                setTimeout(function() {
                    window.location.reload();
                },1000);
                $('#purchaseFormSubmit').submit();
            }
        });
    </script>

@endsection
