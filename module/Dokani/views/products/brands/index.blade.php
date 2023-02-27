@extends('layouts.master')

@section('title', 'Product Brands')


@section('content')


    @include('products.brands.add-modal')


    @include('products.brands.edit-modal')



    <div class="row">


        <div class="col-sm-12">
            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title">
                        <i class="fa fa-info-circle"></i> Product Brands
                    </h4>

                    <span class="widget-toolbar">
                        @if (hasPermission('dokani.brands.store', $slugs))
                            <a href="#add-new" role="button" data-toggle="modal">
                                <i class="ace-icon fa fa-plus"></i>
                                Create New
                            </a>
                        @endif
                    </span>
                </div>


                <div class="widget-body">
                    <div class="widget-main">


                        @include('partials._alert_message')

                        <div class="row">



                            <div class="col-sm-12">

                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Sl</th>
                                            <th>Name</th>
                                            <th>Created At </th>
                                            <th>Updated At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse ($brands as $key => $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->created_at->format('F d, Y h:i s A') }}</td>
                                                <td>{{ $item->updated_at->format('F d, Y h:i s A') }}</td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-corner">
                                                        @if (hasPermission('dokani.brands.update', $slugs))
                                                            <a href="#edit-modal" role="button"
                                                                onclick="editItem(`{{ $item }}`)" data-toggle="modal"
                                                                class="btn btn-sm btn-success" title="Edit">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a>
                                                        @endif


                                                        @if (hasPermission('dokani.brands.delete', $slugs))
                                                            <button type="button"
                                                                onclick="delete_item(`{{ route('dokani.brands.destroy', $item->id) }}`)"
                                                                class="btn btn-sm btn-danger" title="Delete">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('js')


    <!-- Datatable Script -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/custom-datatable.js') }}"></script>

    <script>
        const brand_name = $('.edit-name')
        const edit_from = $('#edit_form')
        const update_route = `{{ route('dokani.brands.update', ':id') }}`

        function editItem(item) {
            let brand = JSON.parse(item)

            edit_from.attr('action', update_route.replace(':id', brand.id))
            brand_name.val(brand.name)
        }
    </script>

@stop
