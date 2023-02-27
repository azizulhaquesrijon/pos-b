@extends('layouts.master')

@section('title', 'Change Pin')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Change Pin
@stop



@section('content')

    <div class="row" style="background-color: white">
        <div class="col-md-8 my-7" style="margin-left: 23rem !important;margin-right: 10rem !important;">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main" style="background-color: whitesmoke">



                        <form method="POST" action="{{ url('dokani/change-pin-update') }}" class="form-horizontal">
                        @csrf


                        <!-- Old pin -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Old Pin<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="password" name="old_pin" autocomplete="off"
                                           value="{{ old('old_pin') }}" placeholder="Type old pin" required />
                                </div>
                            </div>





                            <!-- New pin -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    New Pin<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="password" name="new_pin" autocomplete="off"
                                           value="{{ old('new_pin') }}" placeholder="Type new pin" required />
                                </div>
                            </div>




                            <!-- Confirm pin -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Confirm Pin<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="password" name="confirm_pin" autocomplete="off"
                                           value="{{ old('confirm_pin') }}" placeholder="Type confirm pin" required />
                                </div>
                            </div>



                            <!-- Action -->
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4"></label>
                                <div class="col-md-3 col-sm-3">
                                    <button type="submit" class="btn btn-primary col-md-12">
                                        <i class="fa fa-upload"></i> Update
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



@endsection
