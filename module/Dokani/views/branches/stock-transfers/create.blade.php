@extends('layouts.master')

@section('title', 'Add New Stock Transfer')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Add Stock Transfer
@stop

@section('css')
    <style>
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
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">
                        @if (hasPermission('dokani.stock-transfers.create', $slugs))
                            <a href="{{ route('dokani.stock-transfers.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Stock Transfer List
                            </a>
                        @endif
                    </span>
                </div>

                 <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        @include('partials._alert_message')


                        <!-- PURCHASE CREATE FORM -->
                        <form id="stock-transfer-form" class="form-horizontal" action="{{ route('dokani.stock-transfers.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="input-group mb-1 width-100" style="width: 100%">
                                        <span class="input-group-addon" style="width: 40%; text-align: left">
                                            Branch ( From )<span class="label-required"> *</span>
                                        </span>
                                        <select name="from_branch_id" id="branch_id" data-placeholder="- Select -" tabindex="2" class="form-control select2" style="width: 100%" required onchange="resetTable()">
                                            <option value="null">Head Office</option>
                                            @foreach(dokanBranches() as $id => $name)
                                                <option value="{{ $id }}" >{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group mb-1 width-100" style="width: 100%">
                                        <span class="input-group-addon" style="width: 40%; text-align: left">
                                            Branch ( To )<span class="label-required"> *</span>
                                        </span>
                                        <select name="to_branch_id" id="to_branch_id" data-placeholder="- Select -" tabindex="2" class="form-control select2" style="width: 100%" required>
                                            <option></option>
                                            @foreach(dokanBranches() as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group mb-1 width-100" style="width: 100%">
                                        <span class="input-group-addon" style="width: 40%; text-align: left">
                                            Date<span class="label-required">*</span>
                                        </span>
                                        <input type="text" name="date" id="date" tabindex="3" class="form-control date-picker" value="{{ old('date', date('Y-m-d')) }}" autocomplete="off" data-date-format="yyyy-mm-dd" required>
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


                                {{-- <div id="searchProduct" class="col-sm-12 search-purchase-product">
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-2 search-any-product">
                                            <div class="input-group mb-1 width-100" style="width: 100%">
                                                <span class="input-group-addon width-10" style="text-align: left; background-color: #e1ecff; color: #000000;">
                                                    Search<span class="label-required"></span>
                                                </span>
                                                <div style="position: relative;">
                                                    <input type="text" class="form-control" name="product_search" id="searchProductField"  placeholder="Scan Your Barcode or SKU" onkeyup="branchSelectValidation(this, event)" autocomplete="off">

                                                    <div class="dropdown-content live-load-content">


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                            </div>


                            <!-- STOCK TRANSFER TABLE -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered fixed-table-header" id="purchaseTable">
                                            <thead>
                                                <tr class="table-header-bg">
                                                    <th width="20%">Product</th>
                                                    <th width="10%">Barcode</th>
                                                    <th width="7%">Unit</th>
                                                    <th width="10%" class="text-center">Unit Cost</th>
                                                    <th width="8%" class="text-center">Current Stock</th>
                                                    <th width="8%" class="text-center">Transfer Qty</th>
                                                    <th width="12%">Comment</th>
                                                    <th width="5%" class="text-center"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="stock-table-body">

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-3 col-md-offset-9">

                                    <div class="input-group mb-1 width-100">
                                        <span class="input-group-addon" style="width: 40%; text-align: left">
                                            Total Quantity
                                        </span>
                                        <input type="text" class="form-control text-right total-quantity" name="total_quantity" id="total_quantity" value="{{ old('due_amount') }}" readonly>
                                    </div>
                                    <div class="input-group mb-1 width-100">
                                        <span class="input-group-addon" style="width: 40%; text-align: left">
                                            Total Amount
                                        </span>
                                        <input type="text" class="form-control text-right total-amount" name="total_amount" id="total_amount" value="{{ old('due_amount') }}" readonly>
                                    </div>
                                    <div class="input-group mb-1 width-100">
                                        <span class="input-group-addon" style="width: 40%; text-align: left">
                                            Transfer Cost
                                        </span>
                                        <input type="text" class="form-control text-right" name="transfer_cost" id="transfer_cost" value="{{ old('due_amount',0) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row my-1">
                                <div class="btn-group" style="float: right;margin-right:8px">
                                    <a class="btn btn-sm btn-default" href="{{ route('dokani.stock-transfers.index') }}"> <i class="fa fa-bars"></i> list </a>
                                    <button class="btn btn-sm btn-info" type="submit" name="is_approved" value="0"> <i class="fa fa-save"></i> Save </button>
                                    <button class="btn btn-sm btn-success" type="submit" name="is_approved" value="1"> <i class="far fa-save"></i> Save & Approved </button>
                                </div>
                            </div>

                        </form>
                        <!-- FORM END -->

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



@section('script')

    <script src="{{ asset('assets/js/jquery.ba-throttle-debounce.js') }}"></script>
    @include('branches.stock-transfers._inc.script')

@endsection
