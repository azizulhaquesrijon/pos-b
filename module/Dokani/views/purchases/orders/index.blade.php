@extends('layouts.master')
@section('title', 'Orders List')
@section('page-header')
    <i class="fa fa-list"></i> Purchase Orders List
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

                                @if ((hasPermission("dokani.orders.create", $slugs)))
                                    <a href="{{route('dokani.purchase-orders.create')}}" class="dt-button btn btn-white btn-info btn-bold" title="Create New" data-toggle="tooltip" tabindex="0" aria-controls="dynamic-table">
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
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr class="table-header-bg">
                                <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                <th class="pl-3" style="color: white !important;" >Invoice ID</th>
                                <th class="pl-3" style="color: white !important;" >Date</th>
                                <th class="pl-3" style="color: white !important;" >Supplier</th>
                                <th class="pl-3" style="color: white !important;" >Payable Amount</th>
                                <th class="pl-3" style="color: white !important;" >Paid Amount</th>
                                <th class="pl-3" style="color: white !important;" >Due</th>
                                <th class="pl-3" style="color: white !important;" >Change</th>
                                <th class="text-center" style="color: white !important;" width="15%">Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($orders as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="pl-3">{{ $item->invoice_no }}</td>
                                    <td class="pl-3">{{ $item->date }}</td>
                                    <td class="pl-3">{{ $item->supplier->name }}</td>
                                    <td class="pl-3">{{ number_format($item->total_payable,2) }}</td>
                                    <td class="pl-3">{{ number_format($item->paid_amount,2) }}</td>
                                    <td class="pl-3">{{ number_format($item->total_payable - $item->paid, 2) }}</td>
                                    <td class="pl-3">{{ number_format($item->change_amount,2) }}</td>
                                    <td class="text-center">
                                        @if($item->purchase_id == null)
                                            <div class="btn-group btn-corner">
    {{--                                            @include('partials._user-log', ['data' => $item])--}}

                                                @if ((hasPermission("dokani.orders.edit", $slugs)))
                                                    @if($item->purchase_id == null)
                                                        {{-- <a href="{{ route('dokani.purchase-orders.edit',$item->id) }}" class="btn btn-primary btn-xs" title="Purchase Order">
                                                            <i class="fa fa-arrow-right"></i>
                                                        </a> --}}
                                                    @endif
                                                @endif
                                                @if ((hasPermission("dokani.orders.approve", $slugs)))
                                                    @if($item->purchase_id == null)
                                                        <a href="{{ route('dokani.purchase-orders.show', $item->id . '?is_approved=1') }}" class="btn btn-primary btn-xs" title="Approve">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                                @if ((hasPermission("dokani.orders.show", $slugs)))
                                                    <a href="{{ route('dokani.purchase-orders.show',$item->id) }}" class="btn btn-primary btn-xs" title="Order Details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endif

                                                @if ((hasPermission("dokani.purchase-orders.destroy", $slugs)))
                                                    @if($item->purchase_id == null)
                                                    <a href="#" onclick="delete_item('{{ route('dokani.purchase-orders.destroy', $item->id) }}')" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></a>
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            @if ((hasPermission("dokani.orders.show", $slugs)))
                                            <a href="{{ route('dokani.purchase-orders.show',$item->id) }}">Completed</a>
                                            @endif
                                        @endif
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



