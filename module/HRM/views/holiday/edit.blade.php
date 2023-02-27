@extends('layouts.master')

@section('title', 'Edit Holiday')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Edit Holiday
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
                        @if (hasPermission('dokani.holidays.index', $slugs))
                            <a href="{{ route('dokani.holidays.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Holiday List
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <div class="row">
                            <div class="col-xs-12">

                                <!-- PAGE CONTENT BEGINS -->
                                <form class="form-horizontal" action="{{ route('dokani.holidays.update', $holiday->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right"> Holiday Title<sup>*</sup> </label>
                                            <div class="col-sm-9">
                                                <input name="title" type="text" value="{{ $holiday->title }}" placeholder="Holiday Title" class="form-control" required />
                                                @error('title')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Choose Date<sup>*</sup> </label>
                                            <div class="col-sm-9">
                                                <input name="date" type="text" value="{{ $holiday->date }}" placeholder="Choose Date" id="choose_date" class="form-control" required/>
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Status </label>
                                            <div class="col-sm-9">
                                                @php
                                                    $status = ['0' => 'Inactive', '1' => 'Active'];
                                                @endphp
                                                <select name="status" class="chosen-select form-control"
                                                        data-placeholder="-Select Status-">
                                                    <option value=""></option>
                                                    @foreach ($status as $key => $item)
                                                        <option value="{{ $key }}" {{ $holiday->status == $key ? 'selected' : '' }}>
                                                            {{ $item }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-md-6">
                                            <label class="control-label col-sm-3 no-padding-right"></label>
                                            <div class="col-sm-9">
                                                <button class="btn btn-info" type="submit">
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
                                        <div class="col-sm-3"></div>
                                    </div>

                                    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                                        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
                                    </a>
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


    <!-- inline scripts related to this page -->
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $('#choose_date').datepicker({
            format: "yyyy/mm/dd",
            autoclose: true,
            todayHighlight: true,
        })

    </script>

    <script src="{{ asset('assets/custom_js/file_upload.js') }}"></script>


@endsection


