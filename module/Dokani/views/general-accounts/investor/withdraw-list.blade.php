@extends('layouts.master')


@section('title', 'Withdraw List')

@section('page-header')
    <i class="fa fa-info-circle"></i> Withdraw List
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
                        @if (hasPermission('dokani.ac.investor.withdraw', $slugs))
                            <a href="{{ route('dokani.ac.investor.withdraw') }}" class="">
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
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <select name="investor_id" id="investor" class="form-control chosen-select"
                                                        data-placeholder="-- Select Investor --">
                                                    <option></option>
                                                    @foreach($investors as $item)
                                                        <option value="{{ $item->id }}" data-balance="{{ $item->balance }}">
                                                            {{ optional($item->g_party)->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <div class="btn-group btn-corner">
                                                    <button type="submit" class="btn btn-xs btn-success">
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                    <a href="{{ request()->url() }}" class="btn btn-xs">
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
                                            <th>Balance</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($withdraws as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ optional(optional($item->investor)->g_party)->name }}</td>
                                                <td>{{ $item->amount }}</td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-corner">


                                                        <!-- edit -->
                                                        @if (hasPermission('dokani.ac.investor.withdraw.show', $slugs))
                                                            <a href="{{ route('dokani.ac.investor.withdraw.show', $item->id) }}"
                                                               role="button" class="btn btn-sm btn-primary" title="Show">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        @endif

                                                    <!-- delete -->
                                                        @if (hasPermission('dokani.ac.investor.withdraw.delete', $slugs))
                                                            <button type="button"
                                                                    onclick="delete_item(`{{ route('dokani.ac.investor.withdraw.delete', $item->id) }}`)"
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

                                    @include('partials._paginate',['data'=> $withdraws])

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

