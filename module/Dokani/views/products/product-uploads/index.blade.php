@extends('layouts.master')


@section('title', 'Products')

@section('page-header')
    <i class="fa fa-info-circle"></i> Products <small style="font-size: 10px">({{ $products->total() }})</small>
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
                        <form action="{{ route('dokani.product-uploads.store') }}" method="POST" style="display: inline">
                            @csrf

                            <button type="submit" class="btn btn-xs btn-info">
                                Upload 50 rows
                            </button>
                        </form>
                        {{-- <a href="{{ route('dokani.product-uploads-with-branch') }}" class="btn btn-xs btn-info">

                        </a> --}}
                        <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#exampleModalCenter" style="padding: 1px;">
                            Upload with Branch
                        </button>

                        <a href="{{ route('dokani.products.create') }}" class="">
                            <i class="fa fa-plus"></i> Add New Product
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
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>Unit</th>
                                                <th>Type</th>
                                                <th>Barcode</th>
                                                <th class="text-right">Purchase Price</th>
                                                <th class="text-right">Sell Price</th>
                                                <th class="text-right">Opening Qty</th>
                                                <th class="text-right">Alert Qty</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($products as $key => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->name }}</td>

                                                    <td>{{ $item->category }}</td>
                                                    <td>{{ $item->unit }}</td>
                                                    <td>{{ $item->product_type }}</td>
                                                    <td class="text-right">{{ $item->barcode }}</td>
                                                    <td class="text-right">
                                                        {{ $item->buy_price }}</td>
                                                    <td class="text-center">{{ $item->sell_price }}
                                                    <td class="text-center">{{ $item->openingQty }}
                                                    <td class="text-center">{{ $item->alertQty }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-corner">

                                                            {{-- <!-- edit -->
                                                            <a href="{{ route('dokani.products.edit', $item->id) }}"
                                                                role="button" class="btn btn-sm btn-success" title="Edit">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a> --}}

                                                            <!-- delete -->
                                                            <button type="button"
                                                                onclick="delete_item(`{{ route('dokani.product-uploads.destroy', $item->id) }}`)"
                                                                class="btn btn-sm btn-danger" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
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

                                    @include('partials._paginate',['data'=> $products])

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('products.product-uploads._inc.branch_wise_upload')

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
