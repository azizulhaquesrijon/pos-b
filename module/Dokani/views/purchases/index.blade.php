@extends('layouts.master')


@section('title', 'Purchase')

@section('page-header')
    <i class="fa fa-info-circle"></i> Purchase
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
                        @if (hasPermission('dokani.purchases.create', $slugs))
                            <a href="{{ route('dokani.purchases.create') }}" class="" target="_blank">
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
                            <div class="col-sm-12">
                                 <form action="">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="name" value="{{ request('name') }}"
                                                       class="form-control input-sm" placeholder="Name">
                                            </td>

                                            <td>
                                                <div class="input-group">
                                                    <input type="text" name="from_date" value="{{ request('from_date') }}"
                                                           class="form-control date-picker" placeholder="From Date" autocomplete="off">
                                                    <span class="input-group-addon">
                                                            <i class="fa fa-exchange"></i>
                                                        </span>
                                                    <input type="text" class="form-control date-picker" placeholder="To Date"
                                                           name="to_date" value="{{ request('to_date') }}" id="" autocomplete="off"/>
                                                </div>

                                            </td>

                                            <td>
                                                <input type="text" name="invoice_no" value="{{ request('invoice_no') }}"
                                                       class="form-control input-sm" placeholder="Invoice No">
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
                                                <th>Supplier</th>
                                                <th>Invoice</th>
                                                <th>Reference</th>
                                                <th>Date</th>
                                                <th>Purchase Qty</th>
                                                <th class="text-right">Total Amount</th>
                                                <th class="text-right">Discount</th>
                                                <th class="text-right">Paid Amount</th>
                                                <th class="text-right">Due</th>
                                                <th>Created By</th>
                                                <th>Updated By</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

{{--                                        @php--}}
{{--                                            $total_qty = 0;--}}
{{--                                            $total_amount = 0;--}}
{{--                                            $total_paid_amount = 0;--}}
{{--                                            $total_due_amount = 0;--}}
{{--                                            $total_discount = 0;--}}
{{--                                            $grand_total_qty = 0;--}}


{{--                                            $grand_total_amount = $grans_purchases->sum('payable_amount');--}}
{{--                                            $grand_total_paid_amount = $grans_purchases->sum('paid_amount');--}}
{{--                                            $grand_total_due_amount = $grans_purchases->sum('due_amount');--}}
{{--                                            $grand_total_discount = $grans_purchases->sum('discount');--}}

{{--                                        foreach ($grans_purchases as $purchase){--}}

{{--                                            $grand_total_qty += $purchase->details->sum('quantity');--}}
{{--                                        }--}}
{{--                                        @endphp--}}

                                            @forelse ($purchases as $key => $item)


{{--                                                @php--}}

{{--                                                    $total_qty += $item->details->sum('quantity');--}}
{{--                                                    $total_amount += $item->payable_amount;--}}
{{--                                                    $total_paid_amount += $item->paid_amount;--}}
{{--                                                    $total_due_amount += $item->due_amount;--}}
{{--                                                    $total_discount += $item->discount;--}}

{{--                                                @endphp--}}


                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ optional($item->supplier)->name }}</td>
                                                    <td>{{ $item->invoice_no }}</td>
                                                    <td>{{ $item->reference_no }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}
                                                    </td>
                                                    <td class="text-center">{{ $item->details->sum('quantity') }}</td>
                                                    <td class="text-right">{{ number_format($item->payable_amount,2) }}
                                                    </td>
                                                    <td class="text-right">{{ number_format($item->discount,2) }}
                                                    </td>
                                                    <td class="text-right">{{ number_format($item->paid_amount,2) }}
                                                    </td>
                                                    <td class="text-right">{{ number_format($item->due_amount,2) }}
                                                    </td>

                                                    <td>{{ optional($item->created_user)->name }}</td>
                                                    <td>{{ optional($item->updated_user)->name }}</td>

                                                    <td class="text-center">
                                                        <div class="btn-group btn-corner">

                                                            <!-- Show -->
{{--                                                            <a href="{{ route('dokani.purchases.show', $item->id) }}"--}}
{{--                                                                role="button" class="btn btn-xs btn-success"--}}
{{--                                                                title="view Details">--}}
{{--                                                                <i class="fa fa-eye"></i>--}}
{{--                                                            </a>--}}

                                                            <!-- edit -->
                                                            {{-- <a href="{{ route('dokani.purchases.edit', $item->id) }}"
                                                                role="button" class="btn btn-xs btn-info" title="Edit">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a> --}}


                                                            <!-- Normal print -->
                                                            @if (hasPermission('dokani.purchases.show', $slugs))
                                                                <a href="{{ route('dokani.purchases.show', $item->id) }}"
                                                                    role="button" class="btn btn-xs btn-primary" title="Edit">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                            @endif

                                                            <!-- Pos Print -->
                                                            <a href="{{ route('dokani.purchases.show', $item->id) }}?type=pos-invoice"
                                                                target="_blank" role="button" class="btn btn-xs btn-purple"
                                                                title="Edit">
                                                                <i class="fa fa-print"></i>
                                                            </a>

                                                            <!-- delete -->
                                                            @if (hasPermission('dokani.purchases.delete', $slugs))
                                                                <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.purchases.destroy', $item->id) }}`)"
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

{{--                                        <tfoot>--}}
{{--                                        <tr>--}}
{{--                                            <th colspan="4"><strong>Total:</strong></th>--}}
{{--                                            <th class="text-center">--}}
{{--                                                {{ $total_qty }}--}}
{{--                                            </th>--}}
{{--                                            <th class="text-right">--}}
{{--                                                {{ $total_amount }}--}}
{{--                                            </th>--}}
{{--                                            <th class="text-right">--}}
{{--                                                {{ $total_discount }}--}}
{{--                                            </th>--}}
{{--                                            <th class="text-right">--}}
{{--                                                {{ $total_paid_amount }}--}}
{{--                                            </th>--}}
{{--                                            <th class="text-right">--}}
{{--                                                {{ $total_due_amount }}--}}
{{--                                            </th>--}}

{{--                                        </tr>--}}

{{--                                        <tr>--}}
{{--                                            <th colspan="4"><strong>Grand Total:</strong></th>--}}
{{--                                            <th class="text-center">--}}
{{--                                                {{ $grand_total_qty }}--}}
{{--                                            </th>--}}
{{--                                            <th class="text-right">--}}
{{--                                                {{ $grand_total_amount }}--}}
{{--                                            </th>--}}
{{--                                            <th class="text-right">--}}
{{--                                                {{ $grand_total_discount }}--}}
{{--                                            </th>--}}
{{--                                            <th class="text-right">--}}
{{--                                                {{ $grand_total_paid_amount }}--}}
{{--                                            </th>--}}
{{--                                            <th class="text-right">--}}
{{--                                                {{ $grand_total_due_amount }}--}}
{{--                                            </th>--}}
{{--                                        </tr>--}}

{{--                                        </tfoot>--}}
                                    </table>

                                    @include('partials._paginate', ['data' => $purchases])

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
