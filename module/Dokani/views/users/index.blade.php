@extends('layouts.master')


@section('title', 'Users')

@section('page-header')
    <i class="fa fa-info-circle"></i> Users
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
                        <a href="{{ route('dokani.users.create') }}" class="">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Searching -->
                        <div class="row">
                            <div class="col-sm-12">
                                <form action="">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" name="name" value="{{ request('name') }}"
                                                        class="form-control input-sm" placeholder="Name">
                                                </td>

                                                <td>
                                                    @php
                                                        $status = ['0' => 'Inactive', '1' => 'Active'];
                                                    @endphp
                                                    <select name="status" class="chosen-select form-control"
                                                        data-placeholder="-Select Status-">
                                                        <option value=""></option>

                                                        @foreach ($status as $key => $item)
                                                            <option value="{{ $key }}">
                                                                {{ $item }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-corner">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fa fa-search"></i> Search
                                                        </button>
                                                        <a href="{{ request()->url() }}" class="btn btn-sm">
                                                            <i class="fa fa-refresh"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xs-12">

                                <div class="table-responsive" style="border: 1px #cdd9e8 solid;">

                                    <!-- Table -->
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th class="text-center">Designation</th>
                                                <th>Created At </th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($users as $key => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        {{-- {!! $item->image($item) !!} --}}
                                                        {{ $item->name }}
                                                    </td>
                                                    <td>{{ $item->email }}</td>
                                                    <td>{{ $item->mobile }}</td>
                                                    <td>{{ $item->designation }}</td>

                                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F d, Y h:i s A') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-corner">

                                                            <!-- edit -->
                                                            <a href="{{ route('permission-access.edit', $item->id) }}"
                                                                role="button" class="btn btn-sm btn-inverse"
                                                                title="Permission Assign">
                                                                <i class="fa fa-key"></i>
                                                            </a>
                                                            <!-- edit -->
                                                            <a href="{{ route('dokani.users.edit', $item->id) }}"
                                                                role="button" class="btn btn-sm btn-success" title="Edit">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a>

                                                            <!-- delete -->
                                                            <button type="button"
                                                                onclick="delete_item(`{{ route('dokani.users.destroy', $item->id) }}`)"
                                                                class="btn btn-sm btn-danger" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>

                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="30" class="text-center text-danger py-3"
                                                        style="background: #eaf4fa80 !important; font-size: 18px">
                                                        <strong>No records found!</strong>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    @include('partials._paginate',['data'=> $users])

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')
    <script>
        /***************/
        $('.show-details-btn').on('click', function(e) {
            e.preventDefault();
            $(this).closest('tr').next().toggleClass('open');
            $(this).find(ace.vars['.icon']).toggleClass('fa-eye').toggleClass('fa-eye-slash');
        });
        /***************/
    </script>

@stop
