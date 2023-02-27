@extends('layouts.master')
@section('title', 'Return & Exchange Create')

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
                            Customer
                        </span>
                        <select name="customer_id" class="form-control select2" onchange="getInvoices(this)"
                            style="width: 100%">
                            <option value="" selected>- Select -</option>
                            @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ old('customer_id', request('customer_id')) == $customer->id ? 'selected' : '' }}
                                data-customer-invoices="{{ $customer->sales }}">
                                {{ $customer->name }} &mdash; {{ $customer->mobile }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-5">
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
                        <button class="btn btn-sm btn-primary" style="width: 70%; height: 33px; padding-top: 6px"><i
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

                <input type="hidden" name="sale_id" value="{{ optional($sale)->id }}">
                <input type="hidden" name="customer_id" value="{{ optional($sale)->customer_id }}">


                <table class="table table-bordered">
                    <tbody>
                        <tr class="table-header-bg">
                            <th width="3%" class="text-center">SN</th>
                            <th width="44%">Item Description</th>
                            <th width="10%">Category</th>
                            <th width="10%">Unit</th>
                            <th width="10%" class="text-center">Qty</th>
                            <th width="10%" class="text-center">Return Qty</th>
                            <th width="10%">Price</th>
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
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ optional($saleDetail->product)->name }}</td>
                                <td>{{ optional(optional($saleDetail->product)->category)->name }}</td>
                                <td>{{ optional(optional($saleDetail->product)->unit)->name }}</td>
                                <td>{{ $saleDetail->quantity }}</td>
                                <td class="text-center" >
                                    <input type="number" value="" name="quantity" id="qty{{ $saleDetail->id }}" style="width: 40px">
                                </td>
                                <td class="text-right">{{ number_format($saleDetail->price, 2, '.', '') }}</td>
                                <td class="text-right">
                                    {{ number_format($saleDetail->price * $saleDetail->quantity, 2, '.', '') }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" class="btn btn-minier btn-success exchange" type="button"><i
                                            class="fa fa-exchange"></i></a>
                                </td>
                            </tr>
                            <tr class="exchange-tr hide" >
                                <td colspan="11" style="display: none;">
                                    <div style="display: flex; justify-content: center">
                                        <div class="mr-2">
                                    <a href="javascript:void(0)" class="btn btn-minier btn-success exchange" type="button" onclick="getReturnProduct(this, `{{ $saleDetail->id }}`)">Show</a>

{{--                                            <select name="" class="select-option" style="border-radius: 10px !important" onchange="getReturnProduct(this, `{{ $saleDetail->id }}`)">--}}
{{--                                                <option value="">Select Barcode</option>--}}
{{--                                                @if ($saleDetail->barcodes != '[]')--}}
{{--                                                    @foreach ($saleDetail->barcodes as $item)--}}
{{--                                                        <option value="{{ $item->barcode_id }}">{{ optional($item->barcode)->barcode }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                @else--}}
{{--                                                    <option value="{{ optional(optional($saleDetail->product)->productBarcode)->id }}">{{ optional(optional($saleDetail->product)->productBarcode)->barcode }}</option>--}}
{{--                                                @endif--}}
{{--                                            </select>--}}
                                        </div>
                                        <div>
                                            <input type="text" class="search-input" data-is-barcode="{{ optional($saleDetail->product)->barcode }}" onkeydown="getExchangeProduct(this, event, `{{ $saleDetail->product_id }}`)" placeholder="Exchange Barcode" style="border-radius: 10px !important">
                                            <i class="fa fa-search search-icon"></i>
                                        </div>
                                    </div>


                                    <table class="table table-bordered mt-1" style="border: 1.9px solid gray">
                                        <tbody>
                                            <tr>
                                                <th width="3%" class="text-center">SN</th>
                                                <th width="32%">Item Description</th>
                                                <th width="12%">Category</th>
                                                <th width="8%">Unit</th>
                                                <th width="10%">Barcode</th>
                                                <th width="8%">Type</th>
                                                <th width="8%">Price</th>
                                                <th width="8%" class="text-center">Qty</th>
                                                <th width="10%">Total</th>
                                                <th width="3%" class="text-center"><i class="fa fa-times"></i></th>
                                            </tr>
                                        </tbody>




                                        <tr class="text-center" style="background-color: rgb(255 247 247)">
                                            <td colspan="10" style="line-height: 1px; color: #a0a0a0">Return</td>
                                        </tr>
                                        <tbody class="return-product">

                                        </tbody>




                                        <tr class="text-center" style="background-color: rgb(245 255 245)">
                                            <td colspan="10" style="line-height: 1px; color: #a0a0a0">Exchange</td>
                                        </tr>
                                        <tbody class="exchange-product">

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


{{--                <div class="col-md-9" style="display: block">--}}
{{--                    <table class="table table-bordered table-striped" id="payment-table">--}}
{{--                        <tbody>--}}
{{--                            <tr class="first-tr">--}}
{{--                                <td style="width: 4%"><span class="acc-sn-no">1</span></td>--}}
{{--                                <td style="width: 50%">--}}
{{--                                    <div class="input-group">--}}
{{--                                        <div class="input-group-addon">--}}
{{--                                            <label class="input-group-text">--}}
{{--                                                Account--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                        <select name="pos_account_id[]" class="form-control select2 pos-accounts" onchange="checkAccountExistOrNot(this)" style="width: 100%">--}}
{{--                                            <option value="" selected>- Select -</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </td>--}}

{{--                                <td style="width: 40%">--}}
{{--                                    <div class="input-group">--}}
{{--                                        <div class="input-group-addon">--}}
{{--                                            <label class="input-group-text">--}}
{{--                                                Amount--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                        <input type="text" name="payment_amount[]" class="form-control text-right payment-amount" onkeyup="calculatePaymentAmount()" placeholder="Type amount" autocomplete="off">--}}
{{--                                    </div>--}}
{{--                                </td>--}}

{{--                                <td class="text-center" style="width: 6%">--}}
{{--                                    <div class="btn-group">--}}
{{--                                        <a href="javascript:void(0)" class="btn btn-sm btn-info" onclick="addAccount()" type="button">--}}
{{--                                            <i class="fa fa-plus"></i>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        </tbody>--}}

{{--                        <tfoot>--}}
{{--                            <tr>--}}
{{--                                <th colspan="3" class="text-right">--}}
{{--                                    <span class="label label-inverse">--}}
{{--                                        <strong id="total_payment_amount">0</strong>--}}
{{--                                    </span>--}}
{{--                                </th>--}}
{{--                                <th><a href="javascript:void(0)" class="btn-xs btn-warning" style="color: #000000; text-decoration: none" id="hidePaymentDiv">Hide</a></th>--}}
{{--                            </tr>--}}
{{--                        </tfoot>--}}
{{--                    </table>--}}
{{--                </div>--}}


                <div>
                    <div class="col-md-3"></div>





                    <div class="col-md-3">
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






                    <div class="col-md-3">
                        <div class="input-group mb-1 width-100">
                            <span class="input-group-addon" style="background-color: rgb(245 255 245); border: 2px solid #efefef">
                                EXCHANGE
                            </span>
                        </div>
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
                    <div class="input-group mb-1 width-100">
                        <span class="input-group-addon" style="width: 40%; text-align: left">
                            Payable
                        </span>
                        <input type="text" class="form-control text-right" name="payable_amount" id="payable_amount" value="{{ old('payable_amount') }}" readonly required>
                    </div>
                    <div class="input-group mb-1 width-100">
                        <span class="input-group-addon" style="width: 40%; text-align: left">
                            Paid Amount
                        </span>
                        <input type="text" class="form-control text-right" name="paid_amount" id="paid_amount" value="{{ old('paid_amount') }}">
                    </div>
                    <div class="input-group mb-1 width-100">
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
                            <input name="radio" value="pos-print" type="radio" class="ace" @if(empty(old('radio')) || old('radio') == 'pos-print') checked @else @endif>
                            <span class="lbl"> POS Print</span>
                        </label>

                        <label>
                            <input name="radio" value="normal-print" type="radio" class="ace" {{ old('radio') == 'normal-print' ? 'checked' : '' }}>
                            <span class="lbl"> Normal Print</span>
                        </label>
                    </div>


                    <div class="btn-group width-100">
                        <a href="javascript:void(0)" class="btn btn-sm btn-success" id="submitBtn" style="width: 70%" type="button"> <i class="fa fa-save"></i> SUBMIT </a>
                        <a class="btn btn-sm btn-info" style="width: 29%" href="{{ route('dokani.sale-return-exchanges.index') }}"> <i class="fa fa-bars"></i> LIST </a>
                    </div>
                </div>
            </form>

        @endif
    </div>
</div>
@endsection





@section('js')
    @include('sales.return-exchanges._inc.script')
@endsection
