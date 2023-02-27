@extends('layouts.master')

@section('title', 'Edit Users')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Edit Users
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
                        <a href="{{ route('dokani.users.index') }}" class="">
                            <i class="fa fa-list-alt"></i> Users List
                        </a>
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.users.update', $user->id) }}"
                            class="form-horizontal">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Name<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="text" name="name" autocomplete="off"
                                        value="{{ old('name', $user->name) }}" placeholder="Course Name" required />
                                </div>
                            </div>





                            <!-- Mobile -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    Mobile<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control only-number" type="text" name="mobile"
                                        value="{{ old('mobile', $user->mobile) }}" placeholder="Enter Mobile" required />
                                </div>
                            </div>




                            <!-- Designation -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Designation :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="text" name="designation"
                                        value="{{ old('designation', $user->designation) }}"
                                        placeholder="Type designation" />
                                </div>
                            </div>



                            <!-- PIN -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Pin<sup class="text-danger">*</sup>
                                    :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="password" name="pin"
                                        value="{{ old('pin', $user->pin) }}" placeholder="Type Pin" />
                                </div>
                            </div>






                            <!-- Image -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="sales_price">Image :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input type="file" name="image" style="width:100% !important" id="id-input-file-3"
                                        class="form-control" />
                                </div>
                            </div>









                            <!-- Action -->
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4"></label>
                                <div class="col-md-3 col-sm-3">
                                    <button type="submit" class="btn btn-primary col-md-12">
                                        <i class="fa fa-save"></i> Update
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
