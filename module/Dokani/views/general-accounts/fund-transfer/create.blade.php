@extends('layouts.master')

@section('title', 'Fund Transfer')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Fund Transfer
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
                        <a href="{{ route('dokani.customers.index') }}" class="">
                            <i class="fa fa-list-alt"></i> Customer List
                        </a>
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.ac.fund-transfers.store') }}" class="form-horizontal"
                            enctype="multipart/form-data">
                            @csrf


                            <!-- From -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    From<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <select name="from_account_id" id="from_account_id" class="form-control select2"
                                        data-placeholder="--Select Account--"
                                        data-selected="{{ old('from_account_id') }}">
                                        <option value=""></option>
                                        @foreach (account() as $key => $type)
                                            <option value="{{ $key }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    <div class="ajax-loader-acc" style="visibility: hidden;">
                                        <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 5%;position: absolute;left: 73px;"/>
                                    </div>
                                    <div style="font-size: 16px">
                                        <code class="from_balance"></code>
                                    </div>

                                    <input type="hidden" class="acc_balance" value="0">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">From Bank Info.</label>
                                <div class="col-md-5 col-sm-5" style="display: flex">
                                    <input type="text" name="from_check_no" placeholder="Enter Check No" class="form-control">&nbsp;&nbsp;
                                    <input type="text" name="from_check_date" placeholder="Enter Check Date" class="form-control date-picker" autocomplete="off">
                                </div>
                            </div>


                            <!-- To -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    To<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <select name="to_account_id" id="to_account_id" class="form-control select2"
                                        data-placeholder="--Select Account--" data-selected="{{ old('to_account_id') }}">
                                        <option value=""></option>
                                        @foreach (account() as $key => $type)
                                            <option value="{{ $key }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    <div class="ajax-loader" style="visibility: hidden;">
                                        <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 5%;position: absolute;left: 73px;"/>
                                    </div>
                                    <div style="font-size: 16px">
                                        <code class="to_balance"></code>
                                    </div>
                                </div>
                            </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-3 col-sm-3">To Bank Info.</label>
                                    <div class="col-md-5 col-sm-5" style="display: flex">
                                        <input type="text" name="to_check_no" placeholder="Enter Check No" class="form-control">&nbsp;&nbsp;
                                        <input type="text" name="to_check_date" placeholder="Enter Check Date" class="form-control date-picker" autocomplete="off">
                                    </div>
                                </div>


                            <!-- Amount -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Amount <sup
                                        class="text-danger">*</sup>:</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control only-number" id="paid_amount" step="any" type="text" name="amount"
                                        value="{{ old('address') }}" placeholder="Type Amount" />
                                </div>
                            </div>





                            <!-- Description -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="sales_price">Description :</label>
                                <div class="col-md-5 col-sm-5">
                                    <textarea name="description"
                                        class="form-control input-sm">{{ old('description') }}</textarea>
                                </div>
                            </div>







                            <!-- Action -->
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4"></label>
                                <div class="col-md-3 col-sm-3">
                                    <button type="submit" id="submit_btn" class="btn btn-primary col-md-12">
                                        <i class="fa fa-plus"></i> Add New
                                    </button>
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

        $('#from_account_id').on('change', function() {

            let account_id = $(this).find(':selected').val();

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

                    if (data.balance <= 0 || !data.balance){

                        $('.from_balance').text(data.balance ?? 0)
                        toastr.warning('No available balance');

                        $("#submit_btn").prop("disabled", true);
                    }
                    else {
                        $("#submit_btn").prop("disabled", false);
                        $('.from_balance').text(data.balance)
                        $('.acc_balance').val(data.balance)
                    }

                },
                complete: function(){
                    $('.ajax-loader-acc').css("visibility", "hidden");
                }
            });

        });


        $('#to_account_id').on('change', function() {

            let account_id = $(this).find(':selected').val();
            let from_account_id = $('#from_account_id').find('option:selected').val()

            $.ajax({
                type:'GET',
                url: "{{ route('dokani.get-account-balance-ajax') }}",
                data: {
                    _token: '{!! csrf_token() !!}',
                    account_id: account_id,
                },
                beforeSend: function(){
                    $('.ajax-loader').css("visibility", "visible");
                },
                success:function(data) {

                    if (account_id == from_account_id){

                        toastr.warning('Same Account Select');
                        $("#submit_btn").prop("disabled", true);
                    }
                    else {
                        $("#submit_btn").prop("disabled", false);
                    }

                    if (data.balance){
                        $('.to_balance').text(data.balance)
                    }
                    else {
                        $('.to_balance').text(0)
                    }
                },
                complete: function(){
                    $('.ajax-loader').css("visibility", "hidden");
                }
            });

        });



        $('#paid_amount').on('keyup',function() {
            let balance = Number($('.acc_balance').val());
            let paid_amount = Number($('#paid_amount').val());

            if (paid_amount > balance){

                toastr.warning('No available balance');

                $("#submit_btn").prop("disabled", true);
            }
            else {

                $("#submit_btn").prop("disabled", false);
            }

        });

    </script>






@endsection
