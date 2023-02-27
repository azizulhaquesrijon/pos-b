@extends('layouts.master')
@section('title', 'Order-Sale Create')
@section('page-header')
    <i class="fa fa-plus"></i> Order-Sale Create
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
    </style>
@endpush

@section('content')


    @include('partials._customer-form')
    <div class="main-content-inner">

        <div class="page-content">

            <!-- DYNAIC CONTENT FROM VIEWS -->
            <div class="page-header">
                <h4 class="page-title"><i class="fa fa-list"></i> Order-Sale Create</h4>
                <div class="btn-group">

                </div>
            </div>

            <form action="{{ route('dokani.order-sale') }}" method="POST">
                @csrf

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
                                        <option value="{{ $customer->id }}" {{ ($order->customer_id == $customer->id) ? 'selected' : '' }}
                                        data-pre_due="{{ $customer->balance }}"
                                                data-point = "{{ $customer->point }}">
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
                        <div class="product-search"></div>
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
                                    <th width="10%" class="text-center">Quantity</th>
                                    <th width="10%" class="text-right">Sale Price</th>
                                    <th width="10%" class="text-right">VAT %</th>
                                    <th width="10%" class="text-right">Total VAT</th>
                                    <th width="10%" class="text-right">Sub Total</th>
                                    <th width="2%" class="text-center"><i class="fa fa-trash"></i></th>
                                </tr>
                                </thead>

                                <tbody>
                                {{--                                @if (old('product_ids'))--}}
                                @foreach ($order->order_details as $key => $item)
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
                                            <input type="text" name="description[]" placeholder="Add Description"
                                                   value="{{ $item->description }}" class="form-control" autocomplete="off">
                                        </td>
                                        <td style="width: 15%">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input class="form-control product_qty" type="number"
                                                           onkeyup="updateCart(this)" name="product_qty[]"
                                                           value="{{ $item->quantity }}" style="width:123px">
                                                    <code style="position: absolute;top: 4px;">{{ optional($item->product)->unit->name }}</code>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 10%">
                                            <input type="text" name="product_price[]" class="form-control product-cost"
                                                   onkeyup="updateCart(this)" value="{{ number_format($item->price,2) }}"
                                                   autocomplete="off">
                                        </td>
                                        @php
                                            $total = $item->price * $item->quantity;
                                            $total_vat = ceil((($item->vat/100) * $item->price) * $item->quantity);
                                            $subtotal = $total + $total_vat ;
                                        @endphp
                                        <td style="width: 10%">
                                            <input type="hidden" name="product_vat[]" class="product_vat" value="{{ $item->vat }}">
                                            <strong class="product_vat">{{ number_format($item->vat,2) }}</strong></td>
                                        <td style="width: 5%"><strong
                                                class="sub_total_vat">{{ $total_vat }}</strong></td>
                                        <td style="width: 10%"><strong
                                                class="subtotal">{{ $subtotal }}</strong></td>
                                        <input type="hidden" name="subtotal[]"
                                               value="{{ $subtotal }}" />
                                        <td style="width: 2%">
                                            <a href="#" class="text-danger" onclick="removeField(this)">
                                                <i class="ace-icon fa fa-trash bigger-120"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                {{--                                @endif--}}
                                </tbody>


                            </table>

                            <table style="float: right; border: 1px solid #c6c5c5; width: 32%; padding: 10px">
                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="padding-right: 34px">
                                                Total Amount
                                            </span>
                                            <input type="text" class="form-control total text-right" name="payable_amount"
                                                   id="total" value="{{ $order->payable_amount }}" readonly="">
                                        </div>

                                    </th>

                                </tr>
                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="padding-right: 57px">
                                                Total VAT
                                            </span>
                                            <input type="number" class="form-control total text-right" name="total_vat"
                                                   id="total_vat" value="{{ round($order->total_vat,2) }}" readonly>
                                        </div>

                                    </th>

                                </tr>
                                <tr>

                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                Delivery Charge
                                            </span>
                                            <input type="number" class="form-control text-right delivery_charge"
                                                   name="delivery_charge" value="{{ number_format($order->delivery_charge,2) }}" id="delivery_charge" autocomplete="off"/>
                                        </div>
                                    </th>

                                </tr>

                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="padding-right: 35px">
                                                Previous Due
                                            </span>
                                            <input type="number" class="form-control total text-right" name="previous_due"
                                                   id="previous_due" value="{{ ceil(optional($order->customer)->balance) }}" readonly>
                                        </div>
                                    </th>

                                </tr>
                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="padding-right: 67px">
                                                Discount
                                            </span>
                                            <input type="text" class="form-control discount text-right"
                                                   placeholder="Discount" name="discount" value="{{ number_format($order->discount,2) }}" id="discount">
                                        </div>

                                    </th>

                                </tr>
                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="padding-right: 44px">
                                                Paid Amount
                                            </span>
                                            <input type="text" class="form-control paid_amount text-right" placeholder="Paid Amount"
                                                   name="paid_amount" id="paid_amount" value="{{ ceil($order->paid_amount) }}">
                                        </div>
                                    </th>
                                </tr>

                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="padding-right: 52px">
                                                Due Amount
                                            </span>
                                            <input type="text" class="form-control due_amount text-right"
                                                   placeholder="Due Amount" name="due_amount" id="due_amount" value="{{ number_format($order->due_amount) }}" readonly="">
                                        </div>
                                    </th>
                                </tr>

                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="padding-right: 52px">
                                                Refer Customer
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
                                    </th>
                                </tr>

                                <tr>
                                    <th style="width: 33.33%;padding: 5px">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="padding-right: 21px">
                                                Account Type <em class="text-danger">*</em>
                                            </span>
                                            <select name="account_id" id="account_type_id" class="form-control select2"
                                                    data-placeholder="-- Select Account Type --" required >
                                                <option value=""></option>
                                                @foreach (account() as $key => $type)
                                                    <option value="{{ $key }}">{{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div style="padding-left: 35%; font-size: 16px">
                                            <code class="balance"></code>
                                        </div>
                                    </th>
                                </tr>

                                <tr>
                                    <td style="background: aquamarine;padding: 10px;">
                                        <div class="radio">
                                            <label style="padding-left: 10px">
                                                <input name="" class="point" type="checkbox" id="test3">
                                                <label for="test3"><b>Use Point</b></label>
                                            </label>

                                            <span style="float: right;font-size: 15px;color: #666666; display:none;" class="point_show">
                                                <b>Customer Point: <span class="customer_point">{{ optional($order->customer)->point ?? 0 }}</span></b><br>
                                                <input type="hidden" name="customer_point" id="customer_point" value="">
                                                <input type="number" name="point" class="form-control use_point" id="use_point" value="" onchange="updateDiscount(this)">
                                            </span>


                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="radio" style="background: antiquewhite;padding: 10px;">
                                            <label style="padding-left: 10px">
                                                <input name="is_cod" class="" type="checkbox" id="test2">
                                                <label for="test2"><b>Cash on Delivery</b></label>
                                            </label>

                                        </div>
                                    </td>
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

                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <input type="hidden" name="source" value="Website">

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

        $('#account_type_id').on('change', function() {

            let account_type_id = $(this).find(':selected').val();

            $.ajax({
                type:'GET',
                url: "{{ route('dokani.get-account-balance-ajax') }}",
                data: {
                    _token: '{!! csrf_token() !!}',
                    account_type_id: account_type_id,
                },
                success:function(data) {
                    if (data.balance){
                        $('.balance').text(data.balance)
                    }
                    else {
                        $('.balance').text(0)
                    }
                }
            });

        });




        $('#customer').on('change', function() {

            var point = $(this).find(':selected').data('point');

            $('.customer_point').text(point);
            $('#customer_point').val(point);

        });

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
            let discount = '{{ $order->discount }}';

            let point_value = '{{ optional($point)->point_value }}'

            let total_discount = (point * point_value) + discount

            $('#discount').val(total_discount)
            calculate()

        }


    </script>

@endsection

