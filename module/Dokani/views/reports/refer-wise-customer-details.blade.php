@extends('layouts.master')


@section('title', 'Refer Wise Customer Details Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Refer Wise Customer Details Report
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
                        @if (hasPermission('dokani.reports.refer-wise-customer', $slugs))
                            <a href="{{ route('dokani.reports.refer-wise-customer') }}" class="">
                                <i class="fa fa-backward"></i> Back
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
                                                <div class="col-md-10">
                                                    <div class="input-daterange input-group">
                                                        <span class="input-group-addon" style="border-left: 1px solid #ccc;">
                                                            Date
                                                        </span>
                                                        <input type="text" name="from" class="form-control date-picker" value="{{ request('from') }}" autocomplete="off" placeholder="From" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-exchange"></i>
                                                        </span>
                                                        <input type="text" name="to" class="form-control date-picker" value="{{ request('to') }}" autocomplete="off" placeholder="To" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-inline col-md-10">
                                                    <div class="input-group" style="width: 123%;">
                                                        <span class="input-group-addon" style="border-left: 1px solid #ccc;">
                                                            Account
                                                        </span>
                                                        <select name="account_type_id" id="" class="form-control chosen-select">
                                                            <option value=""></option>
                                                            @foreach(account() as $key => $item)
                                                                <option value="{{ $key }}">{{ $item }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
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
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Account Type</th>
                                            <th class="text-center">IN</th>
                                            <th class="text-center">OUT</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <th>Refer By - {{ optional($customer)->refer_by_customer_id == null ? 'User' : 'Customer' }}</th>
                                            <th colspan="6">{{ optional($customer)->refer_by_customer_id == null ? optional(optional($customer)->refer_user)->name : optional(optional($customer)->refer_customer)->name }}</th>
                                        </tr>
                                        <tbody>

                                        @php
                                            $total = 0;
                                            $in_total = 0;
                                            $out_total = 0;
                                        @endphp
                                        @forelse ($cashFlows as $key => $item)

                                            @php
                                                $in = $item->balance_type == 'In' ? $item->amount : 0;
                                                $out = $item->balance_type == 'Out' ? $item->amount : 0;
                                                $total = $total + ($in - $out);
                                                $in_total += $in;
                                                $out_total += $out;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->date }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ optional($item->account)->name }}</td>
                                                <td class="text-center">{{ $in }}</td>
                                                <td class="text-center">{{ $out }}</td>
                                                <td class="text-right">{{ $total }}</td>
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
                                        <!--
                                        <tfoot>
                                            <tr>
                                                <th colspan="4"><strong>Grand Total:</strong></th>
                                                <th class="text-center">
                                                    {{ $in_total }}
                                                </th>
                                                <th class="text-center">
                                                    {{ $out_total }}
                                                </th>
                                                <th class="text-right">
                                                    {{ $total }}
                                                </th>
                                            </tr>

                                            <tr style="background: #c7c4c4">
                                                <th colspan="7">
                                                    <a  href="{{ route('dokani.reports.refer-wise-customer.export', optional($customer)->id) }}?export_type=refer-wise-customer-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                        title="Excel" style="padding-left: 10px" target="_blank">
                                                        <i class="fa fa-file-excel-o" style="font-size: 31px;color: #046c04;"></i>
                                                        {{--                                                        <img src="{{ asset('assets/images/excel.png') }}" alt="" width="3%">--}}
                                                    </a>&nbsp;&nbsp;&nbsp;
                                                    <a href="{{ route('dokani.reports.refer-wise-customer.export', optional($customer)->id) }}?export_type=refer-wise-customer-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                    title="Pdf" target="_blank">
                                                        <i class="fa fa-file-pdf-o" style="font-size: 30px;color: #cd0b0b;"></i>
                                                        {{--                                                        <img src="{{ asset('assets/images/pdf.png') }}" alt="" width="3%" height="40px">--}}
                                                    </a>
                                                </th>
                                            </tr>
                                        </tfoot>
                                        -->
                                    </table>

                                    @include('partials._paginate',['data'=> $cashFlows])

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
