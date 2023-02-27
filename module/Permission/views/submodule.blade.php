@extends('layouts.master')
@section('title',' Manage Sub Module')
@section('page-header')
    <i class="fa fa-list"></i>  Manage Sub Module
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop


@section('content')

    <div class="page-header">

         <div class="row">

            <div class="col-sm-10 col-sm-offset-1">
                <div class="widget-box">
                    <div class="widget-header">
                        <h5 style="font-weight: 600"><i class="fa fa-gear"></i> Manage Sub Module </h5>
                    </div>

                   

                    <div class="widget-body">
                        <div class="widget-main">
                            <form class="form-horizontal" action="{{ isset($submodule) ? route('submodules.update', $submodule->id) : route('submodules.store') }}" method="post">
                            @csrf
                                @if (isset($submodule))
                                    @method('PUT')
                                @endif
                                @include('partials._alert_message')

                                

                                <div class="row">

                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Module </label>
                                        <div class="col-xs-12 col-sm-8 @error('module_id') has-error @enderror">
                                            <select name="module_id" id="form-field-select-3" data-placeholder="Select" class="form-control chosen-select">
                                                @if (isset($submodule))
                                                    @foreach($modules as $id => $module)
                                                    <option value="{{ $id }}" {{ $id == $submodule->module_id ? 'selected' : '' }}>{{ $module }}</option>
                                                    @endforeach
                                                @else 
                                                        <option value="">Select</option>
                                                    @foreach($modules as $id => $module)
                                                        <option value="{{ $id }}" {{ $id == old('module_id') ? 'selected' : '' }}>{{ $module }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            @error('module_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Sub Module Name </label>
                                        <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                            <input type="text" class="form-control" name="name"
                                                value="{{ isset($submodule) ? $submodule->name : old('name')  }}" placeholder="Sub Module Name">

                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>





                                    <div class="form-group">
                                        <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
                                        <div class="col-xs-12 col-sm-6">
                                            <button type="submit" class="btn btn-success btn-sm"> <i class="fa fa-save"></i> {{ isset($submodule) ? 'Update' : 'Save'}}</button>
                                            <button class="btn btn-gray btn-sm" type="Reset"> <i class="fa fa-refresh"></i> Reset </button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">

            <div style="border: 1px #cdd9e8 solid;">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Module</th>
                            <th>Sub Module Name</th>
                            <th>Slug</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                         @foreach ($submodules as $key => $setting)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $setting->module->name }}</td>
                                <td>{{ $setting->name }}</td>
                                <td>{{ $setting->slug }}</td>
                                
                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        <a href="{{ route('submodules.edit',$setting->id) }}" class="btn btn-xs btn-success" title="Edit">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <button type="button" onclick="delete_check({{ $setting->id }})" class="btn btn-xs btn-danger" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>

                                    <form action="{{ route('submodules.destroy',$setting->id)}}" id="deleteCheck_{{ $setting->id }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                    </form>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @include('partials._paginate', ['data' => $submodules])
            </div>

        </div>
    </div>


@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

    



    <!--  Select Box Search-->
    <script type="text/javascript">
        jQuery(function($){
            if(!ace.vars['touch']) {
                $('.chosen-select').chosen({allow_single_deselect:true});
            }
        });

        function delete_check(id)
        {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width:400,
            }).then((result) =>{
                if(result.value){
                    $('#deleteCheck_'+id).submit();
                }
            })

        }

        jQuery(function ($) {
            $('#dynamic-table').DataTable({
                "ordering": false,
                "bPaginate": false,
                "lengthChange": false,
                "info": false
            });
        })
    </script>

   
@stop