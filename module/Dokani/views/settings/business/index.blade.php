@extends('layouts.master')


@section('title', 'Profile')

@section('page-header')
    <i class="fa fa-info-circle"></i> Profile
@stop


@section('css')
    <style>
        label {
            text-align: start !important;
        }

        form {
            padding: 10px;
            padding-top: 10px;

            background-image: '-o-linear-gradient(top,#FFF 0,#EEE 100%)';
            background-image: linear-gradient(to bottom, #FFF 0, #EEE 100%);
        }

        .fb-profile-block {
            margin: auto;
            position: relative;
            width: 100%;

        }

        .cover-container {
            background: #008780;
            background-image: url('{{ asset(optional($profile->businessProfile)->cover_image) }}');
            border-radius: 20px;
        }

        .fb-profile-block-thumb {
            display: block;
            height: 315px;
            overflow: hidden;
            position: relative;
            text-decoration: none;
            background-repeat: no-repeat;
            background-attachment: relative;
            background-size: cover;
            background-position: center;
        }

        .profile-img img {
            bottom: 15px;
            box-shadow: none;
            display: block;
            left: 15px;
            padding: 1px;
            position: absolute;
            height: 160px;
            width: 160px;
            background: rgba(0, 0, 0, 0.3) none repeat scroll 0 0;
            z-index: 9;
            border-radius: 50%;
        }

        .profile-name {
            bottom: 60px;
            left: 200px;
            position: absolute;
        }

    </style>


@endsection



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

                                <form class="form-horizontal" method="post"
                                    action="{{ route('dokani.business-profile.update', auth()->id()) }}"
                                    enctype="multipart/form-data">

                                    @csrf

                                    @method('PUT')



                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="fb-profile-block">
                                                    <div style="float: right;bottom:0;" id="edit_cover_photo">
                                                    </div>
                                                    <div class="fb-profile-block-thumb cover-container">

                                                    </div>
                                                    <div class="profile-img">

                                                        <img src="{{ asset($profile->image) }}" alt="" title="profile">

                                                    </div>
                                                    <div class="profile-name">
                                                        <h2
                                                            style="background-color: white;border-radius: 25px;color: black;padding: 10px;">
                                                            {{ optional($profile->businessProfile)->shop_name }}
                                                        </h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>






                                    <div style="padding-bottom: 20px"></div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Full
                                            Name</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input name="name" type="text" id="form-field-1"
                                                    value="{{ auth()->user()->name }}" placeholder="Your Full Name"
                                                    class="form-control"/>
                                                <i class="ace-icon fa fa-user"></i>
                                            </span>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Shop
                                            Name(English)</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" id="form-field-1" name="shop_name"
                                                    value="{{ old('shop_name', optional($profile->businessProfile)->shop_name) }}"
                                                    placeholder="Your Shop Name" class="form-control"/>
                                                <i class="ace-icon fa fa-globe"></i>
                                            </span>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Shop
                                            Name(Bangla)</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" placeholder="স্মার্ট দোকানি" name="shop_name_bn"
                                                    value="{{ old('shop_name_bn', optional($profile->businessProfile)->shop_name_bn) }}"
                                                    class="form-control"/>
                                                <i class="ace-icon fa fa-globe"></i>
                                            </span>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Shop
                                            Address</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input name="shop_address" type="text" class="form-control"
                                                    value="{{ old('shop_address', optional($profile->businessProfile)->shop_address) }}"
                                                    placeholder="ex: 152, 2/N Panthapath, Dhaka 1209"/>
                                                <i class="ace-icon fa fa-info"></i>
                                            </span>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right"> Mobile
                                            Number</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" class="form-control" name="business_mobile"
                                                    value="{{ old('business_mobile', optional($profile->businessProfile)->business_mobile) }}"
                                                    placeholder="Enter mobile number"/>
                                                <i class="ace-icon fa fa-mobile"></i>
                                            </span>
                                        </div>
                                    </div>




                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Shop
                                            Type</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <select name="shop_type_id" class="form-control chosen-select">
                                                <option></option>
                                                @foreach ($shop_types as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $id == optional($profile->businessProfile)->shop_type_id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Invoice
                                            Type</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <select name="invoice_type" class="form-control chosen-select">
                                                <option {{ old('invoice_type') }} value="0">Normal Invoice</option>
                                                <option {{ old('invoice_type', optional($profile->businessProfile)->invoice_type == '1' ? 'selected' : '' )}} value="1">POS Invoice</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Email</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input name="business_email" type="email"
                                                    value="{{ old('business_email', optional($profile->businessProfile)->business_email) }}"
                                                    placeholder="ahsan@smartsoftltd.com" class="form-control" />
                                                <i class="ace-icon fa fa-envelope"></i>
                                            </span>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">NID
                                            No.</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" placeholder="NID Number" min="10" max="19" name="nid_no"
                                                    value="{{ old('nid_no', optional($profile->businessProfile)->nid_no) }}"
                                                    class="form-control" />
                                                <i class="ace-icon fa fa-info"></i>
                                            </span>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Trade
                                            License No.</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" placeholder="Trade No" name="trade_license"
                                                    value="{{ old('trade_license', optional($profile->businessProfile)->trade_license) }}"
                                                    class="form-control" />
                                                <i class="ace-icon fa fa-info"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Product Expiry Date</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <label>
													<input type="radio" name="has_expiry_date" value="0" class="ace"
                                                    {{ optional($profile->businessProfile)->has_expiry_date == 0 ? 'checked' : '' }}>
                                                    <span class="lbl"> Yes</span>
												</label>

                                                <label>
													<input type="radio" name="has_expiry_date" value="1" class="ace"
                                                    {{ optional($profile->businessProfile)->has_expiry_date == 1 ? 'checked' : '' }}>
                                                    <span class="lbl"> No</span>
												</label>

                                            </span>
                                        </div>
                                    </div>




                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Show Category To Invoice</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <label>
													<input type="radio" name="is_category_show" value="1" class="ace"
                                                    {{ optional($profile->businessProfile)->is_category_show == 1 ? 'checked' : '' }}>
                                                    <span class="lbl"> Yes</span>
												</label>

                                                <label>
													<input type="radio" name="is_category_show" value="0" class="ace"
                                                    {{ optional($profile->businessProfile)->is_category_show == 0 ? 'checked' : '' }}>
                                                    <span class="lbl"> No</span>
												</label>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Default Vat </label>
                                        <div class="col-xs-4 col-sm-2">
                                            <input type="number" step="0.01" name="vat" value="{{ old('vat',optional($profile->businessProfile)->vat) }}" class="form-control" placeholder="Ex: 5" /> %
                                            <small>Vat in Percentage</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Sale Due(No Due Mandatory)</label>
                                        <div class="col-xs-4 col-sm-2 custom-control custom-switch">
                                            <span class="block input-icon input-icon-right">
                                                <label>
													<input type="radio" name="is_mandatory_no_due" value="1" class="ace"
                                                    {{ optional($profile->businessProfile)->is_mandatory_no_due == 1 ? 'checked' : '' }}>
                                                    <span class="lbl"> Yes</span>
												</label>
                                                <label>
													<input type="radio" name="is_mandatory_no_due" value="0" class="ace"
                                                    {{ optional($profile->businessProfile)->is_mandatory_no_due == 0 ? 'checked' : '' }}>
                                                    <span class="lbl"> No</span>
												</label>
                                            </span>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">
                                            Profile Picture </label>
                                        <div class="col-xs-8 col-sm-5">
                                            <input type="file" name="image" class="form-control ace-file-upload" />

                                        </div>
                                    </div>




                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right"> Cover
                                            Picture </label>
                                        <div class="col-xs-8 col-sm-5">
                                            <label class="ace-file-input ace-file-multiple">
                                                <input type="file" class="ace-file-upload" name="cover_image">
                                            </label>
                                        </div>

                                    </div>


                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">NID
                                            Front Side </label>
                                        <div class="col-xs-8 col-sm-5">
                                            <label class="ace-file-input ace-file-multiple">
                                                <input type="file" class="ace-file-upload" name="nid_front_image">


                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            @if (file_exists(optional($profile->businessProfile)->nid_front_image))
                                                <img src="{{ asset(optional($profile->businessProfile)->nid_front_image) }}"
                                                    alt="" width="100" height="100">
                                            @endif

                                        </div>

                                    </div>



                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right"> NID
                                            Back Side </label>
                                        <div class="col-xs-8 col-sm-5">
                                            <label class="ace-file-input ace-file-multiple">
                                                <input type="file" class="ace-file-upload" name="nid_back_image">


                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            @if (file_exists(optional($profile->businessProfile)->nid_back_image))
                                                <img src="{{ asset(optional($profile->businessProfile)->nid_back_image) }}"
                                                    alt="" width="100" height="100">
                                            @endif

                                        </div>

                                    </div>



                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right"> Trade
                                            Lisence </label>
                                        <div class="col-xs-8 col-sm-5">
                                            <label class="ace-file-input ace-file-multiple">
                                                <input type="file" class="ace-file-upload" name="trade_license_image" />

                                            </label>
                                        </div>
                                        <div class="col-sm-4">
                                            @if (file_exists(optional($profile->businessProfile)->trade_license_image))
                                                <img src="{{ asset(optional($profile->businessProfile)->trade_license_image) }}"
                                                    alt="" width="100" height="100">
                                            @endif
                                        </div>

                                    </div>



                                    <hr>
                                    <h4>Bank Details</h4>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Bank Name</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" id="form-field-1" placeholder="Bank name"
                                                    name="bank_name"
                                                    value="{{ old('bank_name', optional($profile->bankAccount)->bank_name) }}"
                                                    class="form-control" />
                                                <i class="ace-icon fa fa-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Bank Account
                                            Type</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input name="account_type" type="text" placeholder="Bank Account Type"
                                                    value="{{ old('account_type', optional($profile->bankAccount)->account_type) }}"
                                                    class="form-control" />
                                                <i class="ace-icon fa fa-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Bank Account
                                            No</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" name="account_no" class="form-control"
                                                    placeholder="Bank Account No"
                                                    value="{{ old('account_no', optional($profile->bankAccount)->account_no) }}" />
                                                <i class="ace-icon fa fa-info"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-12 col-sm-3 control-label no-padding-right">Branch
                                            Name</label>
                                        <div class="col-xs-12 col-sm-5">
                                            <span class="block input-icon input-icon-right">
                                                <input name="branch_name" type="text" id="form-field-1"
                                                    value="{{ old('branch_name', optional($profile->bankAccount)->branch_name) }}"
                                                    placeholder="Branch Name" class="form-control" />
                                                <i class="ace-icon fa fa-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-sm-offset-4">
                                            <button class=" btn btn-info" type="submit">
                                                <i class="ace-icon fa fa-save bigger-110"></i>
                                                Update
                                            </button>

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

    <script src="{{ asset('assets/custom_js/file_upload.js') }}"></script>

    <script>
        /***************/
        $('.show-details-btn').on('click', function(e) {
            e.preventDefault();
            $(this).closest('tr').next().toggleClass('open');
            $(this).find(ace.vars['.icon']).toggleClass('fa-eye').toggleClass('fa-eye-slash');
        });
        /***************/
    </script>

@stop
