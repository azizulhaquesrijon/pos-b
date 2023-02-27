@extends('layouts.master')

@section('title', 'G Party')


@section('content')


    @include('accounts.parties.add-modal')


    @include('accounts.parties.edit-modal')



    <div class="row">


        <div class="col-sm-12">
            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title">
                        <i class="fa fa-info-circle"></i> Account Party
                    </h4>

                    <span class="widget-toolbar">
                        @if (hasPermission('dokani.ac.parties.store', $slugs))
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

                                <table class="table table-striped table-bordered" id="data-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Sl</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Address</th>
                                            <th>Created At </th>
                                            <th>Updated At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse ($categories as $key => $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->mobile }}</td>
                                                <td>{{ $item->address }}</td>
                                                <td>{{ $item->created_at->format('F d, Y') }}</td>
                                                <td>{{ $item->updated_at->format('F d, Y') }}</td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-corner">
                                                        @if (hasPermission('dokani.ac.parties.edit', $slugs))
                                                            <a href="#edit-modal" role="button"
                                                                onclick="editItem(`{{ $item }}`)" data-toggle="modal"
                                                                class="btn btn-sm btn-success" title="Edit">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a>
                                                        @endif
                                                        @if (hasPermission('dokani.ac.parties.delete', $slugs))
                                                            <button type="button"
                                                                onclick="delete_item(`{{ route('dokani.ac.parties.destroy', $item->id) }}`)"
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
    <script src="{{ asset('admin/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/assets/custom_js/custom-datatable.js') }}"></script>

    <script>
        const name = $('.edit-name')
        const mobile = $('.edit-mobile')
        const address = $('.edit-address')
        const type = $('.edit-type')
        const edit_from = $('#edit_form')
        const update_route = `{{ route('dokani.ac.parties.update', ':id') }}`

        function editItem(item) {
            let chart = JSON.parse(item)
            edit_from.attr('action', update_route.replace(':id', chart.id))
            name.val(chart.name)
            mobile.val(chart.mobile)
            address.val(chart.address)
            type.val(chart.type).attr('selected', true).trigger('chosen:updated')
        }
    </script>

@stop
