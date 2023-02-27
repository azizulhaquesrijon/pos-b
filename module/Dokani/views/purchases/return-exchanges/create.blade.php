@extends('layouts.master')
@section('title', 'Purchase Return & Exchange Create')
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
@section('css')
<style>

    .search-input,
    .select-option {
        position: relative !important;
        width: 160px !important;
        border: 1.9px solid !important;
        height: 25px !important;
        padding: 2px 8px !important;
        font-size: 14px !important
    }

    /* .search-input:focus, */
    .select-option:focus {
        border: 1.9px solid black !important;
    }


    .search-icon {
        position: absolute !important;
        margin-left: -20px !important;
        margin-top: 5.5px !important;
    }


    .select-type,
    .qty-input {
        width: 100px !important;
        /* border: 1.9px solid !important; */
        height: 22px !important;
        padding: 0px 8px !important;
        font-size: 14px !important
    }
    .invoice-sention{
        position: relative;
    }
    .or{
        position: absolute;
        top: -10px;
        background: #c1c1c1;
        right: -13px;
        padding: 8px 2px 9px 2px;
        font-size: 15px;
        color: black;
    }

</style>
@endsection

@section('content')
<div class="page-header" style="padding: 0px !important;">
    <h4 class="page-title"><i class="fa fa-exchange"></i> @yield('title')</h4>
</div>

