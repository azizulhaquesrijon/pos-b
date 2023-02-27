@extends('layouts.master')


@section('title', 'Couriers')

@section('page-header')
    <i class="fa fa-info-circle"></i> Couriers
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
                        @if (hasPermission('dokani.couriers.create', $slugs))
                            <a href="{{ route('dokani.couriers.create') }}" class="">
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
                            <div class="col-sm-8 col-sm-offset-2">
                                <form action="">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="padding-right: 57px;">
                                                        Courier Name
                                                    </span>
                                                    <input type="text" name="name" value="{{ request('name') }}"
                                                           class="form-control input-sm" placeholder="Name">
                                                    @error('name')
                                                    <span class="text-danger" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
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
                                            <tr style="background: #c3c3c3; color:black">
                                                <th>Sl</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Address</th>
                                                <th>Created At </th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse ($couriers as $key => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        {{-- {!! $item->image($item) !!} --}}
                                                        {{ $item->name }}
                                                    </td>
                                                    <td>{{ $item->mobile }}</td>
                                                    <td>{{ $item->address }}</td>

                                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group btn-corner">


                                                            <!-- edit -->
                                                            @if (hasPermission('dokani.couriers.edit', $slugs))
                                                                <a href="{{ route('dokani.couriers.edit', $item->id) }}"
                                                                    role="button" class="btn btn-sm btn-success" title="Edit">
                                                                    <i class="fa fa-pencil-square-o"></i>
                                                                </a>
                                                            @endif

                                                            <!-- delete -->
                                                            @if (hasPermission('dokani.couriers.delete', $slugs))
                                                                <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.couriers.destroy', $item->id) }}`)"
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

                                    @include('partials._paginate',['data'=> $couriers])

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
