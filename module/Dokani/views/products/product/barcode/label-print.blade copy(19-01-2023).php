@extends('layouts.master')

@section('title', 'Add Product')

@section('page-header')
    <i class="fa fa-plus-circle"></i> {{ request('upload') ? 'Upload' : 'Add' }} Product
@stop


@push('style')
    <style>

        .table {
            margin-bottom: 0 !important;
        }

        @media print {

            * {
                margin: 0 !important;
                padding: 0 !important;
                font-family: monospace !important;
            }
            .page-break {
                display: block;
                page-break-inside: avoid;
            }

            .d-print-block {
                display: block;
            }

            .first-row-margin {
                margin-top: -40px !important;
            }

            .widget-box {
                padding: 0 !important;
                box-shadow: none !important;
                margin: 0 !important;
                border: none !important;
            }


            .widget-header {
                display: none !important;
            }

            .btn {
                display: none !important;
            }


            .barcode-row {
                margin: 0 auto !important;
            }

            .barcode-h4{
                font-size: 10px !important;
            }

            .barcode-h5 {
                font-size: 8px !important;
            }

            .barcode-row .barcode-img {
                width: 130px !important;
                height: 50px !important;
                /*margin-top: -8px !important;*/
            }

            .barcode-p {
                font-size: 8px !important;
                /*margin-top: -5px !important;*/
            }


            .barcode-attr {
                font-size: 6px !important;
            }

            .mrp {
                font-size: 17px !important;
                font-weight: 700;
            }

            .labelPrintDiv {
                padding: 1px !important;
                padding-top: 10px !important;
                padding-left: 35px !important;
                text-align: center;
            }




            .labelPrintDiv .barcode-p {
                font-size: 14px !important;
            }


            .labelPrintDiv .barcode-attr {
                font-size: 14px !important;
            }

            .labelPrintDiv .mrp {
                font-size: 14px !important;
                font-weight: 700;
            }

            .labelPrintDiv .barcode-h4{
                font-size: 16px !important;
                /*margin-top: 3px !important;*/
                margin-bottom: 1px !important;
            }

            .labelPrintDiv .barcode-h5 {
                width: 100% !important;
                font-size: 16px !important;
                margin-bottom: 2px !important;
            }

            .labelPrintDiv .barcode-img {
                width: 75% !important;
                height: 50px !important;
                margin: 0 auto !important;
                margin-bottom: 5px !important;
            }


            /* .labelPrintDiv .label-print {
               margin-left: -6px !important;
               margin-top: 43px !important;
            } */


            .labelPrintDiv .first-row-margin {
                margin: 0 !important;
            }
            /* .labelPrintDiv .second-row-margin {
                padding-top: 1px !important;
                margin-bottom: 0 !important;
            } */

        }

        .d-print-block {
            display: none;
        }


        .barcode-p {
            font-size: 15px;
            font-weight: 600;
        }


        .barcode-attr {
            font-size: 9px;
            font-weight: 600;
            margin-top: -12px;
        }

        .mrp {
            font-size: 16px !important;
            font-weight: 700;
            margin-top: -10px;
            margin-bottom: 50px
        }

        .barcode-h4{
            color: #000000;
        }

        .barcode-h5 {
            color: #000000;
            margin-top: -6px;
        }

        .barcode-img {
            width: 220px;
            height: 50px;
            margin-bottom: 5px
        }

    </style>
@endpush



@section('content')

<div class="row">
    <div class="col-sm-12">

        @include('partials._alert_message')

        <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">



            <!-- heading -->
            <div class="widget-header widget-header-small">
                <h3 class="widget-title smaller text-primary">
                    @yield('page-header')
                </h3>

                <div class="widget-toolbar">
                    <a href="{{ request()->url() }}" title="Refresh Data" data-toggle="tooltip">
                        <span>
                            <i class="fa fa-refresh bigger-110"></i> Refresh
                        </span>
                    </a>
                </div>
            </div>




            <div class="space"></div>


            <!-- LIST -->
            <div class="row" style="width: 100%; margin: 0px auto !important;">
                <div class="col-sm-12">
                    <div style="display: flex; align-items:center; justify-content:space-between; margin-bottom: 20px">
                        <div></div>
                        <div class="btn-group btn-corner">
                            <a class="btn btn-sm btn-primary" type="button" onclick="allBarcodePrint()">
                                <i class="fa fa-print"></i> Print
                            </a>
                            <a class="btn btn-sm btn-black" onclick="labelPrint()">
                                <i class="fa fa-print"></i> Label Print
                            </a>
                            <a class="btn btn-sm btn-success" href="">
                                <i class="fa fa-long-arrow-left"></i> Back to list
                            </a>
                        </div>
                    </div>


                    <div class="row barcode-row">
                        @for($i=0; $i<20; $i++)
                            <div class="allPrint col-md-3 col-sm-12 text-center">
                                <h4 class="barcode-h4"><strong>{{$product->name}}</strong></h4>
                                <h5 class="barcode-h5"><strong></strong></h5>
                                <img class="barcode-img pt-1" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, "C128") }}" alt="barcode" />
                                <p class="barcode-p">{{$product->barcode}}</p>
                                <p class="barcode-attr">
                                </p>
                                <p class="mrp">MRP: TK {{ number_format($product->sell_price, 2) }}</p>
                            </div>
                            @endfor

                            <div id="labelPrintDiv" class="labelPrintDiv" style="display: none">
                                <div class="col-sm-12 text-center page-break label-print">
                                    <h4 class="barcode-h4"><strong>{{ $product->name }}</strong></h4>
                                    <h5 class="barcode-h5"><strong></strong></h5>
                                    <img class="barcode-img pt-1" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, "C128") }}" alt="barcode" />
                                    <p class="barcode-p">{{ $product->barcode }}</p>
                                    <p class="barcode-attr">

                                    </p>
                                    <p class="mrp">MRP: TK {{ number_format($product->sell_price, 2) }}</p>
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


    <script src="{{ asset('assets/custom_js/file_upload.js') }}"></script>

    <script>
        $('.allPrint').show();
        $('.labelPrintDiv').hide();




        function allBarcodePrint()
        {
            $('head').append(`
                <style>
                @media print {
                    @page
                    {
                        size: A4;
                    }
                }
                </style>
            `);

            $('.allPrint').show();
            $('.labelPrintDiv').hide();

            window.print()
        }




        function labelPrint() {
            $('head').append(`
                <style>
                @media print {
                    @page {
                         size: 1.5in 1in;
                    }
                }
                </style>
            `);

            $('.allPrint').hide();
            $('.labelPrintDiv').show();

            window.print()
        }
    </script>
@endsection
