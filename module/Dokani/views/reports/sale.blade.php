@extends('layouts.master')


@section('title', 'Sale Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Sale Report
@stop

@push('CSS')



@endpush

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
                            <div class="col-md-12">
                                <form action="">

                                    <table class="table table-bordered">
                                        <tr>

                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <input type="text" name="invoice_no" value="{{ request('invoice_no') }}" class="form-control" placeholder="Invoice No">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="branch_id" class="select2 form-control" data-placeholder="-Select Branch-">
                                                            <option value=""></option>
                                                            @foreach ($branches as $item)
                                                                <option value="{{ $item->id }}" {{ request('branch_id') == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <select name="created_by" class="select2 form-control"
                                                                data-placeholder="-Select User-">
                                                            <option value=""></option>
                                                            @foreach ($users as $item)
                                                                <option value="{{ $item->id }}" {{ request('created_by') == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4 mt-1">
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

                                                    <div class="col-md-4 mt-1">
                                                        <select name="customer_id" class="select2 form-control"
                                                                data-placeholder="-Select Customer-">
                                                            <option value=""></option>
                                                            {{-- <option value="all" {{request('customer_id') == 'all' ? 'selected' : '' }}>All</option> --}}
                                                            @foreach ($customers as $key => $item)
                                                                <option value="{{ $key }}" {{ request('customer_id') == $key ? 'selected' : '' }}>
                                                                    {{ $item }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    @if($areas->count() > 0)
                                                        <div class="col-md-4 mt-1">
                                                            <select name="cus_area_id" class="select2 form-control"
                                                                    data-placeholder="-Select Customer Area-">
                                                                <option value=""></option>

                                                                @foreach ($areas as $item)
                                                                    <option value="{{ $item->id }}" {{ request('cus_area_id') == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif


                                                    @if($categories->count() > 0)
                                                        <div class="col-md-4 mt-1">
                                                            <select name="cus_category_id" class="select2 form-control"
                                                                    data-placeholder="-Select Customer Category-">
                                                                <option value=""></option>

                                                                @foreach ($categories as $item)
                                                                    <option value="{{ $item->id }}" {{ request('cus_category_id') == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

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
                                                <th>Date</th>
                                                <th>Invoice ID</th>
                                                <th class="text-center">Customer</th>
                                                {{-- <th class="text-center">Quantity</th> --}}
                                                <th class="text-right">Sale Price</th>
                                                <th class="text-right">Purchase Price</th>
                                                <th class="text-right">Profit</th>
                                            </tr>
                                        </thead>
                                        @if (true)
                                        {{-- @if(request()->filled('invoice_no') || request()->filled('created_by') || request()->filled('from') || request()->filled('to') || request()->filled('customer_id') || request()->filled('cus_area_id') || request()->filled('cus_category_id')) --}}
                                            <tbody>
                                                {{-- @dd("Hello world") --}}
                                            @php
                                                $profit_total = 0;
                                                $purchase_total = 0;
                                                $sale_total = 0;
                                            @endphp
                                            @forelse ($reports as $key => $item)
                                                @php
                                                    $purchase = 0;
                                                        // $grand_total += $total = $item->payable_amount * $item->product->sell_price;
                                                        foreach ($item->details as $detail){
                                                            $purchase += $detail->buy_price * $detail->quantity;

                                                        }
                                                        //$profit_total += $item->payable_amount - $total;
                                                        $sale_total += $item->payable_amount;
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->date }}</td>
                                                    <td><a href="{{ route('dokani.sales.show', $item->id) }}" target="_blank">{{ $item->invoice_no }}</a></td>
                                                    <td class="text-center">{{ optional($item->customer)->name }}</td>
                                                    {{-- <td class="text-center">{{ $item->total_qty ?? 0 }}</td> --}}
                                                    <td class="text-right">{{ number_format($item->payable_amount,2) }}</td>
                                                    <td class="text-right">
                                                        {{ number_format($purchase,2) }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($item->payable_amount - $purchase,2) }}
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
                                        @else
                                            <tr>
                                                <td colspan="30" class="text-center text-danger py-3"
                                                    style="background: #eaf4fa80 !important; font-size: 18px">
                                                    <strong>No records found!</strong>
                                                </td>
                                            </tr>
                                        @endif
                                         <tfoot>
{{--                                            <tr>--}}
{{--                                                <th colspan="4"><strong>Total:</strong></th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ number_format($sale_total,2) }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ number_format($profit_total,2) }}--}}
{{--                                                </th>--}}
{{--                                            </tr>--}}

                                                <tr>
                                                    <th colspan="20">
                                                        <a  href="{{ route('dokani.reports.sales') }}?export_type=sale-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                            title="Excel" style="padding-left: 10px" target="_blank">
                                                            <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                                        </a>&nbsp;&nbsp;&nbsp;
                                                        <a href="{{ route('dokani.reports.sales') }}?export_type=sale-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
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
