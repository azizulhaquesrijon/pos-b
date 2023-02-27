@extends('layouts.master')


@section('title', 'Payable Due Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Payable Due Report
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Searching -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-offset-3">
                                <form action="">
                                    <div class="row py-2">
                                        <div class="col-md-4">
                                            <select name="id" class="select2 form-control"
                                                    data-placeholder="-Select Supplier-" required>
                                                <option value=""></option>
                                                <option value="all" {{ request('id') == 'all' ? 'selected' : '' }}>All</option>

                                                @foreach ($suppliers as $key => $item)
                                                    <option value="{{ $key }}" {{ request('id') == $key ? 'selected' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="btn-group btn-corner" style="">
                                                <button type="submit" class="btn btn-xs btn-success">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                                <a href="{{ request()->url() }}" class="btn btn-xs">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
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
                                                <th>Supplier</th>
                                                <th>Mobile</th>
                                                <th class="text-right">Due</th>
                                                <th class="text-right">Advance</th>
                                            </tr>
                                        </thead>

                                        <tbody>


                                            @forelse ($reports as $key => $item)

                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->mobile }}</td>
                                                    <td class="text-right">{{ $item->balance }}</td>
                                                    <td class="text-right">{{ $item->previous_due }}</td>
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
                                        <tfoot>
                                        <tr>
                                            <th colspan="20">
                                                <a  href="{{ route('dokani.reports.payable-due') }}?export_type=payable-due-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                    title="Excel" style="padding-left: 10px" target="_blank">
                                                    <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                                </a>&nbsp;&nbsp;&nbsp;
                                                <a href="{{ route('dokani.reports.payable-due') }}?export_type=payable-due-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                   title="Pdf" target="_blank">
                                                    <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}" alt="">
                                                </a>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>

                                    @include('partials._paginate',['data'=> $reports])

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
