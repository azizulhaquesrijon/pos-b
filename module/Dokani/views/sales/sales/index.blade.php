@extends('layouts.master')
@section('title', 'Sales List')
@section('page-header')
    <i class="fa fa-list"></i> Sales List
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12">

            @include('partials._alert_message')

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{ request()->url() }}" class="dt-button btn btn-white btn-primary btn-bold" title="Refresh Data" data-toggle="tooltip">
                                    <span>
                                        <i class="fa fa-refresh bigger-110"></i>
                                    </span>
                                </a>

                                @if ((hasPermission("pos_erp.products.create", $slugs)))
                                    <a href="{{route('pos_erp.products.create')}}" class="dt-button btn btn-white btn-info btn-bold" title="Create New" data-toggle="tooltip" tabindex="0" aria-controls="dynamic-table">
                                        <span>
                                            <i class="fa fa-plus bigger-110"></i>
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space"></div>

                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12 px-2">
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-header-bg">
                                    <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                    <th class="pl-3" style="color: white !important;" >Invoice ID</th>
                                    <th class="pl-3" style="color: white !important;" >Date</th>
                                    <th class="pl-3" style="color: white !important;" >Customer</th>
                                    <th class="pl-3" style="color: white !important;" >Payable Amount</th>
                                    <th class="pl-3" style="color: white !important;" >Paid Amount</th>
                                    <th class="pl-3" style="color: white !important;" >Due</th>
                                    <th class="pl-3" style="color: white !important;" >Change</th>
                                    <th class="text-center" style="color: white !important;" width="15%">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($sales as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="pl-3">{{ $item->invoiceId }}</td>
                                        <td class="pl-3">{{ $item->sale_date->format('Y-m-d') }}</td>
                                        <td class="pl-3">{{ $item->customer->name }}</td>
                                        <td class="pl-3">{{ number_format($item->total_payable,2) }}</td>
                                        <td class="pl-3">{{ number_format($item->paid_amount,2) }}</td>
                                        <td class="pl-3">{{ number_format($item->total_payable - $item->paid, 2) }}</td>
                                        <td class="pl-3">{{ number_format($item->change_amount,2) }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('partials._user-log', ['data' => $item])

                                                @if ((hasPermission("pos_erp.products.edit", $slugs)))
                                                    <a href="{{route('pos_erp.sales.edit', $item->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i></a>
                                                @endif

                                                @if ((hasPermission("pos_erp.sales.delete", $slugs)))
                                                    <a href="#" onclick="delete_item('{{ route('pos_erp.sales.destroy', $item->id) }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>


    <script type="text/javascript">
        jQuery(function($) {
            $('#data-table').DataTable({
                "ordering": false,
                "bPaginate": true,
                "lengthChange": false,
                "info": false,
                "pageLength": 25
            });
        })
    </script>
@endsection


