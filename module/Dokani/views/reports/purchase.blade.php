@extends('layouts.master')


@section('title', 'Purchase Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Purchase Report
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
                                                        <input type="text" name="invoice_no" value="{{ request('invoice_no') }}"
                                                               class="form-control" placeholder="Invoice No">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="supplier_id" class="select2 form-control" style="width: 100%"
                                                                data-placeholder="-Select Supplier-">
                                                            <option value=""></option>
                                                            <option value="all" {{request('supplier_id') == 'all' ? 'selected' : '' }}>All</option>

                                                            @foreach ($suppliers as $key => $item)
                                                                <option value="{{ $key }}" {{ request('supplier_id') == $key ? 'selected' : '' }}>
                                                                    {{ $item }}
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
                                                <th>Purchase ID</th>
                                                <th class="text-center">Supplier</th>
                                                <th class="text-right">Purchase Price</th>
                                                <th class="text-right">Paid Amount</th>
                                            </tr>
                                        </thead>
                                        @if(request()->filled('invoice_no') || request()->filled('supplier_id') || request()->filled('from') || request()->filled('to'))
                                        <tbody>

                                            @php
                                                $paid_total = 0;
                                                $purchase_total = 0;
                                            @endphp
                                            @forelse ($reports as $key => $item)
                                                @php
                                                    // $grand_total += $total = $item->payable_amount * $item->product->sell_price;
                                                    $paid_total += $item->paid_amount;
                                                    $purchase_total += $item->payable_amount;
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->date }}</td>
                                                    <td><a href="{{ route('dokani.purchases.show', $item->id) }}" target="_blank">{{ $item->id }}</a></td>
                                                    <td class="text-center">{{ optional($item->supplier)->name }}</td>
                                                    <td class="text-right">{{ number_format($item->payable_amount,2) }}</td>

                                                    <td class="text-right">
                                                        {{ number_format($item->paid_amount,2) }}
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
{{--                                                <th colspan="4"><strong>Grand Total:</strong></th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $purchase_total }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $profit_total }}--}}
{{--                                                </th>--}}
{{--                                            </tr>--}}


                                            <tr>
                                                <th colspan="12">
                                                    <a  href="{{ route('dokani.reports.purchase') }}?export_type=purchase-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                        title="Excel" style="padding-left: 10px" target="_blank">
                                                        <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                                    </a>&nbsp;&nbsp;&nbsp;
                                                    <a href="{{ route('dokani.reports.purchase') }}?export_type=purchase-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
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
