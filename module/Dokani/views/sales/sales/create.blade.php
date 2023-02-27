@extends('layouts.pos')

@section('title', 'Customer')

@section('page-header')
    <i class="fa fa-plus"></i> Sale Create
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}" />

    <style>
        .card {
            margin: -10px;
            position: relative;
            display: -webkit-box;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #FFF;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.25rem;
        }

        .card-body {
            margin-left: 4px !important;
            margin-right: 4px !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-10">

            @include('partials._alert_message')

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{ route('acc_customers.index') }}"
                                    class="dt-button btn btn-white btn-info btn-bold" title="List" data-toggle="tooltip"
                                    tabindex="0" aria-controls="dynamic-table">
                                    <span>
                                        <i class="fa fa-list bigger-110"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space"></div>

                <!-- INPUTS -->
                <form action="{{ route('acc_customers.store') }}" method="post">
                    @csrf
                    <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                        <div class="col-lg-12">
                            {{-- <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label for="exampleInputName2">Shop</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-map-marker"></i>
                                            </span>
                                            <select name="account_id" id="account_info" class="chosen-select form-control">
                                                <option value="showroom" data-total-amount="0" data-select2-id="11">Showroom</option>
                                            </select>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleInputName2">Suppiler</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-user"></i>
                                            </span>
                                            <input class="form-control" type="text" placeholder="Customer">
                                            <span class="input-group-addon" >
                                                <a href="#"><i class="ace-icon fa fa-plus-circle fa-lg"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label for="exampleInputName2">Shop</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-map-marker"></i>
                                            </span>
                                            <select name="account_id" id="account_info" class="form-control">
                                                <option value="showroom" data-total-amount="0" data-select2-id="11">Showroom</option>
                                            </select>
                                            <span class="input-group-addon" >
                                                <a href="#"><i class="ace-icon fa fa-plus-circle fa-lg"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="exampleInputName2">Date</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa-calendar bigger-110"></i>
                                            </span>
                                          <input type="text" name="sale_date" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered" style="display: none">
                                <thead>
                                    <tr>
                                        <th>Choose Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Cost</th>
                                        <th>Free Qty</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="mgrid">
                                        <td>
                                            <div class="form-group">

                                                <input type="text" class="form-control passcoder" id="product"
                                                    placeholder="Search Product..." autocomplete="off">
                                                <div id="product_list"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control passcoder" id="quantity"
                                                placeholder="Quantity" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control passcoder addItem" id="product_price"
                                                placeholder="Price" autocomplete="off">

                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="free_qty" id=""
                                                placeholder="Free Quantity" autocomplete="off">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="addProduct(this)"><i class="ace-icon fa fa-plus"
                                                    style="font-family: FontAwesome, Bangla246, sans-serif;"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Choose Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Cost</th>
                                        <th>Free Qty</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="mgrid">
                                        <td>
                                            <div class="form-group">

                                                <input type="text" class="form-control passcoder" id="product"
                                                    placeholder="Search Product..." autocomplete="off">
                                                <div id="product_list"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control passcoder" id="quantity"
                                                placeholder="Quantity" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control passcoder addItem" id="product_price"
                                                placeholder="Price" autocomplete="off">

                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="free_qty" id=""
                                                placeholder="Free Quantity" autocomplete="off">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="addProduct(this)"><i class="ace-icon fa fa-plus"
                                                    style="font-family: FontAwesome, Bangla246, sans-serif;"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="product-item">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="3%">SL</th>
                                            <th>Product Name</th>
                                            <th>Product Code</th>
                                            <th>Qty</th>
                                            <th>Unit Cost</th>
                                            <th>Free Qty</th>
                                            <th>Sub Total</th>
                                            <th style="width: 5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="mgrid">
                                            <td>
                                               1
                                            </td>
                                            <td>
                                                Sample Product
                                            </td>
                                            <td>
                                               Sample-code-123
                                            </td>
                                            <td>
                                                <input type="text" name="qty[]" class="from-control product-qty" value="10">
                                            </td>
                                            <td>
                                                <input type="text" name="qty[]" class="from-control product-cost" value="1200">
                                            </td>
                                            <td>
                                                <input type="text" name="qty[]" class="from-control free-qty" value="0">
                                            </td>
                                            <td>
                                                <strong class="subtotal">12000</strong>
                                            </td>
                                            <td>
                                                <button class="btn btn-xs btn-danger">
                                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <div class="col-md-2">
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        Summary
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label style="margin-bottom: 0">Total</label>
                        <input type="text" tabindex="-1" name="subtotal" value="0" id="subtotal"
                            class="form-control item-box" readonly="" placeholder="Total">
                    </div>
                    <div class="form-group">
                        <label style="margin-bottom: 0">Discount (Deduction)</label>
                        <input type="text" class="form-control grand-total-calculate" name="invoice_discount"
                            id="invoice_discount" onkeypress="return event.charCode >= 46 &amp;&amp; event.charCode <= 57"
                            value="0">
                    </div>
                    <div class="form-group">
                        <label style="margin-bottom: 0">Tax/Others (Addition)</label>
                        <input type="text" onkeypress="return event.charCode >= 46 &amp;&amp; event.charCode <= 57"
                            class="form-control grand-total-calculate" name="invoice_tax" id="invoice_tax" value="0">
                    </div>
                    <div class="form-group advance-group">
                        <label style="margin-bottom: 0">Advance</label>
                        <input type="number" class="form-control" tabindex="-1" name="advanced" readonly="" id="advance"
                            value="0">
                    </div>
                    <div class="form-group prevDue-group">
                        <label style="margin-bottom: 0">Previous Due</label>
                        <input type="number" class="form-control" name="previous_due" tabindex="-1" readonly=""
                            id="previous_due" value="">
                    </div>
                    <div class="form-group">
                        <label style="margin-bottom: 0">Total Payable</label>
                        <input type="text" tabindex="-1" name="total_payable" value="" class="form-control"
                            id="total_payable_temp" readonly=""
                            style="font-size: 24px;font-weight: bolder; color: #0d1214;">
                    </div>
                    <div class="form-group">
                        <label style="margin-bottom: 0">Given Amount</label>
                        <input type="text" onkeypress="return event.charCode >= 46 &amp;&amp; event.charCode <= 57"
                            name="total_paid_amount" id="total_paid" value="0"
                            class="form-control given-amount grand-total-calculate">
                    </div>
                    <div class="form-group">
                        <label for="account_info" style="margin-bottom: 0 !important;">Account Information</label>
                        <select name="account_id" id="account_info" class="chosen-select form-control">
                            <option value="1" data-total-amount="0" data-select2-id="11">ASTL Cash , Office Cash</option>
                            <option value="3" data-total-amount="0">ASTL Brac Bank , 0001</option>
                        </select>
                    </div>

                    <div class="row px-1">
                        <div class="col-sm-7" style="padding: 0 !important;">
                            <button type="button" class="btn btn-sm btn-primary btn-block save-btn"><i class="fa fa-save"
                                    style="font-family: FontAwesome, Bangla905, sans-serif;"></i> Save</button>
                        </div>
                        <div class="col-sm-5" style="padding: 0 !important;">
                            <a href="#" class="btn btn-sm btn-success btn-block"
                                style="width: 100%">List</a>
                        </div>
                    </div>
                    <div class="space"></div>
                </div>
            </div>
        </div>

    </div>


@endsection

@section('js')
    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace.min.js') }}"></script>
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>

    <script>
        jQuery('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            todayHighlight: true
        });

    </script>
@endsection
