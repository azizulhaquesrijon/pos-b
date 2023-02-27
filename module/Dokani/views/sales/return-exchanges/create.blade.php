@extends('layouts.master')
@section('title', 'Sale Return & Exchange Create')
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

    .search-input:focus,
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

</style>
@endsection

@section('content')

@php
    $vatPercent = ($sale->total_vat * 100) / ($sale->payable_amount - $sale->total_vat);
@endphp

<div class="page-header" style="padding: 0px !important;">
    <h4 class="page-title"><i class="fa fa-exchange"></i> @yield('title')</h4>
</div>

<div class="row">
    <div class="col-md-12">

        @include('partials._alert_message')



        <!-- SEARCH -->
        <form id="saleProductExchangeSearchForm" class="form-horizontal" action="" method="GET">
            <div class="row">
                <div class="col-lg-3">
                    <div class="input-group mb-2 width-100">
                        <span class="input-group-addon width-30" style="text-align: left">
                            Branch
                        </span>
                        <select name="branch_id" id="branch_id" class="form-control select2" style="width: 100%">
                            @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ old('branch_id', request('branch_id')) == $branch->id ? 'selected' : '' }} data-short_name="{{ $branch->short_name }}">
                                {{ $branch->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="input-group mb-2 width-100">
                        <span class="input-group-addon width-30" style="text-align: left">
                            Customer
                        </span>
                        <select name="customer_id" id="customer_id" class="form-control select2" onchange="getInvoices(this)" style="width: 100%">
                            <option value="" selected>- Select -</option>
                            @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ old('customer_id', request('customer_id')) == $customer->id ? 'selected' : '' }}
                                data-customer-invoices="{{ $customer->sales }}" data-customer_discount="{{ $customer->discount }}">
                                {{ $customer->name }} &mdash; {{ $customer->mobile }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="input-group mb-2 width-100">
                        <span class="input-group-addon width-30" style="text-align: left">
                            Invoice No
                        </span>
                        <select name="invoice_no" id="invoice_no" class="form-control select2" required
                            style="width: 100%">
                            <option value="" selected>- Select -</option>
                            @foreach ($saleInvoices as $invoice)
                            <option value="{{ $invoice }}"
                                {{ old('invoice_no', request('invoice_no')) == $invoice ? 'selected' : '' }}>
                                {{ $invoice }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="btn-group" style="width: 100%">
                        <button type="submit" class="btn btn-sm btn-primary" style="width: 70%; height: 33px; padding-top: 6px"><i
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
        @if (request('invoice_no'))

            <form id="productReturnAndExchangeForm" action="{{ route('dokani.sale-return-exchanges.store') }}" method="POST">
                @csrf

                <div class="purchase-product-list" style="{{ request('invoice_no') == '' ? 'display:none' : ''  }};">
                    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                    <input type="hidden" name="customer_id" value="{{ $sale->customer_id }}">
                    <input type="hidden" name="customer_discount" class="customer_discount" value="{{ optional($sale->customer)->discount }}">
                    <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                    <input type="hidden" name="branch_id" value="{{ request('branch_id') }}">

                    @if(auth()->user()->type == 'owner')
                    <input type="hidden" name="inv_prefix" class="inv_prefix" value="S">
                    @else
                    <input type="hidden" name="inv_prefix" class="inv_prefix" value="{{ $branches->first()->short_name }}">
                    @endif

                    <h4 style="text-align: center;font-weight:600;">Sale Product List</h4>
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="table-header-bg">
                                <th width="3%" class="text-center">SN</th>
                                <th width="34%">Item Description</th>
                                <th width="10%">Category</th>
                                <th width="10%">Unit</th>
                                <th width="15%">Sale Qty</th>
                                <th width="15%">Returnable Qty</th>
                                <th width="10%" class="text-center">Return Qty</th>
                                <th width="10%">Price</th>
                                <th width="10%">Vat(%)</th>
                                <th width="10%">Total</th>
                                <th width="3%" class="text-center">
                                    <i class="fa fa-exchange">
                                    </i>
                                </th>
                            </tr>
                        </tbody>


                        <tbody>
                            @foreach ($sale->sale_details ?? [] as $saleDetail)

                                <tr>
                                    <input type="hidden" class="sale-detail-id" value="{{ $saleDetail->id }}">
                                    <input type="hidden" class="sale-detail-lot" value="{{ $saleDetail->lot }}">
                                    <input type="hidden" class="return-product-id" value="{{ optional($saleDetail->product)->id }}">
                                    <input type="hidden" class="return-product-purchase-price" value="{{ $saleDetail->buy_price }}">

                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="product-name">{{ optional($saleDetail->product)->name }}</td>
                                    <td class="product-category">{{ optional(optional($saleDetail->product)->category)->name }}</td>
                                    <td class="product-unit">{{ optional(optional($saleDetail->product)->unit)->name }}</td>
                                    <td>{{ $saleDetail->quantity }}</td>

                                    <td class="returnable-quantity">{{ number_format($saleDetail->quantity - ($saleDetail->sale_return_details_sum_quantity ?? 0), '2', '.', '') }}</td>
                                    <td class="text-center" >
                                        <input type="number" class="return-quantity" value="" name="quantity" id="qty{{ $saleDetail->id }}" style="width: 40px" onkeyup="checkAvailablity(this)">
                                    </td>
                                    <td class="text-right product-sale-price">{{ number_format($saleDetail->price, 2, '.', '') }}</td>
                                    <td class="text-right sale-detail-vat">{{ number_format($vatPercent, 2, '.', '') }}</td>
                                    <td class="text-right">
                                        {{ number_format(($saleDetail->price * $saleDetail->quantity) + ((($saleDetail->price * $saleDetail->quantity) * $vatPercent) / 100), 2, '.', '') }}</td>
                                    @if($loop->iteration == 1)
                                        <td class="text-center" rowspan="{{ $saleDetail->count() }}">
                                            <a href="javascript:void(0)" class="btn btn-minier btn-success return" type="button" onclick="createReturnList()"><i class="fa fa-exchange"></i></a>
                                        </td>
                                    @endif
                                </tr>


                            @endforeach
                        </tbody>
                    </table>
                </div>



                <div class="return-product-list">
                    <h4 style="text-align: center;font-weight:600;">Return Products</h4>
                    <div class="row mb-1" style="{{ request('invoice_no') != '' ? 'display:none' : ''  }};">
                        <div class="col-md-8 col-md-offset-2" style="position: relative;">
                            <input type="text" class="form-control return-product-search-input" style="border-radius: 20px !important;padding: 20px" id="search" placeholder="🔎︎ Product Name" autocomplete="off">
                            <div class="loader" style="right:0;display: none"></div>
                            <div class="return-product-search">
                            </div>
                        </div>
                        <div class="ajax-loader" style="visibility: hidden;">
                            <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 2%;position: relative;right: 54px;top: 7px;"/>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <input type="hidden" class="return-vat" name="" value="{{ $vatPercent }}">
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
                            <div class="product-search"  style="position: absolute;z-index: 999;background: white;">

                            </div>
                        </div>

                        <div class="ajax-loader" style="visibility: hidden;">
                            <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 2%;position: relative;right: 54px;top: 7px;"/>
                        </div>

                    </div>
                    <table class="table table-bordered" id="exchange-product">
                        <input type="hidden" class="exchange-vat" value="{{ $business_setting->vat }}">

                        <thead>
                            <tr class="" style="background-color: #308f28;color: white;">
                                <th width="3%" class="text-center">SN</th>
                                <th width="25%">Item Description</th>
                                <th width="10%">Code</th>
                                <th width="5%">Stock</th>
                                <th width="10%">Description</th>
                                <th width="10%">Expiry Date</th>
                                <th width="5%">Quantity</th>
                                <th width="5%">Unit</th>
                                <th width="10%">Discount</th>
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
                            <span class="input-group-addon" style="width:40%; text-align: left">
                                Vat
                            </span>
                            <input type="text" class="form-control text-right" name="total_return_vat_percent" id="total_return_vat_percent" value="{{ old('total_return_vat_percent') }}" readonly placeholder="%" autocomplete="off" style="width: 50px">
                            <span class="input-group-addon" style="text-align: left">
                                <i class="fa fa-exchange"></i>
                            </span>
                            <input type="text" class="form-control text-right" name="total_return_vat_amount" id="total_return_vat_amount" value="{{ old('total_return_vat_amount') }}" readonly placeholder="Amount" autocomplete="off">
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
                            <input type="text" class="form-control text-right" name="total_exchange_discount_percent" id="total_exchange_discount_percent" value="{{ old('total_exchange_discount_percent') }}" onkeyup="calculateExchangeDiscountAmount(this)" placeholder="%" autocomplete="off" style="width: 50px" readonly>
                            <span class="input-group-addon width-10" style="width:10%; text-align: left">
                                <i class="fa fa-exchange"></i>
                            </span>
                            <input type="text" class="form-control text-right" name="total_exchange_discount_amount" id="total_exchange_discount_amount" value="{{ old('total_exchange_discount_amount') }}" onkeyup="calculateExchangeDiscountPercent(this)" placeholder="Amount" autocomplete="off" readonly>
                        </div>


                        <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="width:40%; text-align: left">
                                Vat
                            </span>
                            <input type="text" class="form-control text-right" name="total_exchange_vat_percent" id="total_exchange_vat_percent" value="{{ old('total_exchange_vat_percent') }}" readonly placeholder="%" autocomplete="off" style="width: 50px">
                            <span class="input-group-addon" style="text-align: left">
                                <i class="fa fa-exchange"></i>
                            </span>
                            <input type="text" class="form-control text-right" name="total_exchange_vat_amount" id="total_exchange_vat_amount" value="{{ old('total_exchange_vat_amount') }}" readonly placeholder="Amount" autocomplete="off">
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
                            {{-- <h4 class="ShopPaidToCust">Shop Paid to Customer</h4> --}}
                            {{-- <h4 class="CustPaidToShop">Customer Paid to Shop</h4> --}}
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
                                    {{-- <input type="number" class="form-control text-right" name="customer_paid_amount" id="customer_paid_amount" value="{{ old('customer_paid_amount',0) }}" value="0.00" required placeholder="Amount">
                                    <input type="number" class="form-control text-right" name="shop_paid_amount" id="shop_paid_amount" value="{{ old('shop_paid_amount',0) }}" value="0.00" required placeholder="Amount"> --}}
                                </div>
                                {{-- <div class="col-md-1 text-center mt-1" >
                                    <a href="javascript:void(0)" class="addeventmore"><i class="fa fa-plus-circle"></i></a>
                                </div> --}}
                            </div>
                        </div>
                        <div>
                            <span class="supplierNeedToPay" style="color: red;display: block;">
                                If Shop Pay to Customer. Amount Must be (-) Negetive ( Ex: -550)
                                <br>
                                If Customer Pay to Shop. Amount Must be (+) Positive ( Ex: 550)
                            </span>
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
                    {{-- <div class="input-group mb-1 width-100">
                        <span class="input-group-addon" style="width: 40%; text-align: left">
                            Rounding
                        </span>
                        <input type="text" class="form-control text-right" name="rounding" id="rounding" value="{{ old('rounding') }}" readonly>
                    </div> --}}
                    <div class="input-group width-100">
                        <span class="input-group-addon" style="width: 40%; text-align: left">
                            Payable
                        </span>
                        <input type="text" class="form-control text-right" name="payable_amount" id="payable_amount" value="{{ old('payable_amount') }}" readonly required>
                    </div>
                    <small class="supplierNeedToPay" style="color: red;margin-left: 60px;display: block;">(-) Mean Shop Need to Pay to Customer</small>
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

        @endif
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

    @include('sales.return-exchanges._inc.script')
    @include('sales.return-exchanges._inc.new-script')

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
            let product_stock = _this.closest('.single-product-li').find('.product-stock').text();
            let product_price = Number(_this.closest('.single-product-li').find('.product-price').text()).toFixed(2);
            let product_cost = Number(_this.closest('.single-product-li').find('.buy-price').text()).toFixed(2);
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
            addPurchaseItem(length, product_id, product_title, product_code, product_stock, 1, product_price,unit,description, table, product_cost)

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

        $(document).ready(function(){
            let short_name = $('#branch_id option:selected').data('short_name');
            $('.inv_prefix').val(short_name);
        })

        $('#branch_id').on('change',function(){
            let short_name = $('#branch_id option:selected').data('short_name');
            $('.inv_prefix').val(short_name);
        })

        $('.payable_amount').on('change',function(){
            console.log($('.payable_amount').val());
        })
    </script>
@endsection
