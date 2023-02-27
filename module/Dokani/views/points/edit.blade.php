@extends('layouts.master')

@section('title', 'Edit Point')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Edit Point
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
                        @if (hasPermission('dokani.points.index', $slugs))
                            <a href="{{ route('dokani.points.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Point Configure List
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.points.update', $point->id) }}" class="form-horizontal"
                              enctype="multipart/form-data">
                        @csrf
                            @method('PUT')


                        <!-- Start Price -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Start Price<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="number" name="start_price" autocomplete="off"
                                           value="{{ $point->start_price }}" placeholder="Type Price" required />
                                </div>
                            </div>





                            <!-- End Price -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    End Price<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="number" name="end_price"
                                           value="{{ $point->end_price }}" placeholder="Enter Price" />
                                </div>
                            </div>




                            <!-- Point -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Point<sup class="text-danger">*</sup> :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="number" name="point" value="{{ $point->point }}"
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


