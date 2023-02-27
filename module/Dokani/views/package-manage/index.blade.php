@extends('layouts.master')


@section('title', 'All Dokan')

@section('page-header')
    <i class="fa fa-info-circle"></i> All Dokan </span>
@stop


@section('css')

    <style>
        .package_edit{

            display: block;
            background: #eaffff;
            padding: 2px;
            border-radius: 6px;
            font-size: 16px;
            border: 2px solid #d7d7d7;
            color: #125ca7;
            font-weight: bold;
        }
    </style>

@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">
                        <a href="{{ route('dokani.shop.create') }}" class="">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </span>
                    <span class="widget-toolbar">
                        <a href="{{ route('dokani.shop.package') }}" class="">
                            <i class="fa fa-list"></i> Manage Package Type
                        </a>
                    </span>

                    <span class="widget-toolbar">
                        <a href="{{ route('dokani.shop.sms.manage') }}" class="">
                            <i class="fa fa-list"></i> Manage SMS
                        </a>
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Searching -->
                        <div class="row">
                            <div class="col-sm-12">
                                <form action="">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="name" value="{{ request('name') }}"
                                                       class="form-control input-sm" placeholder="Name">
                                            </td>

                                            <td>
                                                <input type="number" name="mobile" value="{{ request('mobile') }}"
                                                       class="form-control input-sm" placeholder="Mobile">
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
                                            <th>Shop Name</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Package Type</th>
                                            <th>Package Month</th>
                                            <th>Package Price</th>
                                            <th>Multiple Branch</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($dokans as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ optional($item->businessProfile)->shop_name }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->mobile }}</td>
                                                <td class="text-center">
                                                    <form action="{{ route('dokani.start_date.change', $item->id) }}" method="POST">
                                                        @csrf

                                                        <span class="start_date-text package_edit" title="Click To Edit">{{ optional($item->package)->start_date ?? 'Add' }}</span>
                                                        <input type="hidden" style="
                                        padding: 2px;
                                        border-radius: 6px;
                                        font-size: 16px;
                                        border: 2px solid gray;
                                        color: #125ca7;
                                        font-weight: bold;" id="date" name="start_date" class="start_date-input date-picker text-center form-control" value="{{ optional($item->package)->start_date }}">
                                                    </form>
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ route('dokani.end_date.change', $item->id) }}" method="POST">
                                                        @csrf

                                                        <span class="end_date-text package_edit" title="Click To Edit">{{ optional($item->package)->end_date ?? 'Add' }}</span>
                                                        <input type="hidden" style="
                                        padding: 2px;
                                        border-radius: 6px;
                                        font-size: 16px;
                                        border: 2px solid gray;
                                        color: #125ca7;
                                        font-weight: bold;" name="end_date" class="end_date-input date-picker text-center form-control" value="{{ optional($item->package)->end_date }}">
                                                    </form>
                                                </td>
                                                <td>{{ optional(optional($item->package)->type)->name }}</td>
                                                <td>{{ optional(optional($item->package)->type)->months }}</td>
                                                <td>{{ optional(optional($item->package)->type)->price }}</td>
                                                <td class="text-center">
                                                    @if ($item->is_multiple_branch)
                                                        <span class="label label-success arrowed-in arrowed-in-right">Yes</span>
                                                    @else
                                                        <span class="label label-danger arrowed-in arrowed-in-right">No</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-corner">

                                                        <!-- show -->
                                                        <a href="{{ route('dokani.shop.edit', $item->id) }}"
                                                           role="button" class="btn btn-xs btn-primary" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>

                                                    <!-- delete -->
{{--                                                        @if (hasPermission('dokani.products.delete', $slugs))--}}
{{--                                                            <a href="{{ route('dokani.shop.package') }}"--}}
{{--                                                                    class="btn btn-xs btn-success" title="Package">--}}
{{--                                                                <i class="fa fa-list"></i>--}}
{{--                                                            </a>--}}
{{--                                                        @endif--}}
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

                                    @include('partials._paginate', ['data' => $dokans])

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


    <script>

        $(document).ready(function() {
            payableSalary()
            $('#date').datepicker({
                format: "yyyy/mm",
                autoclose: true,
                todayHighlight: true,
                viewMode: "months",
                minViewMode: "months"
            })
        })


        $('.end_date-text').click(function() {

            let root = $(this).closest('td')

            root.find('.end_date-input').attr('type', 'text').focus()

            $(this).css({"display" : "none"})
        });

        $('.end_date-input').blur(function() {

            let root = $(this).closest('td')

            let old_value = root.find('.end_date-text').text()
            let new_value = $(this).val()

            if(old_value != new_value) {
                root.find('form').submit()
            } else {
                root.find('.end_date-text').css({"display" : "block"})
                $(this).attr('type', 'hidden')
            }

        })

        $('.start_date-text').click(function() {

            let root = $(this).closest('td')

            root.find('.start_date-input').attr('type', 'text').focus()

            $(this).css({"display" : "none"})
        });

        $('.start_date-input').blur(function() {

            let root = $(this).closest('td')

            let old_value = root.find('.start_date-text').text()
            let new_value = $(this).val()

            if(old_value != new_value) {
                root.find('form').submit()
            } else {
                root.find('.start_date-text').css({"display" : "block"})
                $(this).attr('type', 'hidden')
            }

        })

    </script>

@stop

