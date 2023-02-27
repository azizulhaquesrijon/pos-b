@extends('layouts.master')

@section('title', 'Add Shop')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Add Shop
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
                        <a href="{{ route('dokani.shop.index') }}" class="">
                            <i class="fa fa-list-alt"></i> Shop List
                        </a>
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.shop.store') }}" class="form-horizontal">
                        @csrf


                        <!-- Name -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Name<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="text" name="name" autocomplete="off"
                                           value="{{ old('name') }}" placeholder="Name" required />
                                </div>
                            </div>





                            <!-- Mobile -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    Mobile<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control only-number" type="text" name="mobile"
                                           value="{{ old('mobile') }}" placeholder="Enter Mobile" required />
                                </div>
                            </div>


                            <!-- PIN -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Pin<sup class="text-danger">*</sup>
                                    :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="password" name="pin" value="{{ old('pin') }}"
                                           placeholder="Type Pin" required />
                                </div>
                            </div>



                            




                            <!-- MULTIPLE BRANCH -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Allow Multi Branch :</label>
                                <div class="col-md-5 col-sm-5">
                                    <label>
                                        <input name="is_multiple_branch" value="1" class="ace ace-switch ace-switch-6" type="checkbox">
                                        <span class="lbl"></span>
                                    </label>
                                </div>
                            </div>


                            <div class="" style="background-color: #7f9fe7;
    color: white;
    font-size: 18px;
    margin: 24px;
    padding-left: 17px;"><i class="fa fa-info-circle"></i>&nbsp;<strong>Package</strong></div>

                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Start Date<sup class="text-danger">*</sup>
                                    :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input type="text" name="start_date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                           class="form-control date-picker" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">End Date<sup class="text-danger">*</sup>
                                    :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input type="text" name="end_date" value=""
                                           class="form-control date-picker" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Package Type<sup class="text-danger">*</sup>
                                    :</label>
                                <div class="col-md-5 col-sm-5">
                                    <select name="package_type" class="form-control" id="" style="width: 100%">
                                        @foreach($packages as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
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


@endsection

