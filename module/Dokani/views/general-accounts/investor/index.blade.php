@extends('layouts.master')


@section('title', 'Investors')

@section('page-header')
    <i class="fa fa-info-circle"></i> Investors
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
                        @if (hasPermission('dokani.ac.investors.create', $slugs))
                            <a href="{{ route('dokani.ac.investors.create') }}" class="">
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
                            <div class="col-md-12 col-md-offset-2">
                                <form action="">
                                    <div class="row py-2">
                                        <div class="col-md-8">
                                            <input type="text" name="name" value="{{ request('name') }}"
                                                   class="form-control input-sm" placeholder="Investor Name">
                                        </div>

                                        <div class="col-md-4">
                                            <div class="btn-group btn-corner">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                                <a href="{{ request()->url() }}" class="btn btn-sm">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
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
                                            <th>Address</th>
                                            <th>Note</th>
                                            <th>Balance</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($investors as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ optional($item->g_party)->name }}
                                                </td>
                                                <td>{{ optional($item->g_party)->mobile }}</td>
                                                <td>{{ optional($item->g_party)->address }}</td>
                                                <td>{{ $item->note }}</td>
                                                <td>{{ $item->balance }}</td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-corner">


                                                        <!-- edit -->
                                                        @if (hasPermission('dokani.ac.investor.balance.add', $slugs))
                                                            <a href="{{ route('dokani.ac.investor.balance.add', $item->id) }}"
                                                               role="button" class="btn btn-xs btn-primary" title="Add Balance">
                                                                <i class="ace-icon far fa-plus-circle fa-lg rotate"></i>
                                                            </a>
                                                        @endif

                                                    <!-- delete -->
                                                        @if (hasPermission('dokani.ac.investors.delete', $slugs))
                                                            <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.ac.investors.destroy', $item->id) }}`)"
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

                                    @include('partials._paginate',['data'=> $investors])

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

