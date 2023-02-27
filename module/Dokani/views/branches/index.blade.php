@extends('layouts.master')


@section('title', 'Branches')

@section('page-header')
    <i class="fa fa-info-circle"></i> Branches
@stop



@push('style')

    <style>


        article {
            position: relative;
            width: 115px;
            height: 33px;
            float: left;
            border: 2px solid #428BCA;
            box-sizing: border-box;
            border-radius: 5px;
        }

        article div {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            line-height: 25px;
            transition: .5s ease;
        }

        article input {
            position: absolute;
            top: 0;
            left: 0;
            width: 140px;
            height: 100px;
            opacity: 0;
            cursor: pointer;
        }

        input[type=checkbox]:checked ~ div {
            background-color: #428BCA;
            border-radius: 5px;

        }

        input[type=checkbox]:checked ~ div b {
            color: white;
        }
        input[type=checkbox]:checked ~ div b:before {
            content: "✓";
        }



    </style>

@endpush


@section('content')

    <div class="row">
        <div class="col-md-12">


            @include('partials._alert_message')




            <div class="widget-box">


                <!-- header -->
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">
                        @if (hasPermission('dokani.branches.create', $slugs))
                            <a href="{{ route('dokani.branches.create') }}" class="">
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
                                                    @php
                                                        $status = ['0' => 'Inactive', '1' => 'Active'];
                                                    @endphp
                                                    <select name="status" class="chosen-select form-control"
                                                        data-placeholder="-Select Status-">
                                                        <option value=""></option>

                                                        @foreach ($status as $key => $item)
                                                            <option value="{{ $key }}">
                                                                {{ $item }}
                                                            </option>
                                                        @endforeach
                                                    </select>
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
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th class="text-right">Address</th>
                                                <th>Created At </th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($branches as $key => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="name">{{ $item->name }}</td>
                                                    <td class="mobile">{{ $item->phone_number }}</td>
                                                    <td>{{ $item->address }}</td>

                                                    <td>
                                                        {{ \Carbon\Carbon::parse($item->created_at)->format('F d, Y h:i s A') }}
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="btn-group btn-corner">

                                                            <!-- edit -->
                                                            @if (hasPermission('dokani.branches.edit', $slugs))
                                                                <a href="{{ route('dokani.branches.edit', $item->id) }}"
                                                                    role="button" class="btn btn-sm btn-success" title="Edit">
                                                                    <i class="fa fa-pencil-square-o"></i>
                                                                </a>
                                                            @endif

                                                            <!-- delete -->
                                                            @if (hasPermission('dokani.branches.delete', $slugs))
                                                                <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.branches.destroy', $item->id) }}`)"
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

                                    @include('partials._paginate',['data'=> $branches])

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




        function checkAll(object) {
            if ($(object).is(':checked')) {

                $('#acc').prop('checked', false)
                $('.is_check').val(0);
                $('.ace-switch-7').each(function() {
                    $(this).prop('checked', true)
                })
            } else {
                $('.ace-switch-7').each(function() {
                    $(this).prop('checked', false)
                })

            }
        }
        $(document).ready(function() {
            $('.is_check').val(1);
        })
        function allSupplier() {

            if (acc.checked == 1){
                $('.is_check').val(1);
            } else {
                $('.is_check').val(0);
            }
        }


        function storeTblValues() {
            var TableData = new Array();

            if ($('.ace-switch-7:checked')){
                $('#acc').prop('checked', false)
                $('.is_check').val(0);
            }

            $('.ace-switch-7:checked').each(function(row) {
                var that = $(this)
                TableData[row] = {
                    "name": that.closest('tr').find('.name').text(),
                    "mobile": that.closest('tr').find('.mobile').text(),
                    "supplier_id": parseInt(that.closest('tr').find('#supplier_id').val()),
                }
            })
            var obj = JSON.stringify(TableData)
            console.log(obj)
            $('#all').val(obj)
        }






    </script>

@stop
