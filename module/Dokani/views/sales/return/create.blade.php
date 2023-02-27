@extends('layouts.master')


@section('title', 'Sale Return')

@section('page-header')
    <i class="fa fa-plus"></i> Sale Return
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')


            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <div class="row">
                            <div class="col-xs-12">

                                <form id="damage-Form" class="form-horizontal"
                                    action="{{ route('dokani.sale-returns.store') }}" method="post">


                                    @csrf
                                    <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                                        <div class="col-lg-12">


                                            <div class="row">

                                                <div class="col-lg-4">
                                                    <select name="customer_id" class="chosen-select-100-percent"
                                                        data-placeholder="--Chose Customer--">
                                                        <option></option>
                                                        @foreach ($customers as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->default == 1 ? 'selected' : '' }}>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-4">
                                                    @include('includes.input-field', [
                                                        'name' => 'references',
                                                        'title' => 'Reference',
                                                    ])
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 control-label">
                                                            <b>Date</b>
                                                        </label>
                                                        <div class="col-sm-9">
                                                            <input id="date" name="date" type="text"
                                                                class="form-control input-sm date-picker" placeholder="Date"
                                                                value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width="50%">Product Name</th>
                                                            <th class="text-center" width="20%">Return Qty</th>
                                                            <th class="text-center">Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <select id="product " name="product_id"
                                                                    class="form-control chosen-select product-select"
                                                                    onchange="changeProduct(this)">
                                                                    <option></option>
                                                                    @foreach ($products as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            data-quantity="{{ $item->returnable_qty ?? 0 }}"
                                                                            data-price="{{ $item->sell_price ?? 0 }}">
                                                                            {{ $item->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="text-center">
                                                                <p><span class="available_quantity">0</span></p>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="number" id="quantity" class="form-control">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered" id="table">
                                                        <thead>
                                                            <tr>
                                                                <th width="3%">Sl.</th>
                                                                <th width="25%">Product Name</th>
                                                                <th width="25%">Returnable Qty</th>
                                                                <th width="25%">Description</th>
                                                                <th width="10%">Type</th>
                                                                <th width="10%">Quantity</th>
                                                                <th width="5%"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="product-damage">

                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row justify-content-end pb-0">
                                                <div class="col-md-12">
                                                    <div class="form-group float-right" style="float: right!important">
                                                        <button type="button" id="submitBtn" class="btn btn-success btn-xs">
                                                            <i class="fa fa-save"></i> Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
        /***************/
        $('.show-details-btn').on('click', function(e) {
            e.preventDefault();
            $(this).closest('tr').next().toggleClass('open');
            $(this).find(ace.vars['.icon']).toggleClass('fa-eye').toggleClass('fa-eye-slash');
        });
        /***************/
    </script>


    <script>
        function changeProduct(object) {
            let _this = $(object).find(':selected');
            let available_qty = _this.data('quantity');
            $('.available_quantity').text(available_qty);
            $('#quantity').focus();
        }

        $('#quantity').keypress(function(e) {

            let _this = $('#product').find(':selected');
            let product_id = _this.val();
            let product_name = _this.text();
            let available_qty = _this.data('quantity');
            let product_code = _this.data('product_code');
            let price = _this.data('price');

            let input_qty = $(this).val();
            let selected_qty = $('.available_quantity').text();
            let keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == 13) {
                let is_added = true;

                if ((input_qty == '') || (input_qty == 0) || (selected_qty == '')) {
                    is_added = false
                    return alert('Select product and add quantity');
                }
                if (is_added == true) {
                    addRow(product_id, product_name, available_qty, input_qty, price)

                    tableRowCheck()
                    $("#product option[value=" + product_id + "]").prop('disabled', true).trigger("chosen:updated");
                    $('#quantity').val('')
                }
            }
        })


        $('#submitBtn').click(function() {

            if ($('.tr_serial').length == 1) {
                alert('Please add atleast one Product.')
            } else {
                $('#damage-Form').submit();
            }

        })

        function addRow(product_id, product_name, available_qty, qty, price) {
            const htmlString = `
            <tr class="r-row">
                <td><span class="tr_serial"></span></td>
                <td>
                   <input type="hidden" value="${product_id}" class="product-ids" name="product_ids[]">
                   <input type="hidden" value="${price}" name="product_price[]">
                   <span>${product_name}</span>
                </td>
                <td>
                    <input type="text" name="available_qty[]" class="form-control available-quantity" value="${available_qty}"
                           placeholder="Available Quantity" readonly>
                </td>
                <td>
                    <textarea name="description[]" class="form-control input-sm" placeholder="Description"></textarea>
                </td>
                <td>
                    <select name="conditions[]" class="form-control">
                        <option value="good">Good</option>
                        <option value="damaged">Damaged</option>
                    </select>
                </td>
                <td>
                    <input value="${qty}" onkeyup="checkQuantity(this, event)" class="form-control" name="quantity[]"
                    type="text" placeholder="Return Quantity" readonly>
                </td>
                <td>
                    <button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-sm"><i class="fa
                    fa-trash"></i></button>
                </td>
            </tr>
            `;
            $('.product-damage').append(htmlString);

            serial()

            // setAvailableQuantity()

        }

        function serial() {
            $('.tr_serial').each(function(index) {
                $(this).text(index + 1)
            })
        }


        function deleteRow(object) {

            $("#product option[value=" + $(object).closest('tr').find('.product-ids').val() + "]").prop('disabled', true)
                .trigger("chosen:updated");
            chosenSelectInit()
            $(object).closest('tr').remove()
            tableRowCheck()
        }



        function tableRowCheck() {
            if ($('#table tbody').length > 0) {
                $('#submitBtn').prop("disabled", false)
            }

        }
    </script>
@endsection
