@extends('layouts.master')


@section('title', 'VAT Reports')

@section('page-header')
    <i class="fa fa-info-circle"></i> VAT Reports
@stop

@section('content')
    <style>
        @media print {

            .no-print {
                display: none !important;
            }
            .widget-box {
                border: none !important;
                width: 100%;
            }
            .th{

                margin: 0;
                padding: 0;
                width: 40%;
            }
            .print-header{
                display: block !important;
            }
            .table-footer-calculate{
                visibility: visible !important;
            }
        }
        .table-footer-calculate{
            visibility: hidden;
        }
    </style>

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header no-print">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Searching -->
                        <div class="row no-print">
                            <div class="col-sm-12">
                                <form action="">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="row">
                                                <div class="col-md-4" style="margin-left: 17%;">
                                                    <input type="text" name="invoice_no" value="{{ request('invoice_no') }}"
                                                           class="form-control" placeholder="Invoice No">
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

                        @include('partials._print-header')
                        <div class="row widget-box">
                            <div class="col-xs-12">

                                <div class="table-responsive" id="content1" style="border: 1px #cdd9e8 solid;">

                                    <!-- Table -->
                                    <table id="tblData" class="table table-striped table-bordered table-hover">

                                        <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th class="th">Date</th>
                                            <th class="th">Sale ID</th>
                                            <th class="text-right th">{{ request()->filled('from') ? 'Total' : '' }} Sale Price</th>
                                            <th class="text-right th">{{ request()->filled('from') ? 'Total' : '' }} VAT Amount</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $total_sale = 0;
                                                $total_vat_amount = 0;
                                            @endphp

                                        @forelse ($reports as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                @if(request()->filled('from'))
                                                    <td>
                                                        <a href="{{ route('dokani.reports.vat-details', $item->date) }}" role="button"
                                                           title="Details Show">{{ $item->date }}
                                                        </a>
                                                    </td>
                                                @else
                                                    <td>{{ $item->date }}</td>
                                                @endif
                                                <td class="text-right">{{ $item->id }}</td>
                                                <td class="text-right">{{ number_format($item->payable_amount,2,'.','') }}</td>

                                                <td class="text-right">
                                                    {{ number_format($item->total_vat,2,'.','') }}
                                                </td>

                                            </tr>

                                            @php
                                                $total_sale += number_format($item->payable_amount,2,'.','');
                                                $total_vat_amount += number_format($item->total_vat,2,'.','');
                                            @endphp
                                        @empty
                                            <tr>
                                                <td colspan="30" class="text-center text-danger py-3"
                                                    style="background: #eaf4fa80 !important; font-size: 18px">
                                                    <strong>No records found!</strong>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                        <tfoot class="table-footer-calculate">
                                            <tr>
                                                <td colspan="3" style="text-align: right"><strong>Total </strong></td>
                                                <td style="text-align: right"><strong>{{ $total_sale }}</strong></td>
                                                <td style="text-align: right"><strong>{{ $total_vat_amount }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="no-print">
                                    @include('partials._paginate',['data'=> $reports])
                                </div>
                                @if(!request()->filled('from'))
                                    <div class="no-print mx-2 py-3">
                                        <a href="javascript:void(0)" onclick="window.print()">
                                        {{-- <a href="javascript:void(0)" onclick="print()"> --}}
                                            <i class="fa fa-print" style="font-size:30px"></i>
                                        </a>
                                        <a  href="javascript:void(0)" onclick="exportTableToExcel('tblData','VAT Reports')">
                                            <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                        </a>
                                        <a href="javascript:void(0)" id="btnExport" onclick="Export('tblData', 'VAT Reports')">
                                            <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}" alt="">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')

    <script src="{{ asset('assets/custom_js/export-excel-file.js') }}"></script>
    <script src="{{ asset('assets/custom_js/export-pdf-file.js') }}"></script>
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

