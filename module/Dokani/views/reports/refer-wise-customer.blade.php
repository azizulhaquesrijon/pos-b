@extends('layouts.master')


@section('title', 'Refer Wise Customer Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Refer Wise Customer Report
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Searching -->
                        <div class="row">
                            <div class="col-sm-12">
                                <form action="">
                                    <table class="table table-bordered">
                                        <tr>

                                            <td class="row">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <select name="customer_id" class="select2 form-control"
                                                                data-placeholder="-Select Customer-" required>
                                                            <option value=""></option>

                                                            @foreach ($customers as $item)
                                                                <option value="{{ $item->id }}" {{ request('customer_id') == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if($areas->count() > 0)
                                                        <div class="col-md-3">
                                                            <select name="cus_area_id" class="select2 form-control"
                                                                    data-placeholder="-Select Customer Area-">
                                                                <option value=""></option>

                                                                @foreach ($areas as $item)
                                                                    <option value="{{ $item->id }}" {{ request('cus_area_id') == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                    @if($categories->count() > 0)
                                                        <div class="col-md-3">
                                                            <select name="cus_category_id" class="select2 form-control"
                                                                    data-placeholder="-Select Customer Category-">
                                                                <option value=""></option>

                                                                @foreach ($categories as $item)
                                                                    <option value="{{ $item->id }}" {{ request('cus_category_id') == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-3">
                                                        <div class="input-daterange input-group">

                                                            <input type="text" name="from" class="form-control date-picker" value="{{ request('from') }}"
                                                                   autocomplete="off" placeholder="From" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                            <span class="input-group-addon">
                                                            <i class="fa fa-exchange"></i>
                                                        </span>
                                                            <input type="text" name="to" class="form-control date-picker" value="{{ request('to') }}"
                                                                   autocomplete="off" placeholder="To" style="cursor: pointer" data-date-format="yyyy-mm-dd">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="btn-group btn-corner" style="float: right">
                                                    <button type="submit" class="btn btn-xs btn-success">
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                    <a href="{{ request()->url() }}" class="btn btn-xs">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
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
                                            <th>Customer</th>
                                            <th>Refer By</th>
                                        </tr>
                                        </thead>

                                        <tbody>


                                        @forelse ($refer_customers as $key => $item)

                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                                                <td><a href="{{ route('dokani.reports.refer-wise-customer.details', $item->id) }}"
                                                       title="Show Details">{{ $item->name }}</a></td>
                                                <td>
                                                    @if($item->refer_by_user_id == null )

                                                        {{ $item->refer_by_customer_id == null ? '' : 'Customer - '.optional($item->refer_customer)->name }}
                                                    @else
                                                        {{ $item->refer_by_user_id == null ? '' : 'User - '.optional($item->refer_user)->name }}
                                                    @endif
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
{{--                                            <th colspan="3"><strong>Total Amount:</strong></th>--}}
{{--                                            <th class="text-right">--}}
{{--                                                {{ $total_sale_amount }}--}}
{{--                                            </th>--}}
{{--                                            <th class="text-right">--}}
{{--                                                {{ $total_collection_amount }}--}}
{{--                                            </th>--}}
{{--                                        </tr>--}}
{{--                                        </tfoot>--}}
                                    </table>

                                    @include('partials._paginate',['data'=> $refer_customers])

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

