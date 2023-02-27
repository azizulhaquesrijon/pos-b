@extends('layouts.master')


@section('title', 'Salary')

@section('page-header')
    <i class="fa fa-info-circle"></i> Salary
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
                        @if (hasPermission('dokani.salaries.create', $slugs))
                            <a href="{{ route('dokani.salaries.create') }}" class="">
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
                            <div class="col-sm-12">
                                <form action="">
                                    <div class="row" style="margin:25px;">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-2">

                                        </div>

                                        <div class="col-md-3">
                                            <!-- <label>Product Name</label> -->
                                            <div class="input-group-append">
                                                <input type="text" value="{{ Carbon\Carbon::now()->format('Y/m') }}"
                                                       class="form-control filter" name="date" id="date"
                                                       placeholder="Choose Date..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div style="margin-right:10px;">
                                                <button type="submit" class="btn btn-success btn-sm"><i class="ace-icon fa fa-check"></i>Check</button>
                                                <a href="{{ request()->url() }}" class="btn btn-warning btn-sm"><i
                                                        class="ace-icon fa fa-undo"></i>Reset</a>
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
                                            <th>Year</th>
                                            <th>Month </th>
                                            <th>Net Salary </th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($salaries as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->date.'/01')->format('Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->date.'/01')->format('F') }}</td>
                                                <td>{{ $item->total_payable_salary }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-corner">

                                                        <!-- edit -->
                                                        @if (hasPermission('dokani.salaries.show', $slugs))
                                                            <a href="{{ route('dokani.salaries.show', $item->id) }}"
                                                               role="button" class="btn btn-sm btn-primary" title="Show">
                                                                <i class="fa fa-print"></i>
                                                            </a>
                                                        @endif

{{--                                                    <!-- Print -->--}}
{{--                                                        @if (hasPermission('dokani.salaries.prints', $slugs))--}}
{{--                                                            <a href="{{ route('dokani.salaries.prints',$item->id) }}"--}}
{{--                                                               role="button" class="btn btn-sm btn-success" title="Show">--}}
{{--                                                                <i class="fa fa-print"></i>--}}
{{--                                                            </a>--}}
{{--                                                        @endif--}}

                                                    <!-- delete -->
                                                        @if (hasPermission('dokani.salaries.delete', $slugs))
                                                            <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.salaries.destroy', $item->id) }}`)"
                                                                    class="btn btn-sm btn-danger" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @endif
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

                                    @include('partials._paginate',['data'=> $salaries])

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

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $('#date').datepicker({
            format: "yyyy/mm",
            autoclose: true,
            todayHighlight: true,
            viewMode: "months",
            minViewMode: "months"
        })

    </script>
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


