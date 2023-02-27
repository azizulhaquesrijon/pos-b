@extends('layouts.master')


@section('title', 'Alert Inventory Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Alert Inventory Report
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
                            <div class="col-sm-12">
                                <form action="">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="row">
                                                <div class="col-md-4">
                                                    <select name="name" class="select2 form-control"
                                                            data-placeholder="-Select Product-">
                                                        <option value=""></option>

                                                        @foreach ($products as $key => $item)
                                                            <option value="{{ $item }}" {{ request('name') == $item ? 'selected' : '' }}>
                                                                {{ $item }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="unit_id" class="select2 form-control"
                                                            data-placeholder="-Select Unit-">
                                                        <option value=""></option>

                                                        @foreach ($units as $key => $item)
                                                            <option value="{{ $key }}" {{ request('unit_id') == $key ? 'selected' : '' }}>
                                                                {{ $item }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="category_id" class="select2 form-control"
                                                            data-placeholder="-Select Category-">
                                                        <option value=""></option>

                                                        @foreach ($categories as $key => $item)
                                                            <option value="{{ $key }}" {{ request('category_id') == $key ? 'selected' : '' }}>
                                                                {{ $item }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="btn-group btn-corner" style="float: right">
                                                    <button type="submit" class="btn btn-xs btn-success">
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                    <a href="{{ request()->url() }}" class="btn btn-xs">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
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
                                                <th>Product Name</th>
                                                <th>Product Code</th>
                                                <th class="text-right">Available Qty</th>
                                                <th class="text-right">Alert Qty</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>


                                            @forelse ($reports as $key => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->id }}</td>
                                                    <td class="text-right">{{ $item->available_qty }}</td>
                                                    <td class="text-right">{{ $item->alert_qty }}</td>
                                                    <td class="text-center">
                                                        @if ($item->available_qty <= $item->alert_qty)
                                                            <span class="text-danger">Low Qty</span>
                                                        @else
                                                            <span class="text-success">Enough Qty</span>
                                                        @endif

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

                                    {{-- @include('partials._paginate', ['data' => $reports]) --}}

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
