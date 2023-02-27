@extends('layouts.master')


@section('title', 'Products')

@section('page-header')
    <i class="fa fa-info-circle"></i> Products <span class="badge badge-info">{{ $products->total() }}</span>
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
                        @if (hasPermission('dokani.products.create', $slugs))
                            <a href="{{ route('dokani.products.create') }}" class="">
                                <i class="fa fa-plus"></i> Add New
                            </a>
                        @endif
                    </span>
                    <span class="widget-toolbar">
                        <a href="{{ route('dokani.product-type-finish-goods') }}" onclick="return confirm('Non-Typed Product will be Finish Goods')" class="make_product_finish">Make Type Finish</a>
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Searching -->
                        <div class="row">
                            <div class="col-sm-12">
                                <form action="">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" name="name" value="{{ request('name') }}"
                                                        class="form-control input-sm" placeholder="Product Name">
                                                </td>
                                                <td>
                                                    <input type="text" name="barcode" value="{{ request('barcode') }}"
                                                        class="form-control input-sm" placeholder="Product Barcode">
                                                </td>
                                                <td>
                                                    <select name="category_id" class="chosen-select form-control"
                                                        data-placeholder="-Select Category-">
                                                        <option value=""></option>
                                                        @foreach ($categories as $key => $item)
                                                            <option value="{{ $key }}"
                                                                {{ request('category_id') == $key ? 'selected' : '' }}>
                                                                {{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>
                                                    <select name="brand_id" class="chosen-select form-control"
                                                            data-placeholder="-Select Brand-">
                                                        <option value=""></option>
                                                        @foreach ($brands as $key => $item)
                                                            <option value="{{ $key }}"
                                                                {{ request('brand_id') == $key ? 'selected' : '' }}>
                                                                {{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    @php
                                                        $status = ['0' => 'Inactive', '1' => 'Active'];
                                                    @endphp
                                                    <select name="status" class="chosen-select form-control"
                                                        data-placeholder="-Select Status-">
                                                        <option value=""></option>

                                                        @foreach ($status as $key => $item)
                                                            <option value="{{ $key }}">
                                                                {{ $item }}
                                                            </option>
                                                        @endforeach
                                                    </select>
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
                                                <th>Name</th>
                                                <th>Image</th>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>Brand</th>
                                                <th>Unit</th>
                                                <th>Barcode</th>
                                                <th class="text-right">Purchase Price</th>
                                                <th class="text-right">Sell Price</th>
                                                <th class="text-right">VAT % </th>

                                                <th>Created At </th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($products as $key => $item)
                                                <tr>
                                                    <td>{{ $products->firstItem() + $key }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>
                                                        @if (file_exists($item->image))
                                                            <img src="{{ asset($item->image) }}" width="100" height="100"
                                                                alt="{{ $item->name }}">
                                                        @endif
                                                    </td>
                                                    <td>{{ optional($item->category)->name }}</td>
                                                    <td>{{ $item->product_type == 1 ? "Finish" : ($item->product_type == 2 ? "Raw Materials" : '') }}</td>
                                                    <td>{{ optional($item->brand)->name }}</td>
                                                    <td>{{ optional($item->unit)->name }}</td>
                                                    <td class="text-right">{{ $item->barcode }}</td>
                                                    <td class="text-right">
                                                        {{ number_format($item->purchase_price, 2) }}</td>
                                                    <td class="text-center">
                                                        {{ number_format($item->sell_price, 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ number_format($item->vat, 2) }}
                                                    </td>

                                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-corner">

                                                            <!-- show -->
                                                            <a href="{{ route('dokani.products.show', $item->id) }}"
                                                                role="button" class="btn btn-xs btn-primary" title="Edit">
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            <!-- Barcode Show -->
                                                            @if (hasPermission('dokani.product-barcode.index', $slugs))
                                                                <a href="{{ route('dokani.product-barcode.print', $item->id) }}"
                                                                    role="button" class="btn btn-xs btn-info" title="Edit">
                                                                    <i class="fa fa-barcode"></i>
                                                                </a>
                                                            @endif

                                                            <!-- edit -->
                                                            @if (hasPermission('dokani.products.edit', $slugs))
                                                                <a href="{{ route('dokani.products.edit', $item->id) }}"
                                                                    role="button" class="btn btn-xs btn-success" title="Edit">
                                                                    <i class="fa fa-pencil-square-o"></i>
                                                                </a>
                                                            @endif

                                                            <!-- delete -->
                                                            @if (hasPermission('dokani.products.delete', $slugs))
                                                                <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.products.destroy', $item->id) }}`)"
                                                                    class="btn btn-xs btn-danger" title="Delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            @endif
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

                                    @include('partials._paginate', ['data' => $products])

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
