<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <td colspan="7" style="font-family: 'Arial Black';text-align: center; font-size: 20px">
            {{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->shop_name : optional(auth()->user()->businessProfileByUser)->shop_name }}
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-family: 'Arial Black';text-align: center; font-size: 20px">
            {{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->business_mobile : optional(auth()->user()->businessProfileByUser)->business_mobile }}
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-family: 'Arial Black';text-align: center; font-size: 15px">Chart of Account Report
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-family: 'Arial Black';text-align: center">{{ fdate(request('date', now())) }}
        </td>
    </tr>



    <tr style="background: #b9b4b41a !important; color:black !important">
        <th>Date</th>
        <th>Party Name</th>
        <th>Account Name</th>
        <th class="text-center">Head Account</th>
        <th class="text-right">Income Amount</th>
        <th class="text-right">Expense Amount</th>

    </tr>
    </thead>




    <tbody>

    @php
        $income_total = 0;
        $expense_total = 0;
    @endphp
    @foreach ($vouchers as $key => $report)

        @foreach($report->details as $item)
            @php
                $income = $report->type == 'income' ? $item->amount : 0;
                $expense = $report->type == 'expense' ? $item->amount : 0;
                $income_total += $income;
                $expense_total += $expense;
            @endphp

            <tr>
                {{--                                                    <td>{{ $item->id }}</td>--}}
                <th>{{ $report->date }}</th>
                <th>{{ optional($report->party)->name }}</th>
                <th>{{ $report->type }}</th>
                <th class="text-center">{{ optional($item->chart_account)->name }}</th>
                <th class="text-right">{{ $report->type == 'income' ? $item->amount : 0 }}</th>
                <th class="text-right">{{ $report->type == 'expense' ? $item->amount : 0 }}</th>
            </tr>
        @endforeach

    @endforeach
    </tbody>

    <tfoot style="background: #c1c1c1">
    <tr>
        <th colspan="4"><strong>Total:</strong></th>
        <th class="text-center">
            {{ number_format($income_total) }}
        </th>
        <th class="text-center">
            {{ number_format($expense_total) }}
        </th>
    </tr>
    </tfoot>
</table>

