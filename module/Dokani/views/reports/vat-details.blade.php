@extends('layouts.master')


@section('title', 'VAT Report Details')

@section('page-header')
    <i class="fa fa-info-circle"></i> VAT Report Details ({{ $date }})
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
                width: 50%;
            }
        }
    </style>


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
                        <div class="row no-print">
                            <div class="col-sm-10"></div>
                            <div class="col-sm-2">
                                <div class="btn-group btn-corner">
                                    <a href="{{ route('dokani.reports.vats') }}" class="btn btn-sm btn-success btn_search">
                                        <i class="fa fa-backward"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 py-4">

                                <div class="table-responsive" style="border: 1px #cdd9e8 solid;">

                                    <!-- Table -->
                                    <table id="tblData" class="table table-striped table-bordered table-hover">
                                        <caption class="text-center no-print"><strong>VAT Report at ({{ $date }})</strong></caption>
                                        <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th class="th">Sale Price</th>
                                            <th class="th">VAT Amount</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($details as$item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ number_format($item->payable_amount,2) }}</td>
                                                <td>
                                                    {{ number_format($item->total_vat,2) }}
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



                                </div>
                                <div class="no-print">
                                    @include('partials._paginate',['data'=> $details])
                                </div>

                                <div class="no-print mx-2 py-3">
                                    <a href="javascript:void(0)" onclick="print()">
                                        <i class="fa fa-print" style="font-size:40px"></i>
                                    </a>
                                    <a  href="javascript:void(0)" onclick="exportTableToExcel('tblData','VAT Reports')">
                                        <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="">
                                    </a>
                                    <a href="javascript:void(0)" id="btnExport" onclick="Export('tblData','VAT Reports')">
                                        <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}" alt="">
                                    </a>
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

