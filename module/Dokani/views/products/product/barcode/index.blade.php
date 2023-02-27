@extends('layouts.master')


@section('title', 'Products Barcode')

@section('page-header')
    <i class="fa fa-info-circle"></i> Products Barcode
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
                        <a href="{{ route('dokani.products.create') }}" class="">
                            <i class="fa fa-plus"></i> Add New
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
                                                <th>Barcode</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($products as $key => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->name }}</td>


                                                    <td class="text-center">
                                                        <div class="mb-3">{!! DNS1D::getBarcodeHTML($item->barcode ?? 123456, 'C128') !!}</div>
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="btn-group btn-corner">

                                                            <!-- show -->
                                                            <a href="{{ route('dokani.product-barcode.print', $item->id) }}"
                                                                role="button" class="btn btn-sm btn-info" title="Edit">
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            <!-- edit -->
                                                            <a href="{{ route('dokani.products.edit', $item->id) }}"
                                                                role="button" class="btn btn-sm btn-primary" title="Edit">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a>

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
