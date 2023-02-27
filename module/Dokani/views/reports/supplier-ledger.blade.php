@extends('layouts.master')


@section('title', 'Supplier Ledger Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Supplier Ledger Report
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
                                                    <select name="supplier_id" class="select2 form-control" style="width: 100%"
                                                            data-placeholder="-Select Supplier-" required>
                                                        <option value=""></option>

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
                                                <th>Source ID</th>
                                                <th>Description</th>
                                                <th class="text-right">Debit/Purchases</th>
                                                <th class="text-right">Credit/Payment</th>
                                                <th class="text-right">Balance</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                        @if (request('supplier_id'))
                                            <tr>
                                                <th colspan="6"><b>Opening Balance</b></th>
                                                <th class="text-right">{{ $supplier->opening_due }}</th>
                                            </tr>
                                        @endif

                                        @php
                                            $total_due_amount = $supplier->opening_due ?? 0;
                                        @endphp

                                        @forelse ($reports as $key => $item)

                                        @php
                                            $payment_paid_amount = $item->source_type == 'Payment' ? optional($item->source)->paid_amount : 0 ;
                                            $total_due_amount = optional($item->source)->payable_amount - optional($item->source)->paid_amount ;
                                            //$total_due_amount = $total_due_amount - $payment_paid_amount;
                                        @endphp



                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->source_id }}</td>
                                                    <td>{{ $item->source_type }}</td>
                                                    <td class="text-right">{{ number_format(optional($item->source)->payable_amount,2) }}</td>
                                                    <td class="text-right">{{ number_format(optional($item->source)->paid_amount,2) }}</td>
                                                    <td class="text-right">{{ number_format($total_due_amount,2) }}</td>
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
{{--                                                <th colspan="5"><strong>Total:</strong></th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $total_purchase_amount }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $total_payment_amount }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $total_paid_amount }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $total_discount_amount }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ request('supplier_id') ? $item->amount ?? 0 : '' }}--}}
{{--                                                </th>--}}
{{--                                            </tr>--}}
{{--                                            <tr>--}}
{{--                                                <th colspan="5"><strong>Grand Total:</strong></th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $grand_total_purchase_amount }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $grand_total_payment_amount }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $grand_total_paid_amount }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ $grand_total_discount_amount }}--}}
{{--                                                </th>--}}
{{--                                                <th class="text-right">--}}
{{--                                                    {{ \Module\Dokani\Models\Supplier::dokani()->sum('balance') }}--}}
{{--                                                </th>--}}
{{--                                            </tr>--}}
                                            <tr>
                                                <th colspan="20">
                                                    <a  href="{{ route('dokani.reports.supplier-ledger') }}?export_type=supplier-ledger-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                        title="Excel" style="padding-left: 10px" target="_blank">
                                                        <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                                    </a>&nbsp;&nbsp;&nbsp;
                                                    <a href="{{ route('dokani.reports.supplier-ledger') }}?export_type=supplier-ledger-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                       title="Pdf" target="_blank">
                                                        <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}" alt="">
                                                    </a>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>


                                    <p>*Positive balance means that amount is showing as due to supplier. ( ব্যালেন্স ধনাত্মক হলে তা উক্ত সাপ্লায়ার এর প্রতি বাকী টাকা হিসেবে বিবেচিত হবে )</p>

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
