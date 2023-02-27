@extends('layouts.master')


@section('title', 'Fund Transfer')

@section('page-header')
    <i class="fa fa-info-circle"></i> Fund Transfer
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
                        @if (hasPermission('dokani.ac.fund-transfers.create', $slugs))
                            <a href="{{ route('dokani.ac.fund-transfers.create') }}" class="">
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
                                                <th>From Account</th>
                                                <th>To Account</th>
                                                <th>Amount</th>
                                                <th>Description</th>

                                                <th>Created At </th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($fundTransfers as $key => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{!! optional($item->from_account)->name !!}</td>
                                                    <td>{!! optional($item->to_account)->name !!}</td>
                                                    <td>{{ number_format($item->amount, 2) }}</td>
                                                    <td>{{ $item->description }}</td>

                                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-corner">

                                                            <!-- show -->
{{--                                                            <a href="{{ route('dokani.ac.fund-transfers.show', $item->id) }}"--}}
{{--                                                                role="button" class="btn btn-sm btn-primary" title="Edit">--}}
{{--                                                                <i class="fa fa-eye"></i>--}}
{{--                                                            </a>--}}



                                                            <!-- delete -->
                                                            <button type="button"
                                                                onclick="delete_item(`{{ route('dokani.ac.fund-transfers.destroy', $item->id) }}`)"
                                                                class="btn btn-sm btn-danger" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
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

                                    @include('partials._paginate', ['data' => $fundTransfers])

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
