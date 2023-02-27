@extends('layouts.master')


@section('title', 'Sales')

@section('page-header')
    <i class="fa fa-info-circle"></i> Sales
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
                        @if(hasPermission('dokani.sales.create', $slugs))
                            <a href="{{ route('dokani.sales.create') }}" class="" target="_blank">
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
                                                    <input type="text" name="name" value="{{ request('name') }}" class="form-control input-sm" placeholder="Customer Name">
                                                </td>
                                                <td>
                                                    <select name="courier_id" id="" class="form-control chosen-select" data-placeholder="-- Select Courier --">
                                                        <option></option>
                                                        @foreach ($couriers as $courier)
                                                            <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                @if (hasBranchSystem())

                                                <td>
                                                    <select name="branch_id" id="" class="form-control chosen-select" data-placeholder="-- Select Branch --">
                                                        <option value="">All</option>
                                                        <option value="Main" {{  request()->branch_id == "Main" ? 'selected' : ''}}>Main Branch</option>
                                                        @foreach ($branches as $branch)
                                                            <option value="{{ old('branch_id', $branch->id)}}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>


                                                @endif

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

                                            </tr>
                                            <tr>
                                                <td colspan="{{ hasBranchSystem() ? 5 : 4 }}" style="text-align: center;">
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
                                            <th>Date</th>
                                            <th>Invoice</th>
                                            <th>Customer</th>
                                            <th class="text-right">Total Amount</th>
                                            <th class="text-right">Paid Amount</th>
                                            <th class="text-right">Change</th>
                                            <th class="text-right">Due</th>
                                            <th class="text-right">Total VAT</th>
                                            <th class="text-center">Courier</th>
                                            <th>Method</th>
                                            <th class="text-right">Source</th>
                                            <th>Created By</th>
                                            <th>Updated By</th>
                                            <th class="text-center" width="10%">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($sales as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($item->date)->format('F d, Y') }}
                                                </td>
                                                <td>{{ $item->invoice_no }}</td>
                                                <td>{{ optional($item->customer)->name }}</td>
                                                <td class="text-right">{{ number_format($item->payable_amount) }}
                                                </td>
                                                <td class="text-right">{{ number_format($item->paid_amount) }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($item->change_amount,1,'.','')}}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($item->due_amount,1,'.','') }}
                                                </td>
                                                <td class="text-right">{{ number_format($item->total_vat) }}
                                                </td>
                                                <td class="text-center">{{ optional($item->courier)->name ?? '-' }}
                                                </td>
                                                <td>
                                                    <span class="label {{ $item->is_cod == 1 ? 'label-success' : 'label-primary' }} arrowed-in arrowed-in-right">{{ $item->is_cod == 1 ? 'Cash on delivery' : 'Cash on hand' }}</span>
                                                </td>
                                                <td class="text-right">
                                                    @if ($item->source)
                                                    {{ $item->source }}
                                                    @else
                                                    {{ $item->sale_from }}
                                                    @endif
                                                </td>
                                                <td>{{ optional($item->created_user)->name }}</td>
                                                <td>{{ optional($item->updated_user)->name }}</td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-corner">

                                                    @if (hasPermission('dokani.sales.show', $slugs))
                                                        <!-- Show -->
                                                        <a href="{{ route('dokani.sales.show', $item->id) }}"
                                                            role="button" class="btn btn-xs btn-success"
                                                            title="view Details">
                                                            <i class="fa fa-eye"></i>
                                                        </a>

                                                        <!-- Pos Print -->
                                                        <a href="{{ route('dokani.sales.show', $item->id) }}?type=pos-invoice"
                                                            role="button" class="btn btn-xs btn-purple"
                                                            title="Pos Print" target="_blank">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                    @endif
{{--                                                        @if (hasPermission('dokani.customers.edit', $slugs))--}}
{{--                                                            <a href="{{ route('dokani.sales.edit',$item->id) }}"--}}
{{--                                                               role="button" class="btn btn-xs btn-warning" title="Edit">--}}
{{--                                                                <i class="fa fa-pencil-square-o"></i>--}}
{{--                                                            </a>--}}
{{--                                                        @endif--}}

                                                    <!-- edit -->
                                                    {{-- <a href="{{ route('dokani.sales.edit', $item->id) }}"
                                                        role="button" class="btn btn-xs btn-info" title="Edit">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a> --}}


                                                    {{-- <!-- Normal print -->
                                                    <a href="{{ route('dokani.sales.show', $item->id) }}"
                                                        role="button" class="btn btn-xs btn-primary" title="Edit">
                                                        <i class="fa fa-print"></i>
                                                    </a> --}}



                                                    @if (hasPermission('dokani.sales.delete', $slugs))
                                                            <!-- delete -->
                                                            <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.sales.destroy', $item->id) }}`)"
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

                                    @include('partials._paginate', ['data' => $sales])

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
