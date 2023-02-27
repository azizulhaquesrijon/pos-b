@extends('layouts.master')


@section('title', 'Product wise Profit')

@section('page-header')
    <i class="fa fa-info-circle"></i> Product wise Profit
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
                        {{-- @dd($categories) --}}
                        <!-- Searching -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-offset-0">
                                <form action="">
                                    <div class="row py-2">
                                        <div class="col-md-3">
                                            <div class="input-daterange input-group">
                                                <select name="category_id" class="form-control select2" id="category_id">
                                                    <option value="">-Select-</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $category->id == request('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-daterange input-group">
                                                <select name="product_id" class="form-control select2" id="product_id">
                                                    <option value="">-Select-</option>
                                                    @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ $product->id == request('product_id') ? 'selected' : '' }}>{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
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
                                        <div class="col-md-3">
                                            <div class="btn-group btn-corner" style="">
                                                <button type="submit" class="btn btn-xs btn-success">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                                <a href="{{ request()->url() }}" class="btn btn-xs">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
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
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Category</th>
                                                <th class="text-right">Brand</th>
                                                <th class="text-right">Profit</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($reports as $key => $item)
                                                <tr>
                                                    <td></td>
                                                    <td class="text-center">{{ $item->name }}</td>
                                                    <td class="text-center">{{ optional($item->brand)->name }}</td>
                                                    <td class="text-center">{{ optional($item->category)->name }}</td>
                                                    <td class="text-right">{{ number_format($item->profit, 2, '.', '') }}</td>
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
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-right" style="border-bottom: 1px solid transparent;"><strong>Total Profit:</strong></th>
                                                <th class="text-right">{{ number_format($reports->sum('profit'), 2, '.', '') }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    {{-- @include('partials._paginate',['data'=> $reports]) --}}

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
