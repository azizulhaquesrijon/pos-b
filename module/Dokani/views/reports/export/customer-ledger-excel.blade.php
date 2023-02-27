<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <td colspan="8" style="font-family: 'Arial Black';text-align: center; font-size: 20px">
            {{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->shop_name : optional(auth()->user()->businessProfileByUser)->shop_name }}
        </td>
    </tr>
    <tr>
        <td colspan="8" style="font-family: 'Arial Black';text-align: center; font-size: 20px">
            {{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->business_mobile : optional(auth()->user()->businessProfileByUser)->business_mobile }}
        </td>
    </tr>

    <tr>
        <td colspan="8" style="font-family: 'Arial Black';text-align: center; font-size: 18px">
            {{ $customer->name }}
        </td>
    </tr>

    <tr>
        <td colspan="8" style="font-family: 'Arial Black';text-align: center; font-size: 15px">Customer Ledger Report
        </td>
    </tr>
    <tr>
        <td colspan="8" style="font-family: 'Arial Black';text-align: center">{{ fdate(request('date', now())) }}
        </td>
    </tr>



    <tr style="background: #c6c6c6">
        <th>Sl</th>
        <th>Date</th>
        <th>Source ID</th>
        <th>Description</th>
        <th class="text-right">Debit/Sales</th>
        <th class="text-right">Credit/Receive</th>
        <th class="text-right">Balance</th>
    </tr>
    </thead>




    <tbody>
    @if (request('customer_id'))
        <tr>
            <th colspan="6"><b>Opening Balance</b></th>
            <th class="">{{ $customer->opening_due }}</th>
        </tr>
    @endif
    @php
        $total_debit_amount = 0;
        $total_credit_amount = 0;
        $total_due_amount = $customer->opening_due ?? 0;
    @endphp
    @foreach($reports as $key => $item)

        @php
            $collection_paid_amount = $item->source_type == 'Collection' ? optional($item->source)->paid_amount : 0 ;
            $total_due_amount += $item->source_type == 'Sale' ? $item->amount : 0 ;
            $total_due_amount = $total_due_amount - $collection_paid_amount;
            $total_debit_amount += optional($item->source)->payable_amount;
            $total_credit_amount += optional($item->source)->paid_amount;
        @endphp
        <tr class="body-wi">
            <th>{{ $loop->iteration }}</th>
            <th>{{ $item->date }}</th>
            <th>{{ $item->source_id }}</th>
            <th>{{ $item->source_type }}</th>
            <th class="text-right">{{ number_format(optional($item->source)->payable_amount,2) }}</th>
            <th class="text-right">{{ number_format(optional($item->source)->paid_amount,2) }}</th>
            <th class="text-right">{{ number_format($total_due_amount,2) }}</th>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <th colspan="4"><strong>Total Amount:</strong></th>
        <th class="text-right">
            {{ number_format($total_debit_amount,2) }}
        </th>
        <th class="text-right">
            {{ number_format($total_credit_amount,2) }}
        </th>
        <th class="text-right">
            {{ number_format($total_due_amount,2) }}
        </th>
    </tr>
    </tfoot>
</table>

