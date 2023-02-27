@extends('layouts.master')

@section('title', 'Add Refer')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Add Refer
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
                        @if (hasPermission('dokani.refers.index', $slugs))
                            <a href="{{ route('dokani.refers.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Refer Configure List
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.refers.store') }}" class="form-horizontal"
                              enctype="multipart/form-data">
                        @csrf


                        <!-- Start Price -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Start Price<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="number" name="start_price" autocomplete="off"
                                           value="{{ old('start_price') }}" placeholder="Type Price" required />
                                </div>
                            </div>





                            <!-- End Price -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    End Price<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="number" name="end_price"
                                           value="{{ old('end_price') }}" placeholder="Enter Price" />
                                </div>
                            </div>




                            <!-- Point -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Refer Point(%)<sup class="text-danger">*</sup> :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="number" name="refer_point" value="{{ old('refer_point') }}"
                                           placeholder="Type Point" required/>
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


