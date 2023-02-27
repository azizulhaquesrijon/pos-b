@extends('layouts.master')


@section('title', 'Product Opening Barcode List')


@section('page-header')
    <i class="fa fa-info-circle"></i> Product Opening Barcode List
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

            .row {
                padding: 0 !important;
                box-shadow: none !important;
                margin: 0 !important;
                border: none !important;
            }

            .col-sm-12 {
                padding: 0 !important;
                box-shadow: none !important;
                margin: 0 !important;
                border: none !important;
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
                height: 60px !important;
                margin-top: -8px !important;
            }

            .barcode-p {
                font-size: 8px !important;
                margin-top: -5px !important;
            }


            .barcode-attr {
                font-size: 6px !important;
            }

            .mrp {
                font-size: 7px !important;
                font-weight: 700;
            }

            .labelPrintDiv {
            / padding: 1px !important; /
            padding-top: 6px !important;
            }




            .labelPrintDiv .barcode-p {
                font-size: 12px !important;
            }


            .labelPrintDiv .barcode-attr {
                font-size: 5px !important;
            }

            .labelPrintDiv .mrp {
                font-size: 12px !important;
                font-weight: 700;
            }

            .labelPrintDiv .barcode-h4{
                font-size: 6px !important;
                margin-top: 3px !important;
                margin-bottom: 1px !important;
            }

            .labelPrintDiv .barcode-h5 {
                width: 160px !important;
                margin: 0px auto !important;
                /*inline-size: 100px !important;*/
                /*overflow-wrap: break-word !important;*/
                font-size: 12px !important;
                word-break: break-all !important;
                /*word-wrap: break-word !important;*/
                height: 28px !important;
                margin-bottom: 2px !important;
            }

            .labelPrintDiv .barcode-img {
                width: 75% !important;
                height: 45px !important;
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
            font-size: 13px;
            font-weight: 600;
        }


        .barcode-attr {
            font-size: 9px;
            font-weight: 600;
            margin-top: -12px;
        }

        .mrp {
            font-size: 16px;
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
            height: 60px;
            margin-bottom: 5px
        }

        input[type=checkbox].ace.disabled+.lbl::before, input[type=checkbox].ace:disabled+.lbl::before, input[type=checkbox].ace[disabled]+.lbl::before, input[type=radio].ace.disabled+.lbl::before, input[type=radio].ace:disabled+.lbl::before, input[type=radio].ace[disabled]+.lbl::before {
            color: green !important;
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


                @for($i=0; $i<20; $i++)
                    <div id="labelPrintDiv" class="labelPrintDiv" style="display: none; padding: 0 !important: margin: 0 !important">
                        <div class="text-center page-break {{ $i < 1 ? 'first-row-margin' : '' }} {{ $i == 1 ? 'second-row-margin' : '' }}">
                            <h5 class="barcode-h5"><strong>{{$product->name}}</strong></h5>
                            <img class="barcode-img" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, "C128") }}" alt="barcode" />
                            <p class="barcode-p">{{ $product->barcode }}</p>
                            <p class="mrp">MRP: TK {{ number_format($product->sell_price, 2, '.', '') }}</p>
                        </div>
                    </div>
                @endfor



            <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0px auto !important;">
                    <div class="col-md-12">
                        <div style="display: flex; align-items:center; justify-content:space-between; margin-bottom: 20px">
                            <div></div>

                            <div class="btn-group">
                                <a class="btn btn-sm btn-primary" type="button" onclick="allBarcodePrint()">
                                    <i class="fa fa-print"></i> Print
                                </a>
                                <a class="btn btn-sm btn-black" onclick="labelPrint()">
                                    <i class="fa fa-print"></i> Label Print
                                </a>
{{--                                <a class="btn btn-sm btn-success" href="{{ route('pdt.openings.index') }}">--}}
{{--                                    <i class="fa fa-long-arrow-left"></i> Back to list--}}
{{--                                </a>--}}
                            </div>
                        </div>


{{--                        <div class="row barcode-row">--}}
{{--                            @foreach($productOpeningBarcodes->productBarcodeTrackings ?? [] as $key => $item)--}}

{{--                                @if (optional(optional($item)->productBarcode)->is_print == 0)--}}
{{--                                    <div class="allPrint col-md-3 col-sm-12 text-center page-break {{ $key < 4 ? 'first-row-margin' : '' }}" style="display: none">--}}

{{--                                        --}}{{-- <h4 class="barcode-h4"><strong>{{ optional(optional($productOpeningBarcodes)->company)->name }}</strong></h4> --}}
{{--                                        <h5 class="barcode-h5"><strong>{{ optional(optional($productOpening)->product)->name }}</strong></h5>--}}
{{--                                        <img class="barcode-img" src="data:image/png;base64,{{ DNS1D::getBarcodePNG(optional(optional($item)->productBarcode)->barcode, "C128") }}" alt="barcode" />--}}
{{--                                        <p class="barcode-p">{{ optional(optional($item)->productBarcode)->barcode }}</p>--}}
{{--                                        <p class="barcode-attr">--}}
{{--                                            {{ optional(optional($productOpening)->fabric)->name }} {{ optional(optional($productOpening)->fabric)->name != null ? ' -' : ''}}--}}
{{--                                            {{ optional(optional($productOpening)->size)->name }} {{ optional(optional($productOpening)->size)->name != null ? ' -' : ''}}--}}
{{--                                            {{ optional(optional($productOpening)->color)->name }}--}}
{{--                                        </p>--}}
{{--                                        <p class="mrp">MRP: TK {{ number_format($productOpening->sale_price, 2, '.', '') }}</p>--}}
{{--                                    </div>--}}
{{--                                @endif--}}

