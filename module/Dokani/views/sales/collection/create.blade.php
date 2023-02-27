@extends('layouts.master')

@section('title', 'Add Collection')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Add Collection
@stop

@push('style')

    <style>
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
                        @if (hasPermission('dokani.collections.create', $slugs))
                            <a href="{{ route('dokani.collections.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Collection List
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form class="form-horizontal" action="{{ route('dokani.collections.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label"> Customer</label>
                                    <div class="col-sm-9">
                                        <select name="customer_id" id="customer"
                                            class="customer_name form-control chosen-select"
                                            data-placeholder="--Choose Customer--" required>
                                            <option></option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    data-mobile="{{ $customer->mobile }}"
                                                    data-pre_due="{{ $customer->balance }}"
                                                    data-refer_value="{{ $customer->refer_balance }}"
                                                    data-customer_id="{{ $customer->id }}">
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date</label>
                                    <div class="col-sm-9">
                                        <input name="date" type="text" value="{{ Carbon\Carbon::now()->format('Y/m/d') }}"
                                            placeholder="date" class="form-control date-picker" required />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="control-label col-sm-3 no-padding-right" for="food">Customer
                                        Mobile</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="customer_mobile" class="form-control"
                                            placeholder="Customer Mobile" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Pay Amount
                                    </label>
                                    <div class="col-sm-9">
                                        <input name="paid_amount" value="{{ old('paid_amount') }}" type="number"
                                            id="pay_amount" placeholder="Amount" class="form-control" max="" required />
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Previous Due
                                    </label>
                                    <div class="col-sm-9">
                                        <input name="payable_amount" value="" type="text" id="pre_due" placeholder="Previous Due"
                                            class="form-control" readonly required />
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Current
                                        Amount</label>
                                    <div class="col-sm-9">
                                        <input name="current_amount" value="{{ old('current_balance') }}" step="0.01"
                                            type="text" id="current_amount" placeholder="Current Balance"
                                            class="form-control" readonly required />
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
{{--                                <div class="col-sm-6">--}}
{{--                                    <label class="col-sm-3 control-label no-padding-right" style="margin-right: 13px;" for="form-field-1">Use Refer Value</label>--}}
{{--                                    <div class="col-sm-9" style="background: gainsboro;padding: 10px;height: 51px;width: 71%;">--}}
{{--                                        <input name="" class="refer" type="checkbox" id="test3">--}}
{{--                                        <label for="test3" style="font-size: 18px; font-weight: 500; color: #000000 !important">--}}
{{--                                            Refer Value : <span class="customer_refer"></span>--}}
{{--                                        </label>--}}
{{--                                        <span style="float: right;font-size: 15px;color: #666666; display:none;" class="refer_show">--}}
{{--                                            <input type="hidden" name="customer_refer_value" id="customer_refer_value">--}}
{{--                                            <input type="number" name="refer_amount" class="form-control use_refer" value=""--}}
{{--                                                   id="use_refer" placeholder="Enter Refer Value" onkeyup="updateBalance(this)" style="height: 28px !important;">--}}
{{--                                        </span>--}}

{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-sm-6">
                                    <label class="control-label col-sm-3 no-padding-right" for="food">Receive Amount</label>
                                    <div class="col-sm-9">
                                        <select name="account_id" id="account_type_id"
                                                data-placeholder="-- Select Account --" class="form-control select2" required>
                                            <option></option>
                                            @foreach(account() as $key => $item)
                                                <option value="{{ $key }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                        <div class="ajax-loader-acc" style="visibility: hidden;">
                                            <img src="{{ asset('assets/images/loading.gif') }}" class="img-responsive" style="width: 7%;position: absolute;left: 13px;top: 35px;"/>
                                        </div>
                                        <div style="font-size: 16px">
                                            <code class="balance"></code>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <label class="control-label col-sm-3 no-padding-right">Bank Info.</label>
                                    <div class="col-sm-9" style="display: flex">
                                        <input type="text" name="check_no" placeholder="Enter Check No" class="form-control">&nbsp;&nbsp;
                                        <input type="text" name="check_date" placeholder="Enter Check Date" class="form-control date-picker" autocomplete="off">
                                    </div>
                                </div>

                            </div>


                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1">
                                    <label class="control-label col-sm-3 no-padding-right"></label>
                                    <div class="col-sm-9">
                                        <button class="btn btn-info" type="submit">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Submit
                                        </button>
                                        &nbsp; &nbsp; &nbsp;
                                        <button class="btn" type="reset">
                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                            Reset
                                        </button>
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

    @include('partials._account-balance-script')


    <script src="{{ asset('assets/custom_js/file_upload.js') }}"></script>

    <script>
        $('.customer_name').change(function() {
            var mobile = $(this).find(':selected').data('mobile') | 0
            var pre_due = Number($(this).find(':selected').data('pre_due'))
            $('#customer_mobile').val(mobile)
            $('#pre_due').val(pre_due)
            $('#current_amount').val(pre_due)
            $('#customer_id').val($(this).find(':selected').data('customer_id'))
            $('#pay_amount').prop('max', pre_due)
        })



        $('#pay_amount').on('keyup', function() {
            // let use_refer = Number($('#use_refer').val());
            var pay_amount = Number($(this).val() | 0);
            var pre_due = Number($('#pre_due').val());
            var current_total = Number(pre_due - pay_amount);
            $('#current_amount').val(current_total);
        })



        $('#customer').on('change', function() {

            var refer_amount = $('#customer').find(':selected').data('refer_value');

            $('.customer_refer').text(refer_amount);
            $('#customer_refer_value').val(refer_amount);

        });

        $(".refer").change(function() {

            if ($(this).is(":checked")) {

                $('.refer_show').show()
            } else {

                $('.refer_show').hide()
            }

        })


        function updateBalance(object)
        {
            let refer_amount = Number($('#customer').find(':selected').data('refer_value'));
            let balance = Number($('.use_refer').val());
            let current_amount = Number($('#current_amount').val());

            if (refer_amount <= 0){
                toastr.warning('No available balance')
                $('.use_refer').val(0)
            }
            else {

                let new_balance = current_amount - balance ;

                $('#current_amount').val(new_balance)

                if (balance > refer_amount) {
                    toastr.warning('No available balance')
                    $('.use_refer').val(refer_amount)
                }
            }

        }

    </script>
@endsection
