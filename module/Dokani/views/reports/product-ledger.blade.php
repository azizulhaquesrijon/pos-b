@extends('layouts.master')


@section('title', 'Product Ledger')

@section('page-header')
    <i class="fa fa-info-circle"></i> Product Ledger
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
                                                <div class="col-md-4" style="margin-left: 17%;">
                                                    <select name="product_id" id="" class="form-control select2" style="width: 100%"
                                                            data-placeholder="--Select Product--" required>
                                                        <option value=""></option>
                                                        @foreach($products as $id => $name)
                                                            <option value="{{ $id }}" {{ request('product_id') == $id ? 'selected' : '' }}>
                                                                {{ $name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-daterange input-group">
                                                        <input type="text" name="from" class="form-control date-picker" value="{{ request('from') }}"
                                                               autocomplete="off" placeholder="From"
                                                               style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-exchange"></i>
                                                        </span>
                                                        <input type="text" name="to" class="form-control date-picker"
                                                               value="{{ request('to') }}" autocomplete="off" placeholder="To"
                                                               style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
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
                                                <th>Product Unit</th>
                                                <th class="text-right"> Date</th>
                                                <th class="text-right">Description</th>
                                                <th class="text-right">In</th>
                                                <th class="text-right">Out</th>
                                                <th class="text-right">Damage</th>
                                                <th class="text-right">Available Qty</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @php
                                                $total_in = 0;
                                                $total_out = 0;
                                                $total_available = 0;
                                                $grand_total_available = 0;
                                            @endphp
                                            @foreach($grand_totals as $total)
                                                @php
                                                    $grand_total_available += optional(optional($total->product)->stocks)->available_quantity
                                                @endphp

                                            @endforeach
                                            @forelse ($reports as $key => $item)
                                                @php
                                                    $total_in += $in = $item->stock_type == 'In' ? $item->quantity : 0.00;
                                                    $total_out += $out = $item->stock_type == 'Out' ? $item->quantity : 0.00;
                                                    $total_available += $in - $out;
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ optional($item->product)->name }}</td>
                                                    <td>{{ optional(optional($item->product)->unit)->name }}</td>
                                                    <td class="text-right">{{ $item->date }}</td>
                                                    <td class="text-right">{{ $item->sourceable_type }}</td>
                                                    <td class="text-right">{{ $item->stock_type == 'In' ? $item->quantity : 0.00 }}</td>
                                                    <td class="text-right">{{ $item->stock_type == 'Out' ? $item->quantity : 0.00 }}</td>
                                                    <td class="text-right">{{ 0.00 }}</td>
                                                    <td class="text-right">{{ $total_available }}</td>
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
{{--                                            <tr>--}}
{{--                                                <th colspan="5"><strong>Total Qty:</strong></th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $total_in }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $total_out }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ 0 }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $total_available }}--}}
{{--                                                </th>--}}

{{--                                            </tr>--}}

{{--                                            <tr>--}}
{{--                                                <th colspan="5"><strong>Grand Total Qty:</strong></th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $grand_totals->where('type','In')->sum('quantity') }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $grand_totals->where('type','Out')->sum('quantity') }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ 0 }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $grand_total_available }}--}}
{{--                                                </th>--}}

{{--                                            </tr>--}}

                                            <tr>
                                                <th colspan="20">
                                                    <a  href="{{ route('dokani.reports.product_ledger') }}?export_type=product-ledger-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                        title="Excel" style="padding-left: 10px" target="_blank">
                                                        <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                                    </a>&nbsp;&nbsp;&nbsp;
                                                    <a href="{{ route('dokani.reports.product_ledger') }}?export_type=product-ledger-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
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