<div class="row">
    <div class="col-md-12">

        @include('partials._alert_message')



        <!-- SEARCH -->
        <form id="saleProductExchangeSearchForm" class="form-horizontal" action="" method="GET">
            <div class="row">
                <div class="col-lg-5">
                    <div class="input-group mb-2 width-100">
                        <span class="input-group-addon width-30" style="text-align: left">
                            Supplier <sup style="color: red">*</sup>
                        </span>
                        <select name="supplier_id" class="form-control select2 supplier_id" onchange="getInvoices(this)"
                            style="width: 100%">
                            {{-- <option value="" selected>- Select -</option> --}}
                            @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ old('supplier_id', request('supplier_id')) == $supplier->id ? 'selected' : '' }}
                                data-customer-invoices="{{ $supplier->purchases }}" data-customer-references="{{ $supplier->purchases }}">
                                {{ $supplier->name }} &mdash; {{ $supplier->mobile }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 invoice-sention">
                    <div class="input-group mb-2 width-100">
                        <span class="input-group-addon width-30" style="text-align: left">
                            Invoice No
                        </span>
                        <select name="invoice_no" id="invoice_no" class="form-control select2"
                            style="width: 100%">
                            <option value="" selected>- Select -</option>
                            @foreach ($purchaseInvoices as $invoice)
                            <option value="{{ $invoice }}"
                                {{ old('invoice_no', request('invoice_no')) == $invoice ? 'selected' : '' }}>
                                {{ $invoice }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="btn-group" style="width: 100%">
                        <button class="btn btn-sm btn-primary" type="submit" style="width: 70%; height: 33px; padding-top: 6px"><i
                                class="fa fa-search"></i> SEARCH</button>
                        <a href="{{ request()->url() }}" class="btn btn-sm btn-pink"
                            style="width: 28%; height: 33px; padding-top: 6px">
                            <i class="fa fa-refresh"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>




        <!-- TABLE -->
        {{-- @if (request('supplier_id')) --}}

            <form id="productReturnAndExchangeForm" action="{{ route('dokani.purchase-return-exchanges.store') }}" method="POST">
                @csrf

                <div class="purchase-product-list" style="{{ $purchase ? 'display:block' : 'display:none' }};">
                    <input type="hidden" name="purchase_id" value="{{ optional($purchase)->id }}">
                    <input type="hidden" name="supplier_id" value="{{ optional($purchase)->supplier_id }}">
                    <h4 style="text-align: center;font-weight:600;">Purchase Product List</h4>
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="table-header-bg">
                                <th width="3%" class="text-center">SN</th>
                                <th width="34%">Item Description</th>
                                <th width="20%">Category</th>
                                <th width="10%">Unit</th>
                                <th width="10%">Purchase Qty</th>
                                <th width="10%">Returnable Qty</th>
                                <th width="10%" class="text-center">Return Qty</th>
                                <th width="12%">Price</th>
                                <th width="12%">Total</th>
                                <th width="3%" class="text-center">
                                    <i class="fa fa-exchange">
                                    </i>
                                </th>
                            </tr>
                        </tbody>

                        <tbody id="purchase_details_body">
                            @if ($purchase)
                            @foreach ($purchase->details ?? [] as $purchaseDetail)
                                <tr>
                                    <input type="hidden" class="purchase-detail-id" value="{{ $purchaseDetail->id }}">
                                    <input type="hidden" class="purchase-detail-lot" value="{{ $purchaseDetail->lot }}">
                                    <input type="hidden" class="return-product-id" value="{{ optional($purchaseDetail->product)->id }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="product-name">{{ optional($purchaseDetail->product)->name }}</td>
                                    <td class="product-category">{{ optional(optional($purchaseDetail->product)->category)->name }}</td>
                                    <td class="product-unit">{{ optional(optional($purchaseDetail->product)->unit)->name }}</td>
                                    <td>{{ $purchaseDetail->quantity }}</td>

                                    <td class="returnable-quantity">{{ number_format(optional($purchaseDetail->stock)->available_quantity, 2, '.' ,'') }}</td>
                                    <td class="text-center" >
                                        <input type="number" class="return-quantity" value="" name="quantity" id="qty{{ $purchaseDetail->id }}" style="width: 40px" onkeyup="checkAvailablity(this)">
                                    </td>
                                    <td class="text-right product-purchase-price">{{ number_format($purchaseDetail->price, 2, '.', '') }}</td>
                                    <td class="text-right">
                                        {{ number_format($purchaseDetail->price * $purchaseDetail->quantity, 2, '.', '') }}</td>
                                    {{-- @if($loop->iteration == 1)
                                        <td class="text-center" rowspan="{{ $purchase->details->count() }}">
                                            <a href="javascript:void(0)" class="btn btn-minier btn-success return" type="button" onclick="createReturnList()"><i
                                                    class="fa fa-exchange"></i></a>
                                        </td>
                                    @endif --}}
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-minier btn-success return" type="button" onclick="createReturnList()"><i
                                                    class="fa fa-exchange"></i></a>
                                        </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>



                <div class="return-product-list">
                    <h4 style="text-align: center;font-weight:600;">Return Products</h4>
                    <div class="row mb-1 return-invoice-search" style="{{ (request('invoice_no') != '' || request('reference_no') != '') ? 'display:none' : ''  }};">
                        <div class="col-md-8 col-md-offset-2" style="position: relative;">
                            <input type="text" class="form-control return-product-search-input"
                                    style="border-radius: 20px !important;padding: 20px" id="search"
                                    placeholder="🔎︎ Product Name or Reference No" autocomplete="off" autofocus>

                            <div class="loader" style="right:0;display: none"></div>
                            <div class="return-product-search" style="position: relative">
                            </div>
                        </div>
                        <div class="return-ivoice-ajax-loader" style="visibility: hidden;">
                            <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 2%;position: relative;right: 54px;top: 7px;"/>
                        </div>
                    </div>
                    <table class="table table-bordered" ">
                        <tbody>
                            <tr class="" style="background-color: #b93810;color: white;">
                                <th width="3%" class="text-center">SN</th>
                                <th width="34%">Item Description</th>
                                <th width="10%">Category</th>
                                <th width="10%">Unit</th>
                                <th width="10%" class="text-center">Return Qty</th>
                                <th width="10%">Price</th>
                                <th width="10%">Total</th>
                                <th width="10%">Type</th>
                                <th width="3%" class="text-center">
                                    <i class="fa fa-exchange">
                                    </i>
                                </th>
                            </tr>
                        </tbody>

                        <tbody id="return-tboady">

                        </tbody>
                    </table>
                </div>


                <div class="return-product-list">
                    <h4 style="text-align: center;font-weight:600;">Exchange Products</h4>
                    <div class="row mb-1">

                        <div class="col-md-8 col-md-offset-2" style="position: relative;">

                            <input type="text" class="form-control product-search-input"
                                   style="border-radius: 20px !important;padding: 20px"
                                   id="search" placeholder="🔎︎ Product Name" autocomplete="off">

                            <div class="loader" style="right:0;display: none"></div>
                            <div class="product-search">

                            </div>
                        </div>

                        <div class="ajax-loader" style="visibility: hidden;">
                            <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 2%;position: relative;right: 54px;top: 7px;"/>
                        </div>

                    </div>
                    <table class="table table-bordered" id="exchange-product">
                        <thead>
                            <tr class="" style="background-color: #308f28;color: white;">
                                <th width="3%" class="text-center">SN</th>
                                <th width="34%">Item Description</th>
                                <th width="10%">Code</th>
                                <th width="10%">Description</th>
                                <th width="10%">Expiry Date</th>


                                <th width="10%">Quantity</th>
                                <th width="10%">Unit</th>
                                <th width="10%">Price</th>
                                <th width="10%">Total</th>
                                <th width="3%" class="text-center">
                                    <i class="fa fa-exchange">
                                    </i>
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>






                <div class="col-md-9">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="background-color: rgb(255 247 247); border: 2px solid #efefef">
                                RETURN
                            </span>
                        </div>
                        <input type="hidden" class="form-control" name="total_return_cost" id="total_return_cost" value="{{ old('total_return_cost') }}" readonly>
                        <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="width: 40%; text-align: left">
                                Subtotal
                            </span>
                            <input type="text" class="form-control text-right" name="total_return_subtotal" id="total_return_subtotal" value="{{ old('total_return_subtotal') }}" readonly required>
                        </div>
                        <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="width:40%; text-align: left">
                                Discount
                            </span>
                            <input type="text" class="form-control text-right" name="total_return_discount_percent" id="total_return_discount_percent" value="{{ old('total_return_discount_percent') }}" readonly placeholder="%" autocomplete="off" style="width: 50px">
                            <span class="input-group-addon" style="text-align: left">
                                <i class="fa fa-exchange"></i>
                            </span>
                            <input type="text" class="form-control text-right" name="total_return_discount_amount" id="total_return_discount_amount" value="{{ old('total_return_discount_amount') }}" readonly placeholder="Amount" autocomplete="off">
                        </div>
                        <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="width: 40%; text-align: left">
                                Grand Total
                            </span>
                            <input type="text" class="form-control text-right" name="return_total_amount" id="return_total_amount" value="{{ old('return_total_amount') }}" readonly autocomplete="off">
                        </div>
                    </div>






                    <div class="col-md-4">
                        <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="background-color: rgb(245 255 245); border: 2px solid #efefef">
                                EXCHANGE
                            </span>
                        </div>
                        <input type="hidden" id="total_exchange_quantity" name="total_exchange_quantity" value="">
                        <input type="hidden" class="form-control" name="total_exchange_cost" id="total_exchange_cost" value="{{ old('total_exchange_cost') }}" readonly>
                        <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="width: 40%; text-align: left">
                                Subtotal
                            </span>
                            <input type="text" class="form-control text-right" name="total_exchange_subtotal" id="total_exchange_subtotal" value="{{ old('total_exchange_subtotal') }}" readonly required>
                        </div>
                        <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="width:40%; text-align: left">
                                Discount
                            </span>
                            <input type="text" class="form-control text-right" name="total_exchange_discount_percent" id="total_exchange_discount_percent" value="{{ old('total_exchange_discount_percent') }}" onkeyup="calculateExchangeDiscountAmount(this)" placeholder="%" autocomplete="off" style="width: 50px">
                            <span class="input-group-addon width-10" style="width:10%; text-align: left">
                                <i class="fa fa-exchange"></i>
                            </span>
                            <input type="text" class="form-control text-right" name="total_exchange_discount_amount" id="total_exchange_discount_amount" value="{{ old('total_exchange_discount_amount') }}" onkeyup="calculateExchangeDiscountPercent(this)" placeholder="Amount" autocomplete="off">
                        </div>
                        <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="width: 40%; text-align: left">
                                Grand Total
                            </span>
                            <input type="text" class="form-control text-right" name="total_exchange_amount" id="total_exchange_amount" value="{{ old('total_exchange_amount') }}" readonly required>
                        </div>
                    </div>
                    <div class="col-md-4"></div>

                    <div class="col-md-8">
                        <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="background-color: rgb(255 247 247); border: 2px solid #efefef">
                                ACCOUNT <sup style="color: red">*</sup>
                            </span>
                        </div>
                        <div class="add_item">
                            <div class="row" style="display: flex; margin-bottom: 4px">
                                <div class="col-md-6">
                                    <select name="account_ids" class="form-control select2" id="" required>
                                        @foreach ($accounts as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->balance }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control text-right" name="paid_amount" id="paid_amount" value="{{ old('paid_amount',0) }}" value="0.00" required placeholder="Amount">
                                </div>
                                {{-- <div class="col-md-1 text-center mt-1" >
                                    <a href="javascript:void(0)" class="addeventmore"><i class="fa fa-plus-circle"></i></a>
                                </div> --}}
                            </div>
                        </div>
                        <div>
                            <span class="supplierNeedToPay" style="color: red;display: block;">If Supplier Pay to Shop. Amount Must be (-) Negetive</span>
                        </div>

                        {{-- <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="width: 40%; text-align: left">
                                Grand Total
                            </span>
                            <input type="text" class="form-control text-right" name="grand_total_amount" id="grand_total_amount" value="{{ old('grand_total_amount',0) }}" readonly autocomplete="off">
                        </div> --}}
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="input-group mb-1 width-100">
                        <span class="input-group-addon" style="background-color: rgb(215 223 255); border: 2px solid #efefef">
                            PAYMENT
                        </span>
                    </div>
                    <div class="input-group mb-1 width-100">
                        <span class="input-group-addon" style="width: 40%; text-align: left">
                            Rounding
                        </span>
                        <input type="text" class="form-control text-right" name="rounding" id="rounding" value="{{ old('rounding') }}" readonly>
                    </div>
                    <div class="input-group width-100">
                        <span class="input-group-addon" style="width: 40%; text-align: left">
                            Payable
                        </span>
                        <input type="text" class="form-control text-right" name="payable_amount" id="payable_amount" value="{{ old('payable_amount') }}" readonly required>
                    </div>
                    <small class="supplierNeedToPay" style="color: red;margin-left: 100px;display: block;">(-) Mean Supplier Need to Pay</small>
                    {{-- <div class="input-group mb-1 width-100">
                        <span class="input-group-addon" style="width: 40%; text-align: left">
                            Paid Amount
                        </span>
                        <input type="text" class="form-control text-right" name="paid_amount" id="paid_amount" value="{{ old('paid_amount') }}">
                    </div> --}}
                    <div class="input-group mt-1 mb-1 width-100">
                        <span class="input-group-addon" style="width: 40%; text-align: left">
                            Due Amount
                        </span>
                        <input type="text" class="form-control text-right" name="due_amount" id="due_amount" value="{{ old('due_amount') }}" readonly>
                    </div>
                    <div class="input-group mb-1 width-100">
                        <span class="input-group-addon" style="width: 40%; text-align: left">
                            Change
                        </span>
                        <input type="text" class="form-control text-right" name="change_amount" id="change_amount" value="{{ old('change_amount') }}" readonly>
                    </div>

                    <div class="form-control radio mb-1 bg-dark">
                        <label>
                            <input name="print_type" value="pos-print" type="radio" class="ace" @if(empty(old('radio')) || old('radio') == 'pos-print') checked @else @endif>
                            <span class="lbl"> POS Print</span>
                        </label>

                        <label>
                            <input name="print_type" value="normal-print" type="radio" class="ace" {{ old('radio') == 'normal-print' ? 'checked' : '' }}>
                            <span class="lbl"> Normal Print</span>
                        </label>
                    </div>


                    <div class="btn-group width-100">
                        <a href="#add-new" class="btn btn-sm btn-success" id="submitBtn" style="width: 70%" role="button" data-toggle="modal">
                            <i class="fa fa-save"></i>
                            SUBMIT
                        </a>
                        {{-- <a class="btn btn-sm btn-info" style="width: 29%" href="{{ route('dokani.sale-return-exchanges.index') }}"> <i class="fa fa-bars"></i> LIST </a> --}}
                    </div>
                </div>
            </form>

        {{-- @endif --}}
    </div>
</div>



<div class="whole_extra_item_add" id="whole_extra_item_add" style="display: none">
    <div class="row delete_whole_extra_item_add" style="display: flex;margin-bottom: 4px">
        <div class="col-md-5">
            <select name="account_ids[]" class="form-control" id="" >
                @foreach ($accounts as $item)
                    <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->balance }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <input type="number" class="form-control text-right" name="paid_amount[]" id="paid_amount" value="{{ old('paid_amount',0) }}" value="0.00" required placeholder="Amount">
        </div>
        <div class="col-md-1 text-center mt-1">
            <a href="javascript:void(0)" class="removeeventmore" style="color: red"><i class="fa fa-times"></i></a>
        </div>
    </div>
</div>
@endsection





@section('js')
    <script src="{{ asset('assets/js/jquery.ba-throttle-debounce.js') }}"></script>

    @include('purchases.return-exchanges._inc.script')
    @include('purchases.return-exchanges._inc.new-script')

    <script>
        function checkAvailablity(obj) {

            let returnableStock = Number($(obj).closest('tr').find('.returnable-quantity').text());
            if(returnableStock < $(obj).val()){
                warning('toster', "Not Valid quantity");
                $(obj).val('');
            }

        }


        function GetInfo(object, searchField) {
            let _this = $(object);
            let product_id = _this.closest('.single-product-li').find('.product_id').text();
            let product_title = _this.closest('.single-product-li').find('.product-title').text();
            let product_code = _this.closest('.single-product-li').find('.sku-code').text();
            let product_price = Number(_this.closest('.single-product-li').find('.product-price').text()).toFixed(2);
            let unit = _this.closest('.single-product-li').find('.product-unit').text();
            let description = _this.closest('.single-product-li').find('.description').text();
            let table = $('#exchange-product tbody');
            let length = $('#exchange-product tbody tr').length + 1;

            if (description == 'null'){
                description = ''
            }

            // if(searchField == 'product-search'){
            //     alert("ok");
            // }else if(searchField == 'return-product-search'){
            //     addReturnItem(length, product_id, product_title, product_code, 1, product_price,unit,description, table)
            // }
            addPurchaseItem(length, product_id, product_title, product_code, 1, product_price,unit,description, table)

        }



        $(document).ready(function(){
            var counter = 0;
        	$(document).on("click",".addeventmore", function(){
            	var whole_extra_item_add = $("#whole_extra_item_add").html();
            	$(this).closest(".add_item").append(whole_extra_item_add);
            	counter++;
        	});

        	$(document).on("click",".removeeventmore", function(event){
                $(this).closest(".delete_whole_extra_item_add").remove();
                counter -= 1;
        	});

        })


        $('.payable_amount').on('change',function(){
            console.log($('.payable_amount').val());
        })


        function getInvoiceInfo(obj, event){

            let reference_no = $(obj).val();
            let supplier_id = $('.supplier_id option:selected').val();
            let url = `{{ route('dokani.get-searchable-purchase-invoice-ajax') }}`

            $.ajax({
                type:'GET',
                url: url,
                data: {
                    reference_no: reference_no,
                    supplier_id:supplier_id,
                },
                beforeSend: function(){
                    $('.return-ivoice-ajax-loader').css("visibility", "visible");
                },
                success:function(data) {
                    // console.log(data);
                    selectedLiIndex = -1
                    Invoices(data, event)

                    if (data.length == 1) {
                        onSelectPurchaseInvoice(data[0].details)
                    }
                },
                complete: function(){
                    $('.return-ivoice-ajax-loader').css("visibility", "hidden");
                }
            });
        arrowUpDownInit(event)
        }

        $(document).ready(function() {
			$(window).keydown(function(event){
				if(event.keyCode == 13) {
				event.preventDefault();
				return false;
				}
			});
		});

        $(document).on('keyup', ".return-product-search-input", $.debounce(500, function(e) {
            getInvoiceInfo(this, e)
        }));


        function Invoices(data, event) {

            let li = `<table id="dynamic-table" class="table table-bordered table-hover search-product" style="z-index:999999999999;position: absolute;background: white;">
                        <thead>
                            <tr class="table-header-bg" style="position: sticky; top: 0px;width: 100%">
                                <th class="pl-3" style="color: white !important; width: 20%" >Invoice</th>
                                <th class="pl-3" style="color: white !important; width: 20%" >Reference No</th>
                                <th class="pl-3" style="color: white !important; width: 20%" >Date</th>
                                <th class="pl-3" style="color: white !important; width: 30%" >Total Qty</th>
                                <th class="pl-3" style="color: white !important; width: 20%" >Paid Amount</th>
                            </tr>
                        </thead>
                        <tbody>`
            if (data.length > 0){
                data.map(function(value) {
                    let details = JSON.stringify(value.details);
                    li +=   `<tr class="single-product-li dropdown-item invoice-data" onclick='onSelectPurchaseInvoice(${details})'>
                                <a href="javascript:void(0)">
                                    <td class="product-title" width="20%"><strong>${value.invoice_no}</strong></td>
                                    <td width="20%"><span class="product-stock">${value.reference_no}</span></td>
                                    <td width="20%"><span class="sku-code">${value.date}</span></td>
                                    <td width="30%"><span class="sku-code">${value.details_sum_quantity ?? 0}</span></td>
                                    <td width="20%"><span class="sku-code">${value.paid_amount}</span></td>
                                </a>
                            </tr>`
                })
            }else {
                li +=   `<tr>
                            <td colspan="30" class="text-center text-danger py-3"
                            style="background: #eaf4fa80 !important; font-size: 18px">
                            <strong>No product found!</strong>
                            </td>
                        </tr>`
            }
            li += '</tbody></table>'
            $('.return-product-search').html(li)
            var mouse_is_inside = false;
            if(event.keyCode == 40){

            }

        }


        function onSelectPurchaseInvoice(obj){
            let li = '';
            let supplier_id = $('.supplier_id option:selected').val();
            $.map(obj, function(detail, i){
                li +=   `<tr>
                            <input type="hidden" name="purchase_id" value="${ detail.purchase_id }">
                            <input type="hidden" name="supplier_id" value="${ supplier_id }">
                            <input type="hidden" class="purchase-detail-id" value="${detail.id}">
                            <input type="hidden" class="purchase-detail-lot" value="${detail.lot}">
                            <input type="hidden" class="return-product-id" value="${detail.product_id}">
                            <td class="text-center">${i+1}</td>
                            <td class="product-name">${ detail.product?.name }</td>
                            <td class="product-category">${ detail.product?.category?.name }</td>
                            <td class="product-unit">${ detail.product?.unit?.name }</td>
                            <td>${ detail.quantity }</td>
                            <td class="returnable-quantity">${ detail.stock?.available_quantity }</td>
                            <td class="text-center" >
                                <input type="number" class="return-quantity" value="" name="quantity" id="qty${ detail.id }" style="width: 40px" onkeyup="checkAvailablity(this)">
                            </td>
                            <td class="text-right product-purchase-price">${ Number(detail.price, 2) }</td>
                            <td class="text-right">
                                ${ Number(detail.price * detail.quantity, 2) }
                            </td>
                            <td class="text-center">
                                <a href="javascript:void(0)" class="btn btn-minier btn-success return" type="button" onclick="createReturnList()">
                                    <i class="fa fa-exchange"></i>
                                </a>
                            </td>
                        </tr>`

            })

            $('.return-product-search').html('')

            $('.purchase-product-list').show()
            $('.return-invoice-search').hide()

            $('#purchase_details_body').html(li)
        }


        function arrowUpDownInit(e) {
            e.preventDefault()

            $('.search-product').find('tr .invoice-data').removeClass('background')
            var li = $('.search-product').find('tr')
            var selectedItem

            if (e.which === 40) {
                selectedLiIndex += 1
            } else if (e.which === 38) {
                selectedLiIndex -= 1
            }

            if (selectedLiIndex < 0) {
                selectedLiIndex = 0
            }

            if (li.length <= selectedLiIndex) {
                selectedLiIndex = 0
            }
            if (e.which == 40 || e.which == 38) {
                selectedItem = $('.search-product').find(`li:eq(${selectedLiIndex})`).addClass('background')
                select(selectedItem)
            }
        }


    </script>
@endsection
