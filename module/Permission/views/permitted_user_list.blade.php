@extends('layouts.master')
@section('title', 'Permitted User List')
@section('page-header')
    <i class="fa fa-list"></i> User List
@stop
@section('css')

@stop


@section('content')

    <div class="page-header">

        <h1>
            @yield('page-header')
        </h1>
    </div>

    @include('partials._alert_message')


    <!-- Searching -->
    {{-- <div class="row">
        <form>
            <div class="col-sm-8 col-sm-offset-1">
                <table class="table table-bordered">
                    <tr>
                        <td><input type="text" name="employee_id" value="{{ request('employee_id') }}"
                                class="form-control" placeholder="Employee Id"></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-info btn-xs">
                                    <i class="fa fa-search"></i> Search
                                </button>
                                <a href="{{ request()->url() }}" class="btn btn-default btn-xs">
                                    <i class="fa fa-refresh"></i> Refresh
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div> --}}


    <div class="row">
        <div class="col-md-12" style="margin-left:auto !important; margin-right:auto !important">

            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <table id="data-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Employee Id</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Designation</th>
                            @if (hasPermission('permission.accesses.edit', $slugs) || hasPermission('permission.accesses.delete', $slugs) || hasPermission('change.employees.password', $slugs))
                                <th style="width: 12%">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->employee->employee_full_id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->employee->getCompanyName() }}</td>
                                <td>{{ $user->employee->getDepartmentName() }}</td>
                                <td>{{ $user->employee->getDesignationName() }}</td>

                                @if (hasPermission('permission.accesses.edit', $slugs) || hasPermission('permission.accesses.delete', $slugs) || hasPermission('change.employees.password', $slugs))
                                    <td class="text-center" style="width: 120px">
                                        <div class="btn-group btn-corner">


                                            @if (hasPermission('change.employees.password', $slugs) && $user->credential)
                                                <span class="btn btn-inverse btn-xs popover-inverse" data-rel="popover"
                                                    data-placement="top" data-trigger="click"
                                                    data-original-title="<i class='ace-icon fa fa-info-circle green'></i> User Password"
                                                    data-content="<p>Password: {{ optional($user->credential)->secrete }}</p>">
                                                    <i class="fa fa-key"></i>
                                                </span>
                                            @endif

                                            @if (hasPermission('change.employees.password', $slugs))
                                                <a href="{{ route('admin.edit.password', $user->id) }}"
                                                    class="btn btn-xs btn-info pull-center" title="Change Password">
                                                    <i class="fa fa-lock"></i>
                                                </a>
                                            @endif

                                            @if ($user->status == 2)
                                                <a href="{{ route('user.active.deactive', [$user->id, 1]) }}"
                                                    class="btn btn-xs btn-warning pull-center" title="Active">
                                                    <i class="fa fa-thumbs-o-up"></i>
                                                </a>
                                            @endif

                                            @if ($user->status == 1)
                                                <a href="{{ route('user.active.deactive', [$user->id, 2]) }}"
                                                    class="btn btn-xs btn-primary pull-center" title="De-active">
                                                    <i class="fa fa-thumbs-o-down"></i>
                                                </a>
                                            @endif

                                            @if (hasPermission('permission.accesses.edit', $slugs))
                                                <a href="{{ route('edit.permitted.users', $user->id) }}"
                                                    class="btn btn-xs btn-success pull-center" title="Edit">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            @endif

                                            @if (hasPermission('permission.accesses.delete', $slugs))
                                                <button type="button" onclick="delete_check({{ $user->id }})"
                                                    class="btn btn-xs btn-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>


                                        <form action="{{ route('permitted.user.delete', $user->id) }}"
                                            id="deleteCheck_{{ $user->id }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- @include('partials._paginate',['data'=>$users]) --}}
            </div>

        </div>
    </div>


@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>


    <script src="{{ asset('assets/custom_js/custom-datatable.js') }}"></script>



    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        function delete_check(id) {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width: 400,
            }).then((result) => {
                if (result.value) {
                    $('#deleteCheck_' + id).submit();
                }
            })
        }
    </script>
@stop
