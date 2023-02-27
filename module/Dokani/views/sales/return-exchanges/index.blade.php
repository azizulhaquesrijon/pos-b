@extends('layouts.master')
@section('title', 'Return Exchange List')
@section('page-header')
    <i class="fa fa-list"></i> Return Exchange List
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .dataTables_wrapper .row:first-child {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
            background-color: #EFF3F8;
        }
    </style>
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
                                <a href="{{ request()->url() }}" class="dt-button btn btn-white btn-primary btn-bold"
                                    title="Refresh Data" data-toggle="tooltip">
                                    <span>
                                        <i class="fa fa-refresh bigger-110"></i>
                                    </span>
                                </a>
                                @if (hasPermission('dokani.sale-return-exchanges.create', $slugs))
                                    <a href="{{ route('dokani.sale-return-exchanges.create') }}"
                                        class="dt-button btn btn-white btn-info btn-bold" title="Create New"
                                        data-toggle="tooltip" tabindex="0" aria-controls="dynamic-table">
                                        <span>
                                            <i class="fa fa-plus bigger-110"></i>
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="space"></div> --}}
                 <!-- Searching -->
                 <div class="row">
                    <div class="col-sm-12">
                         <form action="">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="15%">
                                            <input type="text" name="name" value="{{ request('name') }}" class="form-control input-sm" placeholder="Customer Name">
                                        </td>
                                        <td width="15%">
                                            <input type="text" name="invoice_no" value="{{ request('invoice_no') }}"
                                                   class="form-control input-sm" placeholder="Invoice No">
                                        </td>
                                        {{-- <td>
                                            <select name="courier_id" id="" class="form-control chosen-select" data-placeholder="-- Select Courier --">
                                                <option></option>
                                                @foreach ($couriers as $courier)
                                                    <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                                @endforeach
                                            </select>
                                        </td> --}}
                                        @if (hasBranchSystem())
                                        <td width="15%">
                                            <select name="branch_id" id="" class="form-control chosen-select" data-placeholder="-- Select Branch --">
                                                <option value="">All</option>
                                                <option value="Main" {{  request()->branch_id == "Main" ? 'selected' : ''}}>Main Branch</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ old('branch_id', $branch->id)}}" {{ request('branch_id') == $branch->id ? 'selected' : '' }} >{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        @endif

                                        <td width="20%">
                                            <div class="input-group">
                                                <input type="text" name="from_date" value="{{ request('from_date') }}"
                                                   class="form-control date-picker" placeholder="From Date" autocomplete="off">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-exchange"></i>
                                                </span>
                                                <input type="text" class="form-control date-picker" placeholder="To Date"
                                                       name="to_date" value="{{ request('to_date', date('Y-m-d')) }}" id="" autocomplete="off"/>
                                            </div>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="{{ hasBranchSystem() ? 5 : 4 }}" style="text-align: center;">
                                            <div class="btn-group btn-corner">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa fa-search"></i>Search
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

                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12 px-2">
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-header-bg">
                                    <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                    <th class="pl-3" style="color: white !important;">invoice</th>
                                    <th class="pl-3" style="color: white !important;">Date</th>
                                    <th class="pl-3" style="color: white !important;">Sale Invoice</th>
                                    <th class="pl-3" style="color: white !important;">Amount</th>

                                    <th class="text-center" style="color: white !important;" width="15%">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($saleReturnExchanges as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="pl-3">{{ $item->invoice_no }}</td>
                                        <td class="pl-3">{{ $item->date }}</td>
                                        <td class="pl-3">{{ $item->sale->invoice_no }}</td>
                                        <td class="pl-3">{{ number_format($item->subtotal, 2, '.', '') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">

                                                @if (hasPermission('dokani.sale-return-exchanges.show', $slugs))
                                                    <a href="{{ route('dokani.sale-return-exchanges.show', ['sale_return_exchange' => $item->id, 'print_type' => 'normal-print']) }}"
                                                        class="btn btn-primary btn-xs" target="_blank">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('dokani.sale-return-exchanges.show', $item->id) }}"
                                                       class="btn btn-warning btn-xs" target="_blank">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                @endif

                                                @if (hasPermission('dokani.sale-return-exchanges.delete', $slugs))
                                                <!-- delete -->
                                                <button type="button" onclick="delete_item(`{{ route('dokani.sale-return-exchanges.destroy', $item->id) }}`)" class="btn btn-xs btn-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
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
                "pageLength": 25,
                "searching": false
            });
        })
    </script>
@endsection
