@extends('layouts.master')


@section('title', 'Product Barcode List')


@push('style')
    <style>

        .barcode-section {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 20px;
            gap: 20px;
            background: #464646;
        }

        .barcode-item {
            width: 144px;
            height: 96px;
            background: white;
            color: #000000;
            text-align: center;
            font-weight: 500;
            padding: 3px;
        }


        .barcode-item .image {
            width: 90%;
            margin: 0px auto;
            height: 30px;
        }


        .barcode-item .title {
            height: 30px;
            line-height: 15px;
            margin-top: -1px;
            margin-bottom: 0px;
        }

        .barcode-item .barcode {
            margin-bottom: 0px;
        }

        .barcode-item .mrp {
            margin-top: -5px;
            margin-bottom: 0px;
        }

        
        @media print {
            @page {
                size: 1.5in 1in !important;
            }


            .barcode-section {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                padding: 20px;
                gap: 20px;
                background: #464646;
            }

            .barcode-item {
                width: 144px;
                height: 96px;
                background: white;
                color: #000000;
                text-align: center;
                font-weight: 500;
                padding: 3px;
            }


            .barcode-item .image {
                width: 90%;
                margin: 0px auto;
                height: 30px;
            }


            .barcode-item .title {
                height: 30px;
                line-height: 15px;
                margin-top: -1px;
                margin-bottom: 0px;
            }

            .barcode-item .barcode {
                margin-bottom: 0px;
            }

            .barcode-item .mrp {
                margin-top: -5px;
                margin-bottom: 0px;
            }
        }
    </style>
@endpush





@section('content')
    <section class="barcode-section">
        @for($i = 0; $i < 1; $i++)
            <div class="barcode-item" style="width: 144px; height: 96px">
                <p class="title">{{ Str::limit($product->name . ' Hidjkf hkekek hheeek oooooo ere herekrkekk', 35) }}</p>
                <img class="image" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, "C128") }}" alt="barcode" />
                <p class="barcode">{{ $product->barcode }}</p>
                <p class="mrp">MRP: TK {{ number_format($product->sell_price, 2) }}</p>
            </div>
        @endfor
    </section>
@endsection



@section('js')
@endsection
