@extends('layouts.master')


@section('title', 'Products')

@section('page-header')
    <i class="fa fa-info-circle"></i> Products
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

                        <a href="{{ route('dokani.products.edit', $product->id) }}" class="">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('dokani.products.create') }}" class="">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                        <a href="{{ route('dokani.products.index') }}" class="">
                            <i class="fa fa-list"></i> List
                        </a>
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">


                        <div class="row">
                            <div class="col-xs-12">

                                <div class="table-responsive" style="border: 1px #cdd9e8 solid;">

                                    <!-- Table -->
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">

                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <td>{{ $product->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Barcode</th>
                                                <td>
                                                    <span>

                                                        <a
                                                            href="{{ route('dokani.product-barcode.print', $product->id) }}">
                                                            {!! DNS1D::getBarcodeHTML($product->barcode ?? 123456, 'C128') !!}
                                                        </a>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Category</th>
                                                <td>{{ optional($product->category)->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Unit</th>
                                                <td>{{ optional($product->unit)->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Product Type</th>
                                                <td>{{ $product->product_type == 1 ? "Finish" : "Raw Materials" }}</td>
                                            </tr>
                                            <tr>
                                                <th>Sale Price</th>
                                                <td>{{ $product->sell_price }}</td>
                                            </tr>
                                            <tr>
                                                <th>Purchase Price</th>
                                                <td>{{ $product->purchase_price }}</td>
                                            </tr>
                                            <tr>
                                                <th>Image</th>
                                                <td>
                                                    <img src="{{ asset(file_exists($product->image) ? $product->image : 'assets/images/default.png') }}"
                                                        alt="{{ $product->name }}" width="120" height="120">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

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
