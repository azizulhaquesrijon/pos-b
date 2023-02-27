@extends('layouts.master')


@section('title', 'All Dokan')

@section('page-header')
    <i class="fa fa-info-circle"></i> All Dokan </span>
@stop


@section('css')

    <style>
        .package_edit{

            display: block;
            background: #eaffff;
            padding: 2px;
            border-radius: 6px;
            font-size: 16px;
            border: 2px solid #d7d7d7;
            color: #125ca7;
            font-weight: bold;
        }
    </style>

@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">
                        <a href="{{ route('dokani.shop.sms.manage') }}" class="">
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
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="name" value="{{ request('name') }}"
                                                       class="form-control input-sm" placeholder="Name">
                                            </td>

                                            <td>
                                                <input type="number" name="mobile" value="{{ request('mobile') }}"
                                                       class="form-control input-sm" placeholder="Mobile">
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
                                            <th>Dokan Name</th>
                                            <th>Base URL</th>
                                            <th>API Key</th>
                                            <th>Secret Key</th>
                                            <th>Caller Id</th>
                                            <th>Balance URL</th>
                                            <th class="text-center" width="10%">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($sms_apis as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ optional($item->user)->name }}</td>
                                                <td>{{ $item->base_url }}</td>
                                                <td>{{ $item->api_key }}</td>
                                                <td>{{ $item->secret_key }}</td>
                                                <td>{{ $item->caller_id }}</td>
                                                <td class="text-center">{{ $item->balance_url }}</td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-corner">

                                                        <!-- show -->
                                                        <a href="{{ route('dokani.shop.sms.edit', $item->id) }}"
                                                           role="button" class="btn btn-xs btn-primary" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>

                                                        <!-- delete -->
                                                        <button type="button"
                                                                onclick="delete_item(`{{ route('dokani.shop.sms.delete', $item->id) }}`)"
                                                                class="btn btn-xs btn-danger" title="Delete">
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

                                    @include('partials._paginate', ['data' => $sms_apis])

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


