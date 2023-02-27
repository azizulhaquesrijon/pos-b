@extends('layouts.master')
@section('css')
    <style>
    /* <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/> */

        .infobox {
            /* height: fit-content !important; */
            height: 100px !important;
            /* width: fit-content !important; */
            display: inline-block;
            vertical-align: top;
            width: 60px;
            border: 3px solid !important;
            background: antiquewhite !important;
            /* padding: 15px; */
        }

        .infobox-small {
            width: 100% !important;
        }


    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">



            <div class="widget-box">

                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">

                        @if (hasBranchSystem())
                        <div class="col-md-4" style="position: relative;">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="ace-icon fa fa-list"></i>
                                    </span>
                                    <select id="branch_id" name="branch_id"
                                            class="form-control chosen-select-100-percent"
                                            data-placeholder="--Select Branch--" onchange="getTotalData(this)" required>
                                        @if(auth()->user()->type == "owner")
                                            <option value="Main" data-inv_prefix="S">Main Branch</option>
                                        @endif
                                        @foreach ($branches as $id => $branch)
                                            <option value="{{ $branch->id }}" data-employees="{{ $branch->employee }}" data-inv_prefix="{{ $branch->short_name }}">{{ $branch->name }}</option>
                                            {{-- <option value="{{ $branch->id }}" data-inv_prefix="{{ $branch->short_name }}">{{ $branch->name }}</option> --}}
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                        <div class="row">
                            <div class="space-6"></div>
                            <div class="col-sm-12">
                                <div class="infobox infobox-green">
                                    <div class="infobox-icon infobox-dark">
                                        <i class="ace-icon fa fa-shopping-cart"></i>
                                    </div>

                                    <div class="infobox-data">
                                        <div class="infobox-content">TOTAL PRODUCT</div>
                                        <span class="infobox-data-number total-product">{{ $total_product ?? '0' }}</span>
                                    </div>
                                </div>



                                <div class="infobox infobox-blue">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-shopping-cart"></i>
                                    </div>

                                    <div class="infobox-data">
                                        <div class="infobox-content">YESTERDAY</div>
                                        <div class="infobox-content-body"><strong>SALES:
                                            </strong><span class="yesterday-sale">{{ number_format($yesterday_sale, 2)}}</span>
                                        </div>
                                        <div class="infobox-content-body"><strong>PURCHASE:
                                            </strong><span class="yesterday-purchase">{{ number_format($yesterday_purchase, 2) }}</span></div>
                                    </div>


                                </div>

                                <div class="infobox infobox-pink">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-shopping-cart"></i>
                                    </div>

                                    <div class="infobox-data">
                                        <div class="infobox-content">TODAY</div>
                                        <div class="infobox-content-body"><strong>SALES:
                                            </strong><span class="today-sale">{{ number_format($today_sale, 2, '.', '') }}</span></div>
                                        <div class="infobox-content-body"><strong>PURCHASE:
                                            </strong><span class="today-purchase">{{ number_format($today_purchase, 2, '.', '') }}</span></div>
                                    </div>

                                </div>

                                {{-- @php
                                    $profit_total = 0;
                                @endphp

                                @foreach ($today_income as $key => $item)
                                    @php

                                        $profit_total += $item->today_income;

                                    @endphp
                                @endforeach --}}

                                <div class="infobox infobox-pink">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-exchange"></i>
                                    </div>

                                    <div class="infobox-data">
                                        <div class="infobox-content">TODAY INCOME</div>
                                        <div class="infobox-content-body">
                                            {{-- <span class="today_income">{{ ProfitCalculate(date('Y-m-d')) ?? 0 }}</span> --}}
                                            <span class="today_income">{{ number_format($today_income, 2, '.', '') ?? 0 }}</span>
                                        </div>
                                        {{-- <div class="infobox-content-body"><strong>INCOME:
                                            </strong><span>{{ $today_income ?? 0 }}</span></div> --}}
                                    </div>

                                </div>
                                <div class="infobox infobox-pink">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-exchange" style="height: 35px;width:40px"></i>
                                    </div>
                                    {{-- @php
                                        $yesterday_profit = 0;
                                    @endphp

                                    @foreach ($yesterday_income as $key => $item)
                                        @php
                                            $yesterday_profit += $item->yesterday_income;

                                        @endphp
                                    @endforeach --}}
                                    <div class="infobox-data">
                                        <div class="infobox-content" style="font-size: 12px;">YESTERDAY INCOME</div>
                                        <div class="infobox-content-body">
                                            <span class="yesterday_income">{{ number_format($yesterday_income, 2, '.', '') ?? 0 }}</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="infobox infobox-pink">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-exchange"></i>
                                    </div>
                                    @php
                                        $total_net_income = 0;
                                    @endphp

                                    @foreach ($net_income as $key => $item)
                                        @php
                                            $total_net_income += $item->net_income;

                                        @endphp
                                    @endforeach

                                    <div class="infobox-data">
                                        <div class="infobox-content">NET INCOME</div>
                                        <div class="infobox-content-body"><strong>
                                            </strong><span></span></div>
                                         <div class="infobox-content-body">
                                            {{-- <strong>INCOME:</strong> --}}
                                            <span class="net_income">{{ number_format($total_net_income, 2, '.', '') ?? 0 }}</span>
                                        </div>
                                    </div>

                                </div>


                                <div class="infobox infobox-brown">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-shopping-cart"></i>
                                    </div>

                                    <div class="infobox-data">
                                        <div class="infobox-content">ONLINE ORDER</div>
                                        <div class="infobox-content-body"><strong>{{ $online_order }}</strong></div>
                                    </div>

                                </div>
                                <div class="infobox infobox-blue2">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-user"></i>
                                    </div>
                                    <div class="infobox-data">
                                        <div class="infobox-content">Supplier Due</div>
                                        <div class="infobox-content-body"><strong>{{ $supplier_due }}</strong></div>
                                    </div>
                                </div>

                                <div class="infobox infobox-green">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-users"></i>
                                    </div>
                                    <div class="infobox-data">
                                        <div class="infobox-content">Customer Due</div>
                                        <div class="infobox-content-body"><strong>{{ $customer_due }}</strong></div>
                                    </div>
                                </div>
                                <div class="infobox infobox-blue3">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-bar-chart"></i>
                                    </div>
                                    <div class="infobox-data">
                                        <div class="infobox-content">Income/Expense</div>
                                        <div class="infobox-content-body">
                                            Income: <strong>{{ $g_acc->where('type', 'income')->sum('total_amount') }}</strong> <br>
                                            Expense: <strong>{{ $totalExpense = $g_acc->where('type', 'expense')->sum('total_amount') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="infobox infobox-green2">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-bar-chart"></i>
                                    </div>
                                    <div class="infobox-data">
                                        <div class="infobox-content">Total Profit</div>
                                        <div class="infobox-content-body"><strong>{{ number_format($totalProfit = ProfitCalculate(), 2, '.', '') ?? 0 }}</strong></div>
                                    </div>
                                </div>
                                <div class="infobox infobox-blue">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-bar-chart"></i>
                                    </div>
                                    <div class="infobox-data">
                                        <div class="infobox-content">Net Profit</div>
                                        <div class="infobox-content-body"><strong>{{ number_format($totalProfit - $totalExpense, 2, '.', '') }}</strong></div>
                                    </div>
                                </div>

                                <div class="infobox infobox-green">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-send"></i>
                                    </div>

                                    <div class="infobox-data">
                                        <div class="infobox-content">SMS Balance</div>
                                        <div class="infobox-content-body"><strong>{{ $sms_balance }}</strong></div>
                                    </div>

                                </div>
                                <div class="space-6"></div>

                            </div>
                        </div>



                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <div class="widget-box transparent">
                                    <div class="widget-header">
                                        <h4 class="widget-title">
                                            <i class="ace-icon fa fa-signal"></i>
                                            Cash Flow
                                        </h4>
                                        <span id="thismonth" style="font-size: 18px;font:bold"></span>
                                        <input type="text" class="form-control chosen-selecst-280 year_month" name="year_month" id="year_month" placeholder="Year Month" style="display: inline-block;max-width: 120px;">
                                    </div>

                                    <div class="widget-body">
                                        <canvas id="myChart" width="400" height="150"></canvas>
                                        <!-- /.widget-main -->
                                    </div><!-- /.widget-body -->
                                </div><!-- /.widget-box -->
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    {{-- <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script> --}}


    <script>
        var today = new Date()
        var current_year = today.getFullYear()
        var current_month = today.getMonth() + 1
        var end = new Date(current_year, current_month, 0).getDate(); // end date of month
        // current month all days return
        function daysInMonth(month, year) {
            return new Date(year, month, 0).getDate();
        }

        function alldays() {

            days = daysInMonth(today.getMonth() + 1, today.getFullYear())
                result = Array.from({
                    length: days
                }, (_, i) => i + 1);
            return result;

        }
    </script>

    <script>
        $(document).on('ready', function() {
            // const monthNames = ["January", "February", "March", "April", "May", "June",
            //     "July", "August", "September", "October", "November", "December"
            // ];



            $('#thismonth').html('(Current Month: ' + today.toLocaleString('en-us', {
                month: 'long'
            }) + ')');
            $('#year_month').val(current_year + '-' + (current_month < 10 ? '0'+ Number(current_month) : current_month)).prop('selected', true);


            // var date = today.getFullYear() + '-' + (today.getMonth() < 10 ? '0' + (today.getMonth() + 1) : today.getMonth() + 1) + '-' + (i < 10 ? '0' + i : i);
            // var dateOfMonthDate = dateOfMonth.getFullYear() + '-' + (dateOfMonth.getMonth() < 10 ? '0' + (dateOfMonth.getMonth() + 1) : dateOfMonth.getMonth() + 1) + '-' + (i < 10 ? '0' + i : i);

            dynaminChart('<?php echo $cash_flows; ?>', 'php', end, today);
        });


        function dynaminChart(cash_flows, type, totalDays, today) {

            // $('#myChart').html('');
            if(type == 'php'){
                var cash_flow = JSON.parse(cash_flows);
            }else{
                var cash_flow = cash_flows;
            }
            var balance = [];

            for (let i = 1; i <= totalDays; i++) {
                var total = 0;
                var date = today.getFullYear() + '-' + (today.getMonth() < 10 ? '0' + (today.getMonth() + 1) : today.getMonth() + 1) + '-' + (i < 10 ? '0' + i : i);

                $.each(cash_flow, function(key, val) {
                    if (date === val.date) {
                        if(val.balance_type == 'In'){
                            total = Number(val.amount);
                        }
                        else{
                            total = Number(-val.amount);
                        }
                    }
                    // else {
                    //     total += 0
                    // }
                })
                // console.log({total});
                // if (total < -1) {
                //     total = -(total)
                // }
                balance.push(total)
            }
            // console.log({balance});
            // make chart

            var ctx = document.getElementById('myChart');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: alldays(),
                    datasets: [{
                        label: 'Cash Flow',
                        data: balance,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                color: function(context) {
                    var index = context.dataIndex;
                    var value = context.dataset.data[index];
                    return value < 0 ? 'red' : // draw negative values in red
                        index % 2 ? 'blue' : // else, alternate values in blue and green
                            'green';
                }

            });

            var chartColors = {
                red: 'rgb(255, 99, 132)',
                green: 'rgb(51, 204, 51)',
                color3: 'rgb(255, 99, 132)'
            };
            //set this to whatever is the deciding color change value
            var dataset = myChart.data.datasets[0];
            for (var i = 0; i < dataset.data.length; i++) {
                if (dataset.data[i] < 30) {
                    dataset.backgroundColor[i] = chartColors.red;
                    dataset.borderColor[i] = chartColors.red;
                } else if ((dataset.data[i] > 31) && (dataset.data[i] <= 60)) {
                    dataset.backgroundColor[i] = chartColors.green;
                } else {
                    dataset.backgroundColor[i] = chartColors.green;
                }
            }
            myChart.update();
        }






        $('#year_month').on('change', function(){
            const url = `{{ route('dashboard-cash-flow-chart-ajax') }}`
            axios.get(url, {
                params:{
                    year_month: $(this).val(),
                }
            })
            .then(function (response) {
                let data = response.data;
                // console.log(data);
                let currDate = new Date(data.last_date)
                dynaminChart(data.data, 'axios', data.total_days, currDate);
            })
            .catch(function (error) {
                console.log(error);
            });
        });



        // getTotalData();
        function getTotalData(obj){
            $('.load-data').text('');
            const branch_id = $(obj).val();

            const route = `{{ route('home') }}`;

            axios.get(route, {
                params:{
                    is_dynamic: 'Yes',
                    branch_id: branch_id,
                }
            })
            .then(function (response) {
                let data = response.data;
                loadDashboardData(data, branch_id);
            })
            .catch(function (error) {
                console.log(error);
            });


        }

        function loadDashboardData(data, branch_id){
            console.log(data);
            $(".yesterday-sale").text(Number(data.yesterday_sale).toFixed(2));
            $(".today-sale").text(Number(data.today_sale).toFixed(2));

            // let todayIncome = 0;
            // let yesterdayIncome = 0;
            let netIncome = 0;
            // data.today_income.map((today_income)=>{
            //     todayIncome = Number(todayIncome) + Number(today_income.today_income);
            // })

            $('.today_income').text(data.today_income.toFixed(2));

            // data.yesterday_income.map((yest_income)=>{
            //     yesterdayIncome = Number(yesterdayIncome) + Number(yest_income.yesterday_income);
            // })

            $('.yesterday_income').text(data.yesterday_income.toFixed(2));

            data.net_income.map((nt_income)=>{
                netIncome = Number(netIncome) + Number(nt_income.net_income);
            })

            $('.net_income').text(netIncome.toFixed(2));

            if(branch_id != 'Main'){
                $('.today-purchase').text(0.00);
                $(".yesterday-purchase").text(0.00);
            }else{
                $(".today-purchase").text(Number(data.today_purchase).toFixed(2));
                $(".yesterday-purchase").text(Number(data.yesterday_purchase).toFixed(2));
            }
        }



        $('#year_month').datepicker({
            autoclose: true,
            format:'yyyy-mm',
            viewMode: "months",
            minViewMode: "months",
            todayHighlight: true
        });

    </script>
@endsection
