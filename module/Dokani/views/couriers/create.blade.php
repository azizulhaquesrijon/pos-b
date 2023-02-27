@extends('layouts.master')

@section('title', 'Add Courier')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Add Courier
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
                        @if (hasPermission('dokani.couriers.index', $slugs))
                            <a href="{{ route('dokani.couriers.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Courier List
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.couriers.store') }}" class="form-horizontal"
                            enctype="multipart/form-data">
                            @csrf

                        <div class="row">
                                
                            <div class="col-md-8 col-md-offset-3">
                                    <!-- Name -->

                                <div class="col-md-8" style="margin-top: 15px">
                                    <div class="input-group">
                                        <span class="input-group-addon" style="padding-right: 97px;">
                                            Name<em class="text-danger">*</em>
                                        </span>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control input-sm" required>
                                        @error('name')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
    
    
    
    
    
                                <!-- Mobile -->
                                <div class="col-md-8" style="margin-top: 15px">
                                    <div class="input-group">
                                        <span class="input-group-addon" style="padding-right: 90px;">
                                            Mobile
                                        </span>
                                        <input type="number" name="mobile" value="{{ old('mobile') }}"
                                                class="form-control input-sm" required>
                                        @error('mobile')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
    
    
    
    
                                <!-- Address -->
                                <div class="col-md-8" style="margin-top: 15px; margin-bottom:15px">
                                    <div class="input-group">
                                        <span class="input-group-addon" style="padding-right: 81px;">
                                            Address
                                        </span>
                                        <textarea type="text" name="address"
                                                class="form-control input-sm">{{ old('address') }}</textarea>
                                        @error('address')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

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
