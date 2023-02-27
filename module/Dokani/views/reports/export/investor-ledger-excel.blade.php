<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <td colspan="12" style="font-family: 'Arial Black';text-align: center; font-size: 20px">
            {{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->shop_name : optional(auth()->user()->businessProfileByUser)->shop_name }}
        </td>
    </tr>
    <tr>
        <td colspan="12" style="font-family: 'Arial Black';text-align: center; font-size: 20px">
            {{ auth()->user()->dokan_id == null ? optional(auth()->user()->businessProfile)->business_mobile : optional(auth()->user()->businessProfileByUser)->business_mobile }}
        </td>
    </tr>
    <tr>
        <td colspan="12" style="font-family: 'Arial Black';text-align: center; font-size: 15px">Investor Ledger Report
        </td>
    </tr>
    <tr>
        <td colspan="12" style="font-family: 'Arial Black';text-align: center">{{ fdate(request('date', now())) }}
        </td>
    </tr>



    <tr style="background: #b9b4b41a !important; color:black !important">
        <th>Sl</th>
        <th>Investor Name</th>
        <th class="text-right"> Date</th>
        <th class="text-right">Description</th>
        <th class="text-right">In</th>
        <th class="text-right">Out</th>
        <th class="text-right">Balance</th>

    </tr>
    </thead>




    <tbody>

    @php
        $total_in = 0;
        $total_out = 0;
        $total_balance = 0;
    @endphp

    @foreach ($reports as $key => $item)
        @php
            $total_in += $in = $item->balance_type == 'In' ? $item->amount : 0.00;
            $total_out += $out = $item->balance_type == 'Out' ? $item->amount : 0.00;
            $total_balance += $in - $out;
        @endphp
        <tr>
            <th>{{ $loop->iteration }}</th>
            <th>{{ optional(optional($item->investor)->g_party)->name }}</th>
            <th class="text-right">{{ $item->date }}</th>
            <th class="text-right">{{ $item->description }}</th>
            <th class="text-right">{{ $item->balance_type == 'In' ? $item->amount : 0.00 }}</th>
            <th class="text-right">{{ $item->balance_type == 'Out' ? $item->amount : 0.00 }}</th>
            <th class="text-right">{{ $total_balance }}</th>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <th colspan="4"><strong>Total Qty:</strong></th>
        <th class="text-right">
            {{ $total_in }}
        </th>
        <th class="text-right">
            {{ $total_out }}
        </th>
        <th class="text-right">
            {{ $total_balance }}
        </th>

    </tr>
    </tfoot>
</table>


