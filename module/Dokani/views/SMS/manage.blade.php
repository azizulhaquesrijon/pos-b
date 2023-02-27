@extends('layouts.master')


@section('title', 'Manage SMS')

@section('page-header')
    <i class="fa fa-info-circle"></i> Manage SMS
@stop


@push('style')

    <style>


        .phone_box{
            border: 1px solid #d9d9d9;
            padding: 10px;
        }

        .scroll_phone {
            height:220px;
            overflow-y: scroll;
        }

        textarea.form-control {
            height: 217px !important;
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

        .table-border-none td, tr {
            border: none !important;
        }
        .has-mobile {
            color: red !important;
        }
        .tags{
            width: 100% !important;
        }

        article {
            position: relative;
            width: 170px;
            height: 33px;
            float: left;
            border: 2px solid #428BCA;
            box-sizing: border-box;
            border-radius: 5px;
        }

        article div {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            line-height: 25px;
            transition: .5s ease;
        }

        article input {
            position: absolute;
            top: 0;
            left: 0;
            width: 140px;
            height: 100px;
            opacity: 0;
            cursor: pointer;
        }

        input[type=checkbox]:checked ~ div {
            background-color: #428BCA;
            border-radius: 5px;

        }

        input[type=checkbox]:checked ~ div b {
            color: white;
        }
        input[type=checkbox]:checked ~ div b:before {
            content: "✓";
        }


        .select2-selection__choice {
            background-color: #c9c9c9;
            border: none !important;
            border-radius: 0px !important;
            width: 46% !important;
            color: black !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #ff0303 !important;
            padding-right: 21px !important;
        }

    </style>

@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">

                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <div class="row">
                            <div class="col-md-8">

                                <div class="tabbable">
                                    <ul class="nav nav-tabs" id="myTab">
                                        <li class="active">
                                            <a data-toggle="tab" href="#manual" aria-expanded="true">
                                                <i class="green ace-icon fa fa-send bigger-120"></i>
                                                Manual
                                            </a>
                                        </li>

                                        <li class="">
                                            <a data-toggle="tab" href="#customer" aria-expanded="false">
                                                <i class="blue ace-icon fa fa-users bigger-120"></i>
                                                Customer
                                            </a>
                                        </li>

                                        <li class="">
                                            <a data-toggle="tab" href="#client" aria-expanded="false">
                                                <i class="blue ace-icon fa fa-users bigger-120"></i>
                                                Client
                                            </a>
                                        </li>

                                        <li class="">
                                            <a data-toggle="tab" href="#supplier" aria-expanded="false">
                                                <i class="blue ace-icon fa fa-users bigger-120"></i>
                                                Supplier
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="manual" class="tab-pane fade active in">
                                            @include('SMS._inc.manual')
                                        </div>

                                        <div id="customer" class="tab-pane fade">
                                            @include('SMS._inc.customer-sms')
                                        </div>

                                        <div id="client" class="tab-pane fade">
                                            @include('SMS._inc.client-sms')
                                        </div>

                                        <div id="supplier" class="tab-pane fade">
                                            @include('SMS._inc.supplier-sms')
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="widget-box">
                                    <div class="widget-header widget-header-flat">
                                        <h4 class="widget-title smaller">
                                            <i class="ace-icon fa fa-gear smaller-80"></i>
                                            SMS Configure
                                        </h4>
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <table class="table table-sm table-border-none" style="font-weight: bold !important;">
                                                        <tbody style="float: left">
                                                        <tr>
                                                            <td class="text-right">Remaining Balance</td>
                                                            <td width="10px">:</td>
                                                            <td width="30px"><span class="remaining-balance">{{ $balance }}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right">Character Count</td>
                                                            <td>:</td>
                                                            <td><span class="total-character-count">0</span>/640</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right">Sms Count</td>
                                                            <td>:</td>
                                                            <td><span class="part-count">0</span>/4</td>
                                                        </tr>
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')

    <script>


        var tag_input = $('#form-field-tags');
        if(! ( /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase())) )
        {
            tag_input.tag(
                {
                    placeholder:tag_input.attr('placeholder'),
                    source: ace.variable_US_STATES,
                }
            );
        }
        else {

            tag_input.after('<textarea id="'+tag_input.attr('id')+'" name="'+tag_input.attr('name')+'" rows="3">'+tag_input.val()+'</textarea>').remove();

        }


        $('.message-area').keyup(function () {
            let max = 640
            let part = 160 //max / 4;
            let len = $(this).val().length
            let partCount = (len/part) + 1
            if (partCount>4) {
                partCount = 4
            }
            if (len == 0) {
                partCount = 0
            }
            $('.part-count').text(parseInt(partCount))
            $('.total-character-count').text(len)
        });





        function allCustomer() {

            if ($('#all_customer').val() == 1){

                $('#all_customer').val(0)
                $('#selected_customer').empty()

                const all_customer = `<div class="phone_box scroll_phone">
                <select name="mobiles[]" data-placeholder="-- Select Customer Mobiles--"
                            style="width: 100%" class="form-control select2" multiple>
                        <option value=""></option>
                        @foreach($customers as $mobile)
                <option value="{{ '88'.$mobile }}" selected>{{ '88'.$mobile }}</option>
                        @endforeach
                </select></div>`

                $("#selected_customer").html(all_customer);
                $('.select2').select2();
            }

            else if ($('#all_customer').val() == 0) {

                $('#all_customer').val(1)
                $('#selected_customer').empty()

                const all_customer = `<div class="phone_box scroll_phone">
                <select name="mobiles[]" data-placeholder="-- Select Customer Mobiles--"
                            style="width: 100%" class="form-control select2" multiple>
                        <option value=""></option>
                        @foreach($customers as $mobile)
                <option value="{{ '88'.$mobile }}">{{ '88'.$mobile }}</option>
                        @endforeach
                </select></div>`

                $("#selected_customer").html(all_customer);
                $('.select2').select2();
            }

        }


        function allClient() {

            if ($('#all_client').val() == 1){

                $('#all_client').val(0)
                $('#selected_client').empty()

                const all_client = `<div class="phone_box scroll_phone">
                <select name="mobiles[]" data-placeholder="-- Select Client Mobiles--"
                            style="width: 100%" class="form-control select2" multiple>
                        <option value=""></option>
                        @foreach($clients as $mobile)
                <option value="{{ '88'.$mobile }}" selected>{{ '88'.$mobile }}</option>
                        @endforeach
                </select></div>`

                $("#selected_client").html(all_client);
                $('.select2').select2();
            }

            else if ($('#all_client').val() == 0) {

                $('#all_client').val(1)
                $('#selected_client').empty()

                const all_client = `<div class="phone_box scroll_phone">
                <select name="mobiles[]" data-placeholder="-- Select Client Mobiles--"
                            style="width: 100%" class="form-control select2" multiple>
                        <option value=""></option>
                        @foreach($clients as $mobile)
                <option value="{{ '88'.$mobile }}">{{ '88'.$mobile }}</option>
                        @endforeach
                </select></div>`

                $("#selected_client").html(all_client);
                $('.select2').select2();
            }

        }


        function allSupplier() {

            if ($('#all_supplier').val() == 1){

                $('#all_supplier').val(0)
                $('#selected_supplier').empty()

                const all_supplier = `<div class="phone_box scroll_phone">
                <select name="mobiles[]" data-placeholder="-- Select Supplier Mobiles--"
                            style="width: 100%" class="form-control select2" multiple>
                        <option value=""></option>
                        @foreach($suppliers as $mobile)
                <option value="{{ '88'.$mobile }}" selected>{{ '88'.$mobile }}</option>
                        @endforeach
                </select></div>`

                $("#selected_supplier").html(all_supplier);
                $('.select2').select2();
            }

            else if ($('#all_supplier').val() == 0) {

                $('#all_supplier').val(1)
                $('#selected_supplier').empty()

                const all_supplier = `<div class="phone_box scroll_phone">
                <select name="mobiles[]" data-placeholder="-- Select Supplier Mobiles--"
                            style="width: 100%" class="form-control select2" multiple>
                        <option value=""></option>
                        @foreach($suppliers as $mobile)
                <option value="{{ '88'.$mobile }}">{{ '88'.$mobile }}</option>
                        @endforeach
                </select></div>`

                $("#selected_supplier").html(all_supplier);
                $('.select2').select2();
            }

        }





    </script>



@stop

