@extends('layouts.master')


@section('title', 'Customer Ledger Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Customer Ledger Report
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
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <select name="customer_id" class="select2 form-control"
                                                            data-placeholder="-Select Customer-" required>
                                                        <option value=""></option>

                                                        @foreach ($customers as $key => $item)
                                                            <option value="{{ $key }}" {{ request('customer_id') == $key ? 'selected' : '' }}>
                                                                {{ $item }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                    @if($areas->count() > 0)
                                                        <div class="col-md-3">
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
                                                        <div class="col-md-3">
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
                                                <div class="col-md-3">
                                                    <div class="input-daterange input-group">

                                                        <input type="text" name="from" class="form-control date-picker" value="{{ request('from') }}" autocomplete="off" placeholder="From" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-exchange"></i>
                                                        </span>
                                                        <input type="text" name="to" class="form-control date-picker" value="{{ request('to') }}" autocomplete="off" placeholder="To" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                    </div>
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
                                                <th>Source ID</th>
                                                <th>Description</th>
{{--                                                <th>Balance Type</th>--}}
                                                <th class="text-right">Debit/Sales</th>
                                                <th class="text-right">Credit/Receive</th>
{{--                                                <th class="text-right">Paid Amount</th>--}}
{{--                                                <th class="text-right">Discount</th>--}}
                                                <th class="text-right">Balance</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if (request('customer_id'))
                                               <tr>
                                                    <th colspan="6"><b>Opening Balance</b></th>
                                                    <th class="text-right">{{ $customer->opening_due }}</th>
                                                </tr>
                                            @endif

                                            @php
                                                $total_due_amount = $customer->opening_due ?? 0;
                                            @endphp

                                            @forelse ($reports as $key => $item)

                                                @php
                                                    $collection_paid_amount = $item->source_type == 'Collection' ? optional($item->source)->paid_amount : 0 ;
                                                    $total_due_amount = optional($item->source)->payable_amount - optional($item->source)->paid_amount ;
                                                    //$total_due_amount = $total_due_amount - $collection_paid_amount;
                                                @endphp
                                                <tr>

                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->source_id }}</td>
                                                    <td>{{ $item->source_type }}</td>
{{--                                                    <td>{{ $item->balance_type }}</td>--}}
                                                    <td class="text-right">{{ number_format(optional($item->source)->payable_amount,2) }}</td>
                                                    <td class="text-right">{{ number_format(optional($item->source)->paid_amount,2) }}</td>
{{--                                                    <td class="text-right">{{ number_format(optional($item->source)->paid_amount,2) }}</td>--}}
{{--                                                    <td class="text-right">{{ number_format(optional($item->source)->discount,2) }}</td>--}}
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

                                            <tr>
                                                <th colspan="30">
                                                    <a  href="{{ route('dokani.reports.customer-ledger') }}?export_type=customer-ledger-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                        title="Excel" style="padding-left: 10px" target="_blank">
                                                        <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                                    </a>&nbsp;&nbsp;&nbsp;
                                                    <a href="{{ route('dokani.reports.customer-ledger') }}?export_type=customer-ledger-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                       title="Pdf" target="_blank">
                                                        <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}" alt="">
                                                    </a>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <p>*Positive balance means that amount is showing as customer's due. ( ব্যালেন্স ধনাত্মক হলে তা উক্ত কাস্টমার এর বাকী টাকা হিসেবে বিবেচিত হবে এবং ঋণাত্মক হলে জমা)</p>

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
