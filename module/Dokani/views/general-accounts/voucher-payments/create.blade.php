@extends('layouts.master')

@section('title', 'Add ' . ucfirst(request('type')))

@section('page-header')
    <i class="fa fa-plus-circle"></i> Add {{ request('type') }}
@stop



@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')


            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">
                        <a href="{{ route('dokani.products.index') }}" class="">
                            <i class="fa fa-list-alt"></i> Product List
                        </a>
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form class="form-horizontal" action="{{ route('dokani.voucher-payments.store') }}" method="POST"
                            role="form">
                            @csrf

                            <input type="hidden" name="type" class="type" value="{{ request('type') }}" required>

                            <div class="form-row container">

                                <div class="form-group col-md-4 mx-1">

                                    <select name="party_id" class="form-control chosen-select"
                                        data-placeholder="--Choose Party--" required>
                                        <option></option>
                                        @foreach ($parties as $party)
                                            <option value="{{ $party->id }}">
                                                {{ $party->name }}({{ $party->mobile }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
{{--                                <div class="col-md-1"></div>--}}
                                <div class="form-group col-md-3 mx-1">

                                    <select name="account_id" id="account_type_id" class="form-control select2"
                                            data-placeholder="-- Select Account Name --" required>
                                        <option value=""></option>
                                        @foreach (account() as $key => $type)
                                            <option value="{{ $key }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    <div class="ajax-loader-acc" style="visibility: hidden;">
                                        <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 9%;position: absolute;left: 73px;"/>
                                    </div>
                                    <div style="font-size: 16px">
                                        <code class="balance"></code>
                                    </div>
                                </div>

                                <div class="form-group col-md-3 mx-1">
                                    <input type="date" class="form-control date-picker" name="date"
                                        value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                                </div>
                            </div>


                            <div class="container">
                                <div class="table-responsive form-row">
                                    <table class="table table-bordered" id="form_data">
                                        <thead>
                                            <tr>
                                                <th>S/L</th>
                                                <th style="width:55%">Chart Of Account</th>
                                                <th style="width:35%">Paid Amount</th>
                                                <th style="width:10%;" class="text-center">
                                                    <span style="background-color: transparent;border: none; color: white">
                                                        <i class="ace-icon fa fa-times-circle" style="font-size: 15px"></i>
                                                    </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="row1">
                                                <td>1</td>
                                                <td>
                                                    <select name="chart_of_account_ids[]" class="form-control select2"
                                                        data-placeholder="--{{ request()->type == 'income' ? 'Income' : 'Expense' }} Type--"
                                                        required>
                                                        <option></option>
                                                        @foreach ($general_accounts as $item)
                                                            <option value="{{ $item->id }}">
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <!-- <label for="inputEmail4">Paid Amount<sup>*</sup></label> -->
                                                    <input name="amount[]" type="number" onkeyup="totalAmount()"
                                                        placeholder="Enter Amount" class="form-control pamount" required />
                                                    @error('amount')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </td>
                                                <td id="plusBtn" class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="text-danger" disabled
                                                                style="background-color: transparent;border: none;">
                                                            <i class="ace-icon fa fa-times-circle" style="font-size: 21px; color: #a944428c"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <input type="hidden" id="acc_balance" value="0">
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>
                                                    <div style="display: flex">
                                                        <input type="text" name="check_no" placeholder="Enter Check No" class="form-control">&nbsp;&nbsp;
                                                        <input type="text" name="check_date" placeholder="Enter Check Date" class="form-control date-picker" autocomplete="off">
                                                    </div>

                                                </th>
                                                <th>
                                                    <input type="text" class="form-control total" value="0"
                                                        style="border-radius: 5px; border:1px solid gray" readonly>
                                                </th>
                                                <th class="text-center">
                                                    <button type="button" onclick="add_row()" class="text-primary"
                                                            style="background-color: transparent;border: none;">
                                                        <i class="ace-icon fa fa-plus-circle" style="font-size: 21px"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" name="note" placeholder="Type Note">
                                    </div>
                                    <div class="col-sm-2"></div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="control-label col-sm-3 no-padding-right"></label>
                                            <button class="btn btn-info" type="submit" id="submit_btn">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Submit
                                            </button>
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
@endsection



@section('js')


    <script src="{{ asset('assets/custom_js/file_upload.js') }}"></script>

    <script>
        function add_row() {
            var rowno = $("#form_data tbody tr").length;
            var rowno = rowno + 1;
            var addRow =
                `<tr id='row${rowno}'>
                    <td>${rowno}</td>
                    <td>
                        <select name="chart_of_account_ids[]" class="form-control select2"
                            data-placeholder="--Income Type--" required>
                            <option></option>
                            @foreach ($general_accounts as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input name="amount[]" onkeyup="totalAmount()" type="number" placeholder="Enter Amount" class="form-control pamount" required/>
                        @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </td>
                    <td id="btntd" class="text-center">
                        <div class="btn-group">
                            <button type="button" id="removefield${rowno}" value="row${rowno}"
                            style="background-color: transparent;border: none;"
                            onclick="delete_row('row${rowno}')">
                            <i class="ace-icon fa fa-times-circle text-danger" style="font-size: 21px;"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            $("#form_data tbody tr:last").after(addRow);
            $('.select2').select2();
        }






        function totalAmount() {
            let total_val = Number($('.total').val());
            var total = 0

            $('.pamount').each(function() {
                let amount = Number($(this).val())
                total += amount
                $('#paid_amount').val(total)
            })
            $(".total").val(total);
        }




        function delete_row(rowno) {
            $('#' + rowno).remove();
            totalAmount()
        }


        $('#account_type_id').on('change', function() {

            let account_id = $(this).find(':selected').val();
            let type = $('.type').val();
            let total = $('.total').val();

            $.ajax({
                type:'GET',
                url: "{{ route('dokani.get-account-balance-ajax') }}",
                data: {
                    _token: '{!! csrf_token() !!}',
                    account_id: account_id,
                },
                beforeSend: function(){
                    $('.ajax-loader-acc').css("visibility", "visible");
                },
                success:function(data) {
                    if (type == 'income'){
                        if (data.balance){
                            $('.balance').text(data.balance)
                        }
                        else {
                            $('.balance').text(0)
                        }
                    }else {
                        if (data.balance <= 0 || !data.balance){

                            $('.balance').text(data.balance ?? 0)
                            toastr.warning('No available balance');

                            $("#submit_btn").prop("disabled", true);
                        }

                        else {
                            $("#submit_btn").prop("disabled", false);
                            $('.balance').text(data.balance)
                            $('#acc_balance').val(data.balance)
                        }
                    }

                },
                complete: function(){
                    $('.ajax-loader-acc').css("visibility", "hidden");
                }
            });

        });


    </script>
@endsection
