@extends('layouts.master')


@section('title', 'Product Wise Sale Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Product Wise Sale Report
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
                            <div class="col-md-12">
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
                                                               style="cursor: pointer" data-date-format="yyyy-mm-dd h:m:s">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-exchange"></i>
                                                        </span>
                                                        <input type="text" name="to" class="form-control date-picker"
                                                               value="{{ request('to') }}" autocomplete="off" placeholder="To"
                                                               style="cursor: pointer" data-date-format="yyyy-mm-dd h:m:s">
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
                                            <th>Date</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right">Sale Price</th>
                                            <th class="text-right">Purchase Price</th>
                                            <th class="text-right">Profit</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @php
                                            $profit_total = 0;
                                            $sale_total = 0;
                                        @endphp
                                        @forelse ($reports as $key => $item)
                                            @php
                                                // $grand_total += $total = $item->payable_amount * $item->product->sell_price;
                                                 $total =  $item->quantity * $item->buy_price;

                                                $profit_total += $item->price * $item->quantity - $total;
                                                $sale_total += $item->price * $item->quantity;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                                <td class="text-center">{{ $item->quantity ?? 0 }}</td>
                                                <td class="text-right">{{ number_format($item->price * $item->quantity,2) }}</td>
                                                <td class="text-right">
                                                    {{ number_format($total,2) }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($item->price * $item->quantity - $total,2) }}
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
                                        <tfoot>
                                        <tr>
                                            <th colspan="20">
                                                <a  href="{{ route('dokani.reports.product-wise-sales') }}?export_type=product-wise-sale-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                    title="Excel" style="padding-left: 10px" target="_blank">
                                                    <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                                </a>&nbsp;&nbsp;&nbsp;
                                                <a href="{{ route('dokani.reports.product-wise-sales') }}?export_type=product-wise-sale-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
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

