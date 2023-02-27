@extends('layouts.master')


@section('title', 'Investors Profit Disbursement')

@section('page-header')
    <i class="fa fa-info-circle"></i> Investors Profit Disbursement
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
                        <form action="{{ route('dokani.ac.disbursement.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-offset-2" style="padding-bottom: 20px">
                                    <input type="text" value="" id="sale_profit" onkeyup="profitCalculate()"
                                           class="form-control input-sm" placeholder="Profit Amount">
                                </div>
                                <div class="col-md-4" style="padding-bottom: 20px">
                                    <input type="text" id="note" name="note" placeholder="Type Note"
                                           class="form-control input-sm"/>
                                    <p id="warning-message" class="text-danger"></p>
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
                                                <th>Balance</th>
                                                <th>Profit Amount</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @php
                                                $total_amount = 0;
                                            @endphp

                                            @forelse ($investors as $key => $item)
                                                @php
                                                    $total_amount += $item->balance;
                                                @endphp
                                                <tr>
                                                    <td class="profit_no">{{ $loop->iteration }}</td>
                                                    <input type="hidden" class="" name="investor_ids[]" value="{{ $item->id }}">
                                                    <td class="profit_name">
                                                        {{ optional($item->g_party)->name }}
                                                    </td>
                                                    <td class="profit_mobile">{{ optional($item->g_party)->mobile }}</td>
                                                    <td class="profit_balance">{{ $item->balance }}</td>
                                                    <td class="profit_amount">
                                                        <input type="text" class="p_amount" value="" name="profit_amount[]" required readonly>
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

                                            <tfoot>
                                            <tr>
                                                <th colspan="3">Total amount</th>
                                                <th>{{ number_format($total_amount,2) }}</th>
                                                <th class="total_profit"></th>
                                                <input type="hidden" class="total_balance" value="{{ $total_amount }}">
                                            </tr>
                                            </tfoot>
                                        </table>


                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-sm btn-success" style="margin-left: 91%;margin-top: 20px;">
                                Submit
                            </button>

                        </form>


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

        function profitCalculate() {

            let sale_profit = Number($('#sale_profit').val())
            let total_amount = Number($('.total_balance').val())
            let total = 0;
            $('.profit_no').each( function () {

                let profit_balance = Number($(this).closest('tr').find('.profit_balance').text())

                let individual_profit = ((sale_profit * profit_balance) / total_amount).toFixed(2);
                $(this).closest('tr').find('.p_amount').val(individual_profit)

                 total += Number(individual_profit)
                $('.total_profit').text((total).toFixed(2))
            })

        }


        var minLength = 0;
        var maxLength = 20;
        $(document).ready(function(){
            $('#note').on('keydown keyup change', function(){
                var char = $(this).val();
                var charLength = $(this).val().length;
                if(charLength > maxLength){
                    $('#warning-message').text('Length is not valid, maximum '+maxLength+' allowed.');
                    $(this).val(char.substring(0, maxLength));
                }else{
                    $('#warning-message').text('');
                }
            });
        });


    </script>

@endsection


