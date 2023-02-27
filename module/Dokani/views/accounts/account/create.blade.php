@extends('layouts.master')

@section('title', 'Add Account')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Add Account
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
                        @if (hasPermission('dokani.ac.accounts.index', $slugs))
                            <a href="{{ route('dokani.ac.accounts.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Account List
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.ac.accounts.index') }}" class="form-horizontal"
                              enctype="multipart/form-data">
                        @csrf


                        <!-- Name -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Name<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="text" name="name" autocomplete="off"
                                           value="{{ old('name') }}" placeholder="Type Account Name" required />
                                </div>
                            </div>





                            <!-- Opening Balance -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    Opening Balance :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control only-number" type="text" name="opening_balance"
                                           value="0" placeholder="Enter Opening Balance"/>
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

