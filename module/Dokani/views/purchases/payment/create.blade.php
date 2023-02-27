@extends('layouts.master')

@section('title', 'Add Payment')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Add Payment
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
                        @if (hasPermission('dokani.payments.index', $slugs))
                            <a href="{{ route('dokani.payments.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Payment List
                            </a>
                        @endif
                    </span>
                </div>


                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form class="form-horizontal" action="{{ route('dokani.payments.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label"> Supplier</label>
                                    <div class="col-sm-9">
                                        <select name="supplier_id" id="supplier"
                                            class="supplier_name form-control chosen-select"
                                            data-placeholder="--Choose Supplier--" required>
                                            <option></option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    data-mobile="{{ $supplier->mobile }}"
                                                    data-pre_due="{{ $supplier->balance }}"
                                                    data-supplier_id="{{ $supplier->id }}">
                                                    {{ $supplier->name }}
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
                                    <label class="control-label col-sm-3 no-padding-right" for="food">Supplier
                                        Mobile</label>
                                    <div class="col-sm-9">
                                        <input type="text" id="supplier_mobile" class="form-control"
                                            placeholder="Supplier Mobile" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Pay Amount
                                    </label>
                                    <div class="col-sm-9">
                                        <input name="paid_amount" value="{{ old('paid_amount') }}" type="number"
                                            id="pay_amount" placeholder="Amount" class="form-control" required />
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Previous Due
                                    </label>
                                    <div class="col-sm-9">
                                        <input name="payable_amount" value="" type="text" id="pre_due"
                                            placeholder="Previous Due" class="form-control" readonly required />
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Current
                                        Amount</label>
                                    <div class="col-sm-9">
                                        <input name="current_amount" value="{{ old('currentbalance') }}" step="0.01"
                                            type="text" id="current_amount" placeholder="Current Balance"
                                            class="form-control" readonly required />
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">

                                <div class="col-sm-6">
                                    <label class="control-label col-sm-3 no-padding-right" for="food">Account</label>
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
                                        <input type="hidden" class="acc_balance" value="0">
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
                                        <button class="btn btn-info" type="submit" id="submit_btn">
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

    @include('partials._account-balance-condition-script')


    <script src="{{ asset('assets/custom_js/file_upload.js') }}"></script>

    <script>
        $('.supplier_name').change(function() {
            var mobile = $(this).find(':selected').data('mobile') | 0
            var pre_due = Number($(this).find(':selected').data('pre_due'))

            $('#supplier_mobile').val(mobile)
            $('#pre_due').val(pre_due)
            $('#current_amount').val(pre_due)
            $('#supplier_id').val($(this).find(':selected').data('supplier_id'))
            // $('#pay_amount').prop('max', pre_due)
        })


        $('#pay_amount').on('keyup', function() {
            var pay_amount = Number($(this).val() | 0);
            var pre_due = Number($('#pre_due').val());

            var current_total = Number(pre_due - pay_amount);

            $('#current_amount').val(current_total);

        })

        $('#pay_amount').on('keyup', function () {

            let balance = Number($('.acc_balance').val());
            let acc_pay_amount = Number($('#pay_amount').val());

            if (acc_pay_amount > balance){

                toastr.warning('No available balance');

                $("#submit_btn").prop("disabled", true);
            }
            else {

                $("#submit_btn").prop("disabled", false);
            }
        })


    </script>
@endsection