{{--                            @endforeach--}}
{{--                        </div>--}}



{{--                        <div class="row barcode-grid">--}}
{{--                            @foreach($productOpeningBarcodes->productBarcodeTrackings ?? [] as $key => $item)--}}


{{--                                <div class="col-md-3 col-sm-12 text-center page-break {{ $key < 4 ? 'first-row-margin' : '' }}">--}}

{{--                                    --}}{{-- <h4 class="barcode-h4"><strong>{{ optional(optional($productOpeningBarcodes)->company)->name }}</strong></h4> --}}
{{--                                    <h5 class="barcode-h5"><strong>{{ optional(optional($productOpening)->product)->name }}</strong></h5>--}}
{{--                                    <img class="barcode-img" style="position: relative;" src="data:image/png;base64,{{ DNS1D::getBarcodePNG(optional(optional($item)->productBarcode)->barcode, "C128") }}" alt="barcode" />--}}
{{--                                    <span class="label" style="position: absolute; left: 20px; background-color: yellow; color: #000000; font-weight: bold">{{ checkBarcodeIssuedOrNot(optional(optional($item)->productBarcode)->barcode) }}</span>--}}
{{--                                    <p class="barcode-p">{{ optional(optional($item)->productBarcode)->barcode }}</p>--}}
{{--                                    <p class="barcode-attr">--}}
{{--                                        {{ optional(optional($productOpening)->fabric)->name }} {{ optional(optional($productOpening)->fabric)->name != null ? ' -' : ''}}--}}
{{--                                        {{ optional(optional($productOpening)->size)->name }} {{ optional(optional($productOpening)->size)->name != null ? ' -' : ''}}--}}
{{--                                        {{ optional(optional($productOpening)->color)->name }}--}}
{{--                                    </p>--}}
{{--                                    <p class="mrp">MRP: TK {{ number_format($productOpening->sale_price, 2, '.', '') }}</p>--}}
{{--                                    <div style="background-color: #efefef; width: 220px; margin: 0 auto; margin-top: -45px; margin-bottom: 40px; display: flex; align-items:center; justify-content: space-between; height:25px">--}}
{{--                                        @if(optional(optional($item)->productBarcode)->is_print == 0)--}}
{{--                                            <form action="{{ route('pdt.update-barcode-print-status', optional(optional($item)->productBarcode)->id) }}" id="updatePrintStatusForm{{ $item->id }}" method="POST">--}}
{{--                                                @csrf--}}
{{--                                            </form>--}}
{{--                                            <div class="checkbox" style="margin-left: -70px">--}}
{{--                                                <label>--}}
{{--                                                    <input type="checkbox" class="ace" onclick="updatePrintStatus(this, `{{ $item->id }}`)">--}}
{{--                                                    <span class="lbl"> Already Print</span>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}

{{--                                            <form action="{{ route('pdt.delete-barcode', optional(optional($item)->productBarcode)->id) }}" onsubmit="return confirm('Are You Sure')" method="POST">--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}

{{--                                                <button class="text-danger pr-1" style="border: none"><i class="far fa-trash"></i></button>--}}
{{--                                            </form>--}}
{{--                                        @else--}}
{{--                                            <div class="checkbox" style="margin: 0 auto">--}}
{{--                                                <label>--}}
{{--                                                    <input type="checkbox" class="ace" checked disabled>--}}
{{--                                                    <span class="lbl"> Already Print</span>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}



                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection



@section('js')

    <script>
        $('.allPrint').hide();
        $('.labelPrintDiv').hide();
        $('.barcode-grid').show();




        function allBarcodePrint()
        {
            $('head').append(`
                <style>
                @media print {
                    @page
                    {
                        size: A4 ;
                    }
                }
                </style>
            `);

            $('.allPrint').show();
            $('.labelPrintDiv').hide();
            $('.barcode-grid').hide();

            window.print()
        }




        function labelPrint() {
            $('head').append(`
                <style>
                    @media print {
                        @page {
                            size: 1.1in .70in;
                        }
                    }
                </style>
            `);

            $('.allPrint').hide();
            $('.labelPrintDiv').show();
            $('.barcode-grid').hide();

            window.print()
        }




        function updatePrintStatus(obj, id) {
            if($(obj).is(':checked')) {
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {

                    if (result.value) {
                        $('#updatePrintStatusForm'+id).submit();

                    } else {

                        $(obj).prop('checked', false);

                        return;
                    }
                })
            }
        }
    </script>

@endsection
