@extends('layouts.master')


@section('title', 'Holiday')

@section('page-header')
    <i class="fa fa-info-circle"></i> Holiday
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
                        @if (hasPermission('dokani.holidays.create', $slugs))
                            <a href="{{ route('dokani.holidays.create') }}" class="">
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
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="title" value="{{ request('title') }}"
                                                       class="form-control input-sm" placeholder="Title">
                                            </td>

                                            <td>
                                                <input type="text" name="date" value="{{ request('date') ?? Carbon\Carbon::now()->format('Y/m/d') }}"
                                                       class="form-control input-sm" id="choose_date" placeholder="Choose Date">
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
                                            <th>Holiday Title</th>
                                            <th>Date</th>
                                            <th>Status </th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($holidays as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ $item->date }}</td>

                                                <td style="text-align: center;">
                                                    <span class="label {{ $item->status == 1 ? 'label-success' : 'label-danger' }} arrowed-in arrowed-in-right">
                                                        {{ $item->status == 1 ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-corner">

                                                        <!-- edit -->
                                                        @if (hasPermission('dokani.holidays.edit', $slugs))
                                                            <a href="{{ route('dokani.holidays.edit', $item->id) }}"
                                                               role="button" class="btn btn-sm btn-success" title="Edit">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a>
                                                        @endif

                                                    <!-- delete -->
                                                        @if (hasPermission('dokani.holidays.delete', $slugs))
                                                            <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.holidays.destroy', $item->id) }}`)"
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

                                    @include('partials._paginate',['data'=> $holidays])

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
        $('#choose_date').datepicker({
            format: "yyyy/mm/dd",
            autoclose: true,
            todayHighlight: true,
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

