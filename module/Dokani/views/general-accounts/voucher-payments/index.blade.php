@extends('layouts.master')


@section('title', ucfirst(request('type')) . ' Info')

@section('page-header')
    <i class="fa fa-info-circle"></i> {{ ucfirst(request('type')) }} Info
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
                        @if (hasPermission('dokani.voucher-payments.store', $slugs))
                            <a href="{{ route('dokani.voucher-payments.create', ['type' => request('type')]) }}"
                                class="">
                                <i class="fa fa-plus"></i> Add New
                            </a>
                        @endif
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
                                                <th>Date</th>
                                                <th>Party Name</th>
                                                <th>Account</th>
                                                <th>Total Amount</th>

                                                <th>Created At </th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($voucherPayments as $key => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ optional($item->party)->name }}</td>
                                                    <td>
                                                        {{ optional($item->account)->name }}
                                                    </td>
                                                    <td>{{ number_format($item->total_amount, 2) }}</td>


                                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-corner">

                                                            <!-- show -->
                                                            <a href="{{ route('dokani.voucher-payments.show',$item->id) }}"
                                                                role="button" class="btn btn-sm btn-primary" title="Edit">
                                                                <i class="fa fa-eye"></i>
                                                            </a>



                                                            <!-- delete -->
                                                            @if (hasPermission('dokani.voucher-payments.delete', $slugs))
                                                                <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.voucher-payments.destroy', $item->id) }}`)"
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

                                    @include('partials._paginate', ['data' => $voucherPayments])

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
