@extends('layouts.master')

@section('title', 'SMS Api Manage')

@section('page-header')
    <i class="fa fa-plus-circle"></i> SMS Api Manage
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
                        <a href="{{ route('dokani.shop.sms.list') }}" class="">
                            <i class="fa fa-list-alt"></i> SMS List
                        </a>
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.shop.sms.store') }}" class="form-horizontal">
                        @csrf



                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Dokan<sup class="text-danger">*</sup>
                                    :</label>
                                <div class="col-md-5 col-sm-5">
                                    <select name="dokan_id" class="form-control chosen-select"
                                            data-placeholder="-- Select Dokan --" id="dokan_id" style="width: 100%" required>
                                        <option></option>
                                        @foreach($dokans as $item)
                                            <option value="{{ $item->id }}"
                                                    data-base_url="{{ optional($item->smsApi)->base_url ?? 'http://smpp.ajuratech.com:7788/sendtext' }}"
                                                    data-api_key="{{ optional($item->smsApi)->api_key }}"
                                                    data-secret_key="{{ optional($item->smsApi)->secret_key }}"
                                                    data-caller_id="{{ optional($item->smsApi)->caller_id ?? 'SENDER_ID' }}"
                                                    data-balance_url="{{ optional($item->smsApi)->balance_url ?? 'http://smpp.ajuratech.com/portal/sms/smsConfiguration/smsClientBalance.jsp?client=' }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Base URL<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control base_url" type="text" name="base_url" autocomplete="off"
                                           value="http://smpp.ajuratech.com:7788/sendtext" placeholder="Base Url" required/>
                                </div>
                            </div>





                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    API Key<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control api_key only-number" type="text" name="api_key"
                                           value="{{ old('api_key') }}" placeholder="Enter API Key" required />
                                </div>
                            </div>




                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Secret Key<sup class="text-danger">*</sup>
                                    :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control secret_key" type="text" name="secret_key" value="{{ old('secret_key') }}"
                                           placeholder="Type Secret Key" required />
                                </div>
                            </div>





                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Caller Id<sup class="text-danger">*</sup>
                                    :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control caller_id" type="text" name="caller_id" value="SENDER_ID"
                                           placeholder="Type Caller Id" required />
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Balance URL
                                    :</label>
                                <div class="col-md-5 col-sm-5">
                                    <textarea class="form-control balance_url" name="balance_url" rows="3"
                                              placeholder="Type Balance URL">http://smpp.ajuratech.com/portal/sms/smsConfiguration/smsClientBalance.jsp?client=
                                    </textarea>
                                </div>
                            </div>





                            <!-- Action -->
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4"></label>
                                <div class="col-md-3 col-sm-3">
                                    <button type="submit" class="btn btn-primary col-md-12">
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

        $('#dokan_id').on('change',function () {
            $('.api_key').val($(this).find(':selected').data('api_key'))
            $('.secret_key').val($(this).find(':selected').data('secret_key'))
            $('.balance_url').val($(this).find(':selected').data('balance_url'))
        })
    </script>


@endsection

