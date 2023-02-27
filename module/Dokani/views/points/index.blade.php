@extends('layouts.master')


@section('title', 'Points')

@section('page-header')
    <i class="fa fa-info-circle"></i> Points
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
                        @if (hasPermission('dokani.points.create', $slugs))
                            <a href="{{ route('dokani.points.create') }}" class="">
                                <i class="fa fa-plus"></i> Add New
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Searching -->
{{--                        <div class="row">--}}
{{--                            <div class="col-sm-12">--}}
{{--                                <form action="">--}}
{{--                                    <table class="table table-bordered">--}}
{{--                                        <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td>--}}
{{--                                                <input type="text" name="name" value="{{ request('name') }}"--}}
{{--                                                       class="form-control input-sm" placeholder="Name">--}}
{{--                                            </td>--}}

{{--                                            <td>--}}
{{--                                                @php--}}
{{--                                                    $status = ['0' => 'Inactive', '1' => 'Active'];--}}
{{--                                                @endphp--}}
{{--                                                <select name="status" class="chosen-select form-control"--}}
{{--                                                        data-placeholder="-Select Status-">--}}
{{--                                                    <option value=""></option>--}}

{{--                                                    @foreach ($status as $key => $item)--}}
{{--                                                        <option value="{{ $key }}">--}}
{{--                                                            {{ $item }}--}}
{{--                                                        </option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                            </td>--}}
{{--                                            <td>--}}
{{--                                                <div class="btn-group btn-corner">--}}
{{--                                                    <button type="submit" class="btn btn-sm btn-success">--}}
{{--                                                        <i class="fa fa-search"></i> Search--}}
{{--                                                    </button>--}}
{{--                                                    <a href="{{ request()->url() }}" class="btn btn-sm">--}}
{{--                                                        <i class="fa fa-refresh"></i>--}}
{{--                                                    </a>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </form>--}}
{{--                            </div>--}}

{{--                        </div>--}}

                        <div class="row">
                            <div class="col-xs-12">

                                <div class="table-responsive" style="border: 1px #cdd9e8 solid;">

                                    <!-- Table -->
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Start Price</th>
                                            <th>End Price</th>
                                            <th>Point</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($points as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{-- {!! $item->image($item) !!} --}}
                                                    {{ $item->start_price }}
                                                </td>
                                                <td>{{ $item->end_price }}</td>
                                                <td>{{ $item->point }}</td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-corner">


                                                        <!-- edit -->
                                                        @if (hasPermission('dokani.points.edit', $slugs))
                                                            <a href="{{ route('dokani.points.edit', $item->id) }}"
                                                               role="button" class="btn btn-sm btn-success" title="Edit">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a>
                                                        @endif

                                                    <!-- delete -->
                                                        @if (hasPermission('dokani.points.delete', $slugs))
                                                            <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.points.destroy', $item->id) }}`)"
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

                                    <div class="row" style="background: #c8cbcac7;width: 100%;height: 30px; color: white; font-size: 16px;margin-bottom: 20px;">
                                        <div class="col-md-3">
                                            <i class="fa fa-sticky-note" style="padding-left: 15px; color: #031599"></i>
                                            <span style="color: #0400ff">NB: The value of per point is <strong>{{ optional($point)->point_value ?? 0 }} </strong></span>
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('dokani.point.value.edit', optional($point)->id ?? 0)  }}" method="POST">
                                                @csrf
                                                <a href="javascript:void(0)" class="point_value-btn">
                                                    <i class="fa fa-edit"></i> edit</a>
                                                <input type="hidden" style="
                                        padding: 2px;
                                        border-radius: 6px;
                                        font-size: 16px;
                                        border: 2px solid gray;
                                        color: #125ca7;
                                        width: 20%;
                                        height: 12%;
                                        font-weight: bold;" name="point_value" class="point_value-input text-center form-control" value="{{optional($point)->point_value ?? 0 }}">
                                            </form>
                                        </div>
                                    </div>
                                    @include('partials._paginate',['data'=> $points])

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


        $('.point_value-btn').click(function() {

            let root = $(this).closest('div')

            root.find('.point_value-input').attr('type', 'text').focus()

            $(this).css({"display" : "none"})
        });


        $('.point_value-input').blur(function() {

            let root = $(this).closest('div')

            let old_value = '{{ $point->point_value ?? 0 }}'
            let new_value = $(this).val()

            if(old_value != new_value) {
                root.find('form').submit()
            } else {
                root.find('.point_value-btn').css({"display" : "block"})
                $(this).attr('type', 'hidden')
            }

        })


    </script>

@stop

