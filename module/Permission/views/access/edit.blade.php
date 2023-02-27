@extends('layouts.master')
@section('title', 'Edit User Permission')
@section('page-header')
    <i class="fa fa-plus-circle"></i> Edit User Permission
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.custom.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop


@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header" style="background:#DFE2CD">

                    <span class="widget-toolbar pull-left" style="font-size:20px !important; color:#41B883">
                        Edit User Permission
                    </span>
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('update.permission.access', $user->id) }}"
                            method="post" role="form">
                            @csrf
                            @method('put')

                <!-- Branch -->
                @if (hasBranching())
                <div class="panel-group" style="margin-top:40px" id="accordion" role="tablist"
                    aria-multiselectable="true">

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="branch_collapse">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion"
                                    href="#collapse_branches" aria-expanded="true"
                                    aria-controls="collapse_branch">
                                    <i class="short-full glyphicon glyphicon-plus"></i>
                                    <span style="line-height:12px; font-size:15px; font-weight:800; letter-spacing: 1.5px">
                                        Branch
                                    </span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_branches" class="panel-collapse collapse" role="tabpanel"
                            aria-labelledby="branch_collapse">
                            <div class="panel-body">
                                <div class="row order-type">
                                    <table class="table table-bordered table-sm mx-auto"
                                        style="width:98%; margin-left:auto; margin-right:auto; color:#41B883">
                                        <thead>
                                            <tr>
                                                <td colspan="5">
                                                    <label>
                                                        <input type="checkbox" class="ace parentCheckBox">
                                                        <span class="lbl" style="font-weight:800"> Select All </span>
                                                    </label>
                                                </td>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach (dokanBranchesAll()->chunk(5) as $branches)
                                                <tr>
                                                    @foreach ($branches as $item)
                                                    <td width="20%">
                                                        <label>
                                                            <input name="branches[]"
                                                                    value="{{ $item->id }}"
                                                                    {{ in_array($item->name, $hasBranches) ? 'checked' : '' }}
                                                                    type="checkbox"
                                                                    class="ace childCheckBox">
                                                            <span class="lbl"> {{ $item->name }} </span>
                                                        </label>
                                                    </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @endif

                            <!-- Menus All -->
                            <div class="access-control">
                                <ul style="list-style:none" class="list-group">
                                    @foreach ($modules as $index => $module)
                                        <li class="list-group-item">
                                            <label>
                                                <input type="checkbox" class="ace module-checkbox-control">
                                                <span class="lbl" style="font-size:16px !important">
                                                    {{ $module->name }} </span>
                                            </label>
                                            @foreach ($module->submodules as $submodule)
                                                <div class="panel-group" id="accordion" role="tablist"
                                                    aria-multiselectable="true">

                                                    <div class="panel panel-default">
                                                        <div class="panel-heading" role="tab"
                                                            id="heading{{ $submodule->id }}">
                                                            <h4 class="panel-title">
                                                                <a role="button" data-toggle="collapse"
                                                                    data-parent="#accordion"
                                                                    href="#collapse{{ $submodule->id }}"
                                                                    aria-expanded="true"
                                                                    aria-controls="collapse{{ $submodule->id }}">
                                                                    <i class="short-full glyphicon glyphicon-plus"></i>
                                                                    <span
                                                                        style="font-size:14px !important">{{ $submodule->name }}</span>
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapse{{ $submodule->id }}"
                                                            class="panel-collapse collapse" role="tabpanel"
                                                            aria-labelledby="heading{{ $submodule->id }}">
                                                            <div class="panel-body">
                                                                <div class="row order-type" style="margin-top:10px">
                                                                    <table class="table table-striped table-sm mx-auto"
                                                                        style="width:98%; margin-left:auto; margin-right:auto; color:#41B883">
                                                                        <thead>
                                                                            <tr style="border:none !important">
                                                                                <td colspan="9"
                                                                                    style="border:none !important">
                                                                                    <label>
                                                                                        <input type="checkbox"
                                                                                            class="ace parentCheckBox">
                                                                                        <span class="lbl"
                                                                                            style="font-weight:800"> Select
                                                                                            All </span>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>

                                                                            <tr>
                                                                                @foreach ($submodule->permissions as $permission)
                                                                                    <td style="border:none !important">
                                                                                        <label>
                                                                                            <input type="checkbox"
                                                                                                name="permissions[]"
                                                                                                value="{{ $permission->id }}"
                                                                                                {{ in_array($permission->slug, $isPermitted) ? 'checked' : '' }}
                                                                                                class="ace {{ $loop->first ? ' childCheckBox module_row' : 'childCheckBox rowChildCheckBox' }}">
                                                                                            <span class="lbl">
                                                                                                {{ $permission->name }}
                                                                                            </span>
                                                                                        </label>
                                                                                    </td>
                                                                                @endforeach
                                                                            </tr>

                                                                        </tbody>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach
                                        </li>
                                    @endforeach
                                </ul>



                                {{-- actions --}}
                                <div class="form-group pull-right" style="margin-top:14px">
                                    <button class="btn  btn-sm btn-success"> <i class="fa fa-save"></i> Update
                                    </button>
                                    <a href="{{ route('dokani.users.index') }}" class="btn btn-sm  btn-info"> <i
                                            class="fa fa-list"></i>
                                        List</a>
                                </div>

                            </div>


                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>


    <input type="hidden" id="csrf" value="{{ csrf_token() }}">

@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.custom.min.js') }}"></script>
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>


    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace.min.js') }}"></script>


    {{-- dynamically control checkbox --}}
    <script type="text/javascript">
        // when clicked on Check All
        $(".parentCheckBox").click(function() {

            $(this).closest('table').find('tbody tr input').prop('checked', this.checked)

        });

        $('.module-checkbox-control').click(function() {
            if ($(this).is(':checked')) {
                $(this).closest('li').find('.parentCheckBox').prop("checked", true);
                $(this).closest('li').find('.rowChildCheckBox').prop("checked", true);
                $(this).closest('li').find('.childCheckBox').prop("checked", true);
            } else {
                $(this).closest('li').find('.parentCheckBox').prop("checked", false);
                $(this).closest('li').find('.rowChildCheckBox').prop("checked", false);
                $(this).closest('li').find('.childCheckBox').prop("checked", false);
            }
        });
    </script>

    <!--  Select Box Search-->
    <script type="text/javascript">
        jQuery(function($) {

            if (!ace.vars['touch']) {
                $('.chosen-select').chosen({
                    allow_single_deselect: true
                });
            }

        })
    </script>



    <script>
        function toggleIcon(e) {
            $(e.target)
                .prev('.panel-heading')
                .find(".short-full")
                .toggleClass('glyphicon-plus glyphicon-minus');
        }
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);
    </script>
@stop
