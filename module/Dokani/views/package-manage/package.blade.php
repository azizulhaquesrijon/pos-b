@extends('layouts.master')


@section('title', 'Package Type')

@section('page-header')
    <i class="fa fa-info-circle"></i> Package Type </span>
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
                                            <th>Package Type</th>
                                            <th>Package Month</th>
                                            <th>Package Price</th>
{{--                                            <th class="text-center">Action</th>--}}
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($packages as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td class="text-center">
                                                    <form action="{{ route('dokani.shop.package.month', $item->id) }}" method="POST">
                                                        @csrf

                                                        <span class="month-text package_edit" title="Click To Edit">{{ $item->months }}</span>
                                                        <input type="hidden" style="
                                        padding: 2px;
                                        border-radius: 6px;
                                        font-size: 16px;
                                        border: 2px solid gray;
                                        color: #125ca7;
                                        font-weight: bold;" id="month" name="months" class="month-input text-center form-control" value="{{ $item->months }}">
                                                    </form>
                                                </td>

                                                <td class="text-center">
                                                    <form action="{{ route('dokani.shop.package.price', $item->id) }}" method="POST">
                                                        @csrf

                                                        <span class="price-text package_edit" title="Click To Edit">{{ $item->price }}</span>
                                                        <input type="hidden" style="
                                        padding: 2px;
                                        border-radius: 6px;
                                        font-size: 16px;
                                        border: 2px solid gray;
                                        color: #125ca7;
                                        font-weight: bold;" id="price" name="price" class="price-input text-center form-control" value="{{ $item->price }}">
                                                    </form>
                                                </td>

{{--                                                <td class="text-center">--}}
{{--                                                    <div class="btn-group btn-corner">--}}

{{--                                                        <!-- show -->--}}
{{--                                                        <a href="{{ route('dokani.shop.edit', $item->id) }}"--}}
{{--                                                           role="button" class="btn btn-xs btn-primary" title="Edit">--}}
{{--                                                            <i class="fa fa-edit"></i>--}}
{{--                                                        </a>--}}

{{--                                                        <!-- delete -->--}}
{{--                                                        @if (hasPermission('dokani.products.delete', $slugs))--}}
{{--                                                            <a href="{{ route('dokani.shop.package') }}"--}}
{{--                                                               class="btn btn-xs btn-success" title="Package">--}}
{{--                                                                <i class="fa fa-list"></i>--}}
{{--                                                            </a>--}}
{{--                                                        @endif--}}
{{--                                                    </div>--}}
{{--                                                </td>--}}
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

                                    @include('partials._paginate', ['data' => $packages])

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

        $('.month-text').click(function() {

            let root = $(this).closest('td')

            root.find('.month-input').attr('type', 'text').focus()

            $(this).css({"display" : "none"})
        });

        $('.month-input').blur(function() {

            let root = $(this).closest('td')

            let old_value = root.find('.month-text').text()
            let new_value = $(this).val()

            if(old_value != new_value) {
                root.find('form').submit()
            } else {
                root.find('.month-text').css({"display" : "block"})
                $(this).attr('type', 'hidden')
            }

        })

        $('.price-text').click(function() {

            let root = $(this).closest('td')

            root.find('.price-input').attr('type', 'text').focus()

            $(this).css({"display" : "none"})
        });

        $('.price-input').blur(function() {

            let root = $(this).closest('td')

            let old_value = root.find('.price-text').text()
            let new_value = $(this).val()

            if(old_value != new_value) {
                root.find('form').submit()
            } else {
                root.find('.price-text').css({"display" : "block"})
                $(this).attr('type', 'hidden')
            }

        })


    </script>

@stop


