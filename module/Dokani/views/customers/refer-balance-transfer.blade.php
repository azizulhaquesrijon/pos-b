@extends('layouts.master')

@section('title', 'Refer Balance Transfer')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Refer Balance Transfer
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
                        @if (hasPermission('dokani.customers.index', $slugs))
                            <a href="{{ route('dokani.customers.index') }}" class="">
                                <i class="fa fa-list-alt"></i> Customer List
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.refer.transfer.store') }}" class="form-horizontal"
                              enctype="multipart/form-data">
                        @csrf


                        <!-- Name -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Customer<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <select name="customer_id" id="customer_id" class="form-control chosen-select"
                                    data-placeholder="-- Select Customer --">
                                        <option></option>
                                        @foreach($customers as $item)
                                            <option value="{{ $item->id }}" data-refer_balance="{{ $item->refer_balance }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>





                            <!-- Refer Balance -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    Refer Balance :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" id="refer_balance" type="text" name="refer_balance"
                                           placeholder="Refer Balance" readonly/>
                                </div>
                            </div>




                            <!-- Amount -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Amount :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="number" id="amount" name="amount" value="{{ old('amount') }}"
                                           placeholder="Type Amount" />
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


    <script>

        $('#customer_id').on('change', function () {

            let customer_refer_balance = $(this).find(':selected').data('refer_balance')
            let amount = $('#amount').val()

            $('#refer_balance').val(customer_refer_balance)
        })

        $('#amount').on('keyup', function () {

            let customer_refer_balance = Number($('#refer_balance').val())
            let amount = Number($('#amount').val())

            if (amount > customer_refer_balance){

                toastr.warning('No available balance')
                $('#amount').val(customer_refer_balance)
            }

        })

    </script>


@endsection

