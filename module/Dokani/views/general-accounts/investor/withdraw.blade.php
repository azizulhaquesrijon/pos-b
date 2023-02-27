@extends('layouts.master')

@section('title', 'Withdraw Balance')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Withdraw Balance
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
                        @if (hasPermission('dokani.ac.investor.withdraw.list', $slugs))
                            <a href="{{ route('dokani.ac.investor.withdraw.list') }}" class="">
                                <i class="fa fa-list-alt"></i> Withdraw List
                            </a>
                        @endif
                    </span>
                </div>



                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <form method="POST" action="{{ route('dokani.ac.investor.withdraw.create') }}" class="form-horizontal"
                              enctype="multipart/form-data">
                        @csrf


                        <!-- Name -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">
                                    Investor<sup class="text-danger">*</sup> :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <select name="investor_id" id="investor" class="form-control chosen-select"
                                            data-placeholder="-- Select Investor --">
                                        <option></option>
                                        @foreach($investors as $item)
                                            <option value="{{ $item->id }}" data-balance="{{ $item->balance }}">
                                                {{ optional($item->g_party)  ->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>





                            <!-- Refer Balance -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3" for="mobile">
                                    Balance :
                                </label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" id="balance" type="text"
                                           placeholder="Balance" readonly/>
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
                                            <option value="{{ $type->id }}"
                                                {{ $type->balance <= 0 ? 'disabled' : '' }}
                                                data-acc_balance="{{ $type->balance }}">
                                                {{ $type->name .' ('.$type->balance.')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>




                            <!-- Amount -->
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-3">Withdraw Amount :</label>
                                <div class="col-md-5 col-sm-5">
                                    <input class="form-control" type="text" id="amount" name="amount" value="{{ old('amount') }}"
                                           placeholder="Type Amount" />
                                </div>
                            </div>









                            <!-- Action -->
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4"></label>
                                <div class="col-md-3 col-sm-3">
                                    <button type="submit" class="btn btn-primary col-md-12">
                                        <i class="fa fa-plus"></i> Withdraw
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

        $('#investor').on('change', function () {

            let investor_balance = $(this).find(':selected').data('balance')
            let amount = $('#amount').val()

            $('#balance').val(investor_balance)
        })

        $('#amount').on('keyup', function () {

            let account_balance = Number($('#account_id').find(':selected').data('acc_balance'))
            let balance = Number($('#balance').val())
            let amount = Number($('#amount').val())

            if (amount > balance){

                toastr.warning('No available balance')
                $('#amount').val(balance)
            } else if(account_balance < amount){

                toastr.warning('No available balance')
                $('#amount').val(account_balance)
            }

        })

    </script>


@endsection


