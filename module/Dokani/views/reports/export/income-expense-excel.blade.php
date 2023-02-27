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
        <td colspan="7" style="font-family: 'Arial Black';text-align: center; font-size: 15px">Income Expense Report
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-family: 'Arial Black';text-align: center">{{ fdate(request('date', now())) }}
        </td>
    </tr>


    <thead>
    <tr>
        <th colspan="2" style="text-align: right;font-size: 12px;color: black">Income</th>
        <th colspan="2" style="font-size: 12px;color: black">Expense</th>
    </tr>
    <tr>
        <th>Description</th>
        <th>Amount</th>

        <th>Description</th>
        <th>Amount</th>
    </tr>
    </thead>

    <tbody>

    @php
        $income_total = 0;
        $expense_total = 0;
    @endphp
    @forelse ($reports as $key => $report)
        @php
            $income_total += $report->balance_type == 'In' ? $report->amount : 0;
            $expense_total += $report->balance_type == 'Out' ? $report->amount : 0;
        @endphp

{{--        @if($report->transactionable_type == 'Income' || $report->transactionable_type == 'Expense')--}}
{{--            @foreach($report->transactionable->details ?? [] as $detail)--}}
{{--                <tr>--}}
{{--                    <th>{{ $report->balance_type == 'In' ? $report->transactionable_type . ' => '. $detail->chart_account->name .' => '.$report->date : '' }}</th>--}}
{{--                    <th>{{ $report->balance_type == 'In' ? $detail->amount : '' }}</th>--}}

{{--                    <th>{{ $report->balance_type == 'Out' ? $report->transactionable_type . ' => '. $detail->chart_account->name .' => '.$report->date : '' }}</th>--}}
{{--                    <th>{{ $report->balance_type == 'Out' ? $detail->amount : '' }}</th>--}}
{{--                </tr>--}}

{{--            @endforeach--}}
{{--        @else--}}
            <tr>
                <th>{{ $report->balance_type == 'In' ? $report->transactionable_type .' ('.$report->date.')' : '' }}</th>
                <th>{{ $report->balance_type == 'In' ? $report->amount : '' }}</th>
                <th>{{ $report->balance_type == 'Out' ? $report->transactionable_type .' ('.$report->date.')' : '' }}</th>
                <th>{{ $report->balance_type == 'Out' ? $report->amount : '' }}</th>
            </tr>

{{--        @endif--}}

    @empty
        <tr>
            <th colspan="30" class="text-center text-danger py-3"
                style="background: #eaf4fa80 !important; font-size: 18px">
                <strong>No records found!</strong>
            </th>
        </tr>
    @endforelse
    </tbody>

    <tfoot>
    <tr>
        <th><strong>Income Total:</strong></th>
        <th>
            {{ number_format($income_total,2) }}
        </th>
        <th><strong>Expense Total:</strong></th>
        <th>
            {{ number_format($expense_total,2) }}
        </th>
    </tr>
    </tfoot>
</table>

