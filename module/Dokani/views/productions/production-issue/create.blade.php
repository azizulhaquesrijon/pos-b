@extends('layouts.master')

@section('title', 'Production')

@section('page-header')
    <i class="fa fa-plus"></i> Production
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


    <div class="main-content-inner">

        <div class="page-content">

            <!-- DYNAMIC CONTENT FROM VIEWS -->
            <div class="page-header" style="display: flex; justify-content: space-between;">
                <h4 class="page-title"><i class="fa fa-list"></i> Production - Raw Material Issue</h4>
                <div class="btn-group">
                    <a href="{{ route('dokani.production-issues.index') }}" class="btn btn-info btn-sm">
                        <i class="fa fa-list"></i>
                        Production Issue List
                    </a>
                </div>
            </div>

                <div class="row mb-1">

                    <div class="col-md-8 col-md-offset-2" style="position: relative;">

                        <input type="text" class="form-control product-search-input"
                               style="border-radius: 20px !important;padding: 20px"
                               id="search" placeholder="🔎︎ Product Name" autocomplete="off">

                        <div class="loader" style="right:0;display: none"></div>
                        <div class="product-search" style="position: absolute;z-index: 99999999999999999;background: white;"></div>
                    </div>
                    <div class="ajax-loader" style="visibility: hidden;">
                        <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 2%;position: relative;right: 54px;top: 7px;"/>
                    </div>
                </div>

            <form action="{{ route('dokani.production-issues.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered pos_table" id="pos-table" style="background-color: white; color: black !important; margin-bottom: 0px !important">
                                <thead style="border-bottom: 3px solid #346cb0 !important;" class="pos_thead">
                                <tr style="border: none; background: #dfdfdf !important; color: black !important;" class="pos_tr">
                                    <th width="3%" class="text-center">SL</th>
                                    <th width="15%">Product Name</th>
                                    <th width="10%">Product Code</th>
                                    <th width="10%">Product Commend</th>
                                    <th width="10%" class="text-center">Quantity</th>
                                    <th width="5%" class="text-center">Unit</th>
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

                                                <input type="hidden" name="product_unit_price[]"
                                                       value="{{ old('product_unit_price')[$key] }}" />
                                            </td>
                                            <td style="width: 20%"> {{ old('product_titles')[$key] }} </td>
                                            <td style="width: 15%"> {{ old('product_codes')[$key] }} </td>
                                            <td style="width: 15%">
                                                <input type="text" name="commends[]"  class="form-control" autocomplete="off">

                                                <input type="hidden" name="description[]" class="form-control"
                                                       value="{{ old('description')[$key] }}"
                                                       autocomplete="off">
                                            </td>
                                            <td style="width: 5%">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input class="form-control product_qty" type="number"
                                                            name="product_qty[]" onchange="calcTotalCost(this)"
                                                            value="{{ old('product_qty')[$key] }}">
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width: 2%">
                                                <a href="javascript:void(0)" class="text-danger" onclick="removeField(this)">
                                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>
                                            Total Amount: <input type="text" class="form-controll total_amount" id="totalAmount" name="total_amount" readonly tabindex="-1" value="">
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>


                            <table style="float: right; border: 1px solid #c6c5c5; width: 100%; margin: 0px;background: #f3f3f3">
                                <tr>
                                    <td></td>
                                    <td></td>

                                    <td style="padding: 5px; float: right;" >
                                        <div class="col-md-6" style="padding: 0 !important;">
                                            <button class="btn btn-primary" type="submit">Save</button>
                                            {{-- <a href="#add-new" role="button" data-toggle="modal"
                                               class="btn btn-sm btn-primary btn-block" style="background-color: #0044ff !important; border-color: #0044ff !important; border-radius: 0px !important;">
                                                <i class="far fa-money"></i> Save
                                            </a> --}}
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
    @include('productions.production-issue._inc.script')

    <script>

        function calculateSubtotal() {

            let totalAmount = 0;

            $('.quantity').each(function() {
                let quantity    = Number($(this).val());
                let unitCost    = Number($(this).closest('tr').find('.unit-cost').val());

                totalAmount     += Number(unitCost * quantity);
            })

            $('#totalAmount').val(totalAmount.toFixed(2));
        }


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




        function updateTotalCost(qty, cost){
            let newAmount = qty * cost;
            let totalAmount = $('.total_amount');

            let total = totalAmount.val()+newAmount;

            console.log(total);

            totalAmount.val(total);


        }

        function upp(){
            console.log("ok");
        }



    </script>



@endsection

