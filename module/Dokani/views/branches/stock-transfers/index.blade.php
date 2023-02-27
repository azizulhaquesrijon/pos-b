@extends('layouts.master')


@section('title', 'Stock Transfer')

@section('page-header')
    <i class="fa fa-info-circle"></i> Stock Transfers <span class="badge badge-info">{{ $stockTransfers->total() }}</span>
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
                        @if (hasPermission('dokani.stock-transfers.create', $slugs))
                            <a href="{{ route('dokani.stock-transfers.create') }}" class="">
                                <i class="fa fa-plus"></i> Add New
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Searching -->
                        

                        <div class="row">
                            <div class="col-xs-12">

                                <div class="table-responsive" style="border: 1px #cdd9e8 solid;">

                                    <!-- Table -->
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>From Branch</th>
                                                <th>To Branch</th>
                                                <th>Date</th>
                                                <th>Total Quantity</th>
                                                <th>Total Amount</th>
                                                <th>Transfer Cost</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($stockTransfers as $key => $item)
                                                <tr>
                                                    <td>{{ $stockTransfers->firstItem() + $key }}</td>
                                                    <td>{{ optional($item->from_branch)->name ?? 'Head Office' }}</td>
                                                    <td>{{ optional($item->to_branch)->name }}</td>
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->total_quantity }}</td>
                                                    <td>{{ number_format($item->total_amount, 2, '.', '') }}</td>
                                                    <td>{{ number_format($item->transfer_cost, 2, '.', '') }}</td>
                                                   <td>
                                                    @if ($item->is_approved)
                                                        <span class="badge badge-success">Approved</span>
                                                    @else
                                                        <span class="badge badge-danger">Pending</span>
                                                    @endif
                                                   </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-corner">

                                                            <!-- show -->
                                                            <a href="{{ route('dokani.stock-transfers.show', $item->id) }}"
                                                                role="button" class="btn btn-xs btn-primary" title="Show">
                                                                <i class="fa fa-eye"></i>
                                                            </a>

                                                            <!-- Approved -->
                                                            @if (hasPermission('dokani.stock-transfers.approve', $slugs) && !$item->is_approved)
                                                                <a href="{{ route('dokani.stock-transfers.approve', $item->id) }}?is_approved=0"
                                                                    role="button" class="btn btn-xs btn-info" title="Approve">
                                                                    <i class="far fa-check"></i>
                                                                </a>
                                                            @endif

                                                            <!-- edit -->
                                                            @if (hasPermission('dokani.stock-transfers.edit', $slugs) && !$item->is_approved)
                                                                <a href="{{ route('dokani.stock-transfers.edit', $item->id) }}"
                                                                    role="button" class="btn btn-xs btn-success" title="Edit">
                                                                    <i class="fa fa-pencil-square-o"></i>
                                                                </a>
                                                            @endif

                                                            <!-- delete -->
                                                            @if (hasPermission('dokani.stock-transfers.delete', $slugs) && !$item->is_approved)
                                                                <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.stock-transfers.destroy', $item->id) }}`)"
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

                                    @include('partials._paginate', ['data' => $stockTransfers])

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
