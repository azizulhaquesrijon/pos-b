@extends('layouts.master')

@section('title', 'Edit Employee')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Edit Employee
@stop



@section('content')

    <style>
        .input-group-text {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 800;
            line-height: 1.5;
            color: #FFF;
            text-align: left;
            white-space: nowrap;
            background-color: #4F99C6 !important;
            border-color: #6FB3E0;
            border-radius: 0.25rem;
        }
    </style>

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">
                        @if (hasPermission('dokani.employees.index', $slugs))
                            <a href="{{ route('dokani.employees.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Employee List
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
                                <form class="form-horizontal" action="{{ route('dokani.employees.update',$employee->id) }}" method="POST" role="form" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right">Photo</label>
                                            <div class="col-sm-7">
                                                <input type="file" name="image" id="form-profile-picture" class="form-control" />
                                            </div>
                                            <div class="col-sm-2">
                                                <img src="{{ asset($employee->image) }}" alt="" width="260%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right"> Employee Name<sup>*</sup> </label>
                                            <div class="col-sm-9">
                                                <input name="name" type="text" value="{{ $employee->name }}" placeholder="Employee name" class="form-control" required />
                                                @error('name')
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
                                            <label class="col-sm-3 control-label no-padding-right"> Designation<sup>*</sup> </label>
                                            <div class="col-sm-9">
                                                <input name="designation" value="{{ $employee->designation }}" type="text" placeholder="Designation " class="form-control" required/>
                                                @error('designation')
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
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Employee Mobile<sup>*</sup> </label>
                                            <div class="col-sm-9">
                                                <div class="col-sm-1" style="align-items: center; padding:0px;">
                                                    <div class="input-group-prepend" style="display: inline-block;">
                                                        <div class="input-group-text form-control">+88</div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-11" style="padding-right: 0px;">
                                                    <input name="mobile" value="{{ $employee->mobile }}" type="number" placeholder="Mobile Number" class="form-control" required/>
                                                    @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right"> Address</label>
                                            <div class="col-sm-9">
                                                <input name="address" value="{{ $employee->address }}" type="text" placeholder="Enter Address" class="form-control" />
                                                @error('address')
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
                                            <label class="col-sm-3 control-label no-padding-right"> Father's Name</label>
                                            <div class="col-sm-9">
                                                <input name="father_name" value="{{ $employee->father_name }}" type="text" placeholder="Father's Name" class="form-control" />
                                                @error('father_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $father_name }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right">NID Picture</label>
                                            <div class="col-sm-7">
                                                <input type="file" name="nid_image" id="nid-picture" class="form-control" />
                                            </div>
                                            <div class="col-sm-2">
                                                <img src="{{ asset($employee->nid_image) }}" alt="" width="260%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Salary<sup>*</sup> </label>
                                            <div class="col-sm-9">
                                                <input name="salary" value="{{ $employee->salary }}" type="number" placeholder="Enter Salary" class="form-control" required/>
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Branch </label>
                                            <div class="col-sm-9">
                                                <select name="branch_id" class="form-control" id="branch_id">
                                                    <option value="">-Select-</option>
                                                    @foreach ($branches as $item)
                                                        <option value="{{ $item->id }}" {{ $employee->branch_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Joining Date<sup>*</sup> </label>
                                            <div class="col-sm-9">
                                                <input name="joining_date" type="text" value="{{ $employee->joining_date }}" placeholder="Joining Date" id="join_date" class="form-control" required/>
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
                                                        <option value="{{ $key }}" {{ $employee->status == $key ? 'selected' : '' }}>
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
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right" for="isUser">Add as User?</label>
                                            <div class="col-sm-1">
                                                <input name="isUser" type="checkbox" id="isUser" class="form-control" @if($employee->is_user == 1) checked @endif/>
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                    <div class="form-group" id="userPin" @if($employee->is_user == 0) style="display: none;" @endif>
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Pin</label>
                                            <div class="col-sm-9">
                                                <input name="pin" type="text" value="{{ optional($employee->user)->pin }}" placeholder="4 Digit Pin" id="pin" class="form-control" />
                                                <input name="user_id" type="hidden" value="{{ optional($employee->user)->id }}"/>
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
        $('#join_date').datepicker({
            format: "yyyy/mm/dd",
            autoclose: true,
            todayHighlight: true,
        })
        $("#isUser").on('change', function() {
            if ($(this).is(':checked')){

                $('#userPin').show()
            }
            else {
                $('#userPin').hide()
            }
        });


        $('#form-profile-picture').ace_file_input({
            style: 'well',
            btn_choose: 'Drop profile picture or choose profile',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small',
            preview_error: function(filename, error_code) {}

        }).on('change', function() {

        });

        $('#nid-picture').ace_file_input({
            style: 'well',
            btn_choose: 'Drop profile picture or choose profile',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small',
            preview_error: function(filename, error_code) {}

        }).on('change', function() {

        });
    </script>

    <script src="{{ asset('assets/custom_js/file_upload.js') }}"></script>


@endsection
