@extends('layouts.master')


@section('title', 'Production Issues')

@section('page-header')
    <i class="fa fa-info-circle"></i> Production Issues
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
                        @if (hasPermission('dokani.production-receives.create', $slugs))
                            <a href="{{ route('dokani.production-receives.create') }}" class="">
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
                                            <th>Invoice</th>
                                            <th>Date</th>
                                            <th>Total Amount</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($production_issue as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $item->invoice_id }}
                                                </td>
                                                <td>{{ fdate($item->date,'Y-m-d') }}</td>
                                                <td>{{ number_format($item->total_amount,2,'.',' ') }}</td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-corner">


                                                        <!-- SHow -->
                                                        @if (hasPermission('dokani.production-receives.show', $slugs))
                                                            <a href="{{ route('dokani.production-receives.show', $item->id) }}"
                                                                role="button" class="btn btn-sm btn-info" title="Edit">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        @endif

                                                        <!-- edit -->
                                                        {{-- @if (hasPermission('dokani.production-receives.edit', $slugs))
                                                            <a href="{{ route('dokani.production-receives.edit', $item->id) }}"
                                                                role="button" class="btn btn-sm btn-success" title="Edit">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a>
                                                        @endif --}}

                                                        <!-- delete -->
                                                        @if (hasPermission('dokani.production-receives.delete', $slugs))
                                                            <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.production-receives.destroy', $item->id) }}`)"
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

                                    @include('partials._paginate',['data'=> $production_issue])

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



    </script>

@stop


