@extends('layouts.master')

@section('title', 'Invest Balance Add')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Invest Balance Add
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
                        @if (hasPermission('dokani.ac.investors.index', $slugs))
                            <a href="{{ route('dokani.ac.investors.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Investor List
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.ac.investor.balance.store') }}" class="form-horizontal"
                              enctype="multipart/form-data">
                        @csrf


                            <input type="hidden" name="investor_id" value="{{ $investor->id }}">

                            <!-- Date -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    Date :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control date-picker" type="text" name="date"
                                           value="{{ now()->format('Y-m-d') }}"/>
                                </div>
                            </div>





                            <!-- Account -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Account :</label>
                                <div class="col-md-5 col-sm-5">
                                    <select name="account_id" id="account_id" style="width: 100%"
                                            class="form-control select2" data-placeholder="- Select Account -"
                                            aria-hidden="true" required>
                                        <option value=""></option>
                                        @foreach (accountInfo() as $type)
                                            <option value="{{ $type->id }}" {{ $type->id == $investor->account_id ? 'selected' : '' }}>
                                                {{ $type->name .' ('.$type->balance.')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="control-label col-sm-3 no-padding-right">Bank Info.</label>
                                <div class="col-md-5 col-sm-5" style="display: flex">
                                    <input type="text" name="check_no" placeholder="Enter Check No" class="form-control">&nbsp;&nbsp;
                                    <input type="text" name="check_date" placeholder="Enter Check Date" class="form-control date-picker" autocomplete="off">
                                </div>
                            </div>


                            <!-- Amount -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="sales_price">Invest Amount :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="number" name="amount"
                                           value="0" placeholder="Invest Amount" required/>
                                </div>
                            </div>





                            <!-- Note -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Note :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="text" name="note" value="{{ $investor->note }}"
                                           placeholder="Type Note"/>
                                </div>
                            </div>





                            <!-- Action -->
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4"></label>
                                <div class="col-md-3 col-sm-3">
                                    <button type="submit" class="btn btn-primary col-md-12">
                                        <i class="fa fa-plus"></i> Add New
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('js')


    <script src="{{ asset('assets/custom_js/file_upload.js') }}"></script>


@endsection


