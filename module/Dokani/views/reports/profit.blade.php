@extends('layouts.master')


@section('title', 'Profit Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Profit Report
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
                                            <div class="input-daterange input-group">

                                                <input type="text" name="from" class="form-control date-picker" value="{{ request('from') }}"
                                                       autocomplete="off" placeholder="From" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                <span class="input-group-addon">
                                                            <i class="fa fa-exchange"></i>
                                                        </span>
                                                <input type="text" name="to" class="form-control date-picker" value="{{ request('to') }}"
                                                       autocomplete="off" placeholder="To" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                            </div>
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
                                            <th class="text-center">Description</th>
                                            <th class="text-right">Cost</th>
                                            <th class="text-right">Sale</th>
                                            <th class="text-center">Return</th>
                                            <th class="text-right">Exchange Profit</th>
                                            <th class="text-right">Profit</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @php
                                            $profit_total = 0;
                                            $grand_profit_total = 0;
                                        @endphp
                                        @if(request()->filled('from') || request()->filled('to'))
                                            @forelse ($reports as $key => $item)
                                                @php

                                                    $sale_cost = $total_return_price = $total_return_cost = $total_exchange_cost = $total_exchange_price = 0;

                                                     foreach ($item->details as $detail){
                                                         $sale_cost += $detail->buy_price * $detail->quantity;
                                                     }

                                                     $total_sale = $item->payable_amount - $item->total_vat ?? 0;

                                                     foreach ($item->sale_return_exchange ?? [] as $key => $return_exchange) {
                                                        $total_return_price += $return_exchange->return_grand_total ?? 0;
                                                        $total_return_cost += $return_exchange->total_return_cost ?? 0;
                                                        $total_exchange_cost += $return_exchange->total_exchange_cost ?? 0;
                                                        $total_exchange_price += $return_exchange->exchange_subtotal ?? 0;
                                                     }
                                                     $total_sale_cost = number_format($sale_cost, 2, '.', '') - number_format($total_return_cost, 2, '.', '');
                                                     $total_sale_price = number_format($total_sale, 2, '.', '') - number_format($total_return_price, 2, '.', '') ?? 0;

                                                     $total_sale_profit = number_format($total_sale_price, 2, '.', '') - number_format($total_sale_cost, 2, '.', '') ?? 0;

                                                     $total_exchange_profit = number_format($total_exchange_price, 2, '.', '') - number_format($total_exchange_cost, 2, '.', '') ?? 0;

                                                     $total_profit = $total_sale_profit + $total_exchange_profit ?? 0;

                                                    $grand_profit_total += $total_profit;
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="text-center">
                                                        {{ 'Sale ('.$item->date.')' }}
                                                        {{-- (total-sale:{{ $total_sale }})
                                                        (total-return :{{ $total_return_price }})
                                                        (Total-sale-price:{{ $total_sale_price }})
                                                        (total-sale-cost :{{ $total_sale_cost }})
                                                        (sale-profit :{{ $total_sale_profit }})
                                                        (exchnage-price :{{ $total_exchange_price }})
                                                        (exchnage-cost :{{ $total_exchange_cost }})
                                                        (exchnage-profit :{{ $total_exchange_profit }}) --}}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($sale_cost, 2, '.', '') }}
                                                    </td>
                                                    <td class="text-right">{{ number_format($total_sale, 2, '.', '') }}</td>
                                                    <td class="text-center"><i class="fa {{ $total_return_price > 0 ? 'fa-check' : '' }}"></i></td>
                                                    <td class="text-right">{{ number_format($total_exchange_profit ?? 0, 2, '.', '') }}</td>
                                                    <td class="text-right">{{ number_format($total_profit, 2, '.', '') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="30" class="text-center text-danger py-3"
                                                        style="background: #eaf4fa80 !important; font-size: 18px">
                                                        <strong>No records found!</strong>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <tr>
                                                <td colspan="30" class="text-center text-danger py-3"
                                                    style="background: #eaf4fa80 !important; font-size: 18px">
                                                    <strong>No records found!</strong>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-right" style="border-bottom: 1px solid transparent;"><strong>Gross Profit:</strong></th>
                                            <th class="text-right">
                                                {{ number_format($grand_profit_total, 2, '.', '') }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="6" class="text-right" style="border-bottom: 1px solid transparent;"><strong>Total Expense:</strong></th>
                                            <th class="text-right">
                                                {{ '(-) '.number_format($expense,2) ?? 0 }}
                                            </th>
                                        </tr>

                                        <tr>
                                            <th colspan="6" class="text-right"><strong>Net Profit:</strong></th>
                                            <th class="text-right">
                                                {{ number_format($grand_profit_total - $expense, 2, '.', '') }}
                                            </th>
                                        </tr>

                                        <tr>
                                            <th colspan="20">
                                                <a  href="{{ route('dokani.reports.profit') }}?export_type=profit-excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                                                    title="Excel" style="padding-left: 10px" target="_blank">
                                                    <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                                </a>&nbsp;&nbsp;&nbsp;
                                                <a href="{{ route('dokani.reports.profit') }}?export_type=profit-pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
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
