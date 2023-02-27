@extends('layouts.master')


@section('title', 'Collection')

@section('page-header')
    <i class="fa fa-info-circle"></i> Collection
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
                        @if (hasPermission('dokani.collections.create', $slugs))
                            <a href="{{ route('dokani.collections.create') }}" class="">
                                <i class="fa fa-plus"></i> Add New
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Searching -->
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <form action="">
                                    <div class="row py-2">
                                        <div class="col-md-8">
                                            <input type="text" name="name" value="{{ request('name') }}"
                                                   class="form-control input-sm" placeholder="Customer Name">
                                        </div>

                                        <div class="col-md-4">
                                            <div class="btn-group btn-corner">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                                <a href="{{ request()->url() }}" class="btn btn-sm">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>

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
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th class="text-right">Amount</th>
                                                <th>Description</th>
                                                <th>Created At </th>
                                                <th>Created By </th>
                                                <th>Updated By </th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($collections as $key => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->date }}</td>
                                                    <td>
                                                        {{ optional($item->customer)->name }}
                                                    </td>

                                                    <td class="text-right">
                                                        {{ number_format($item->paid_amount, 2) }}</td>
                                                    <td>{{ $item->description }}
                                                    </td>


                                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}
                                                    </td>

                                                    <td>{{ optional($item->created_user)->name }}</td>
                                                    <td>{{ optional($item->updated_user)->name }}</td>

                                                    <td class="text-center">
                                                        <div class="btn-group btn-corner">

                                                            <!-- show -->
                                                            <a href="{{ route('dokani.collections.show', $item->id) }}"
                                                                role="button" class="btn btn-sm btn-primary" title="Edit">
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            <!-- edit -->
{{--                                                            <a href="{{ route('dokani.collections.edit', $item->id) }}"--}}
{{--                                                                role="button" class="btn btn-sm btn-success" title="Edit">--}}
{{--                                                                <i class="fa fa-pencil-square-o"></i>--}}
{{--                                                            </a>--}}

                                                            <!-- delete -->
                                                            @if (hasPermission('dokani.collections.delete', $slugs))
                                                            <button type="button"
                                                                onclick="delete_item(`{{ route('dokani.collections.destroy', $item->id) }}`)"
                                                                class="btn btn-sm btn-danger" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            @endIf
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

                                    @include('partials._paginate',['data'=> $collections])

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
