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
        <td colspan="7" style="font-family: 'Arial Black';text-align: center; font-size: 15px">Refer Wise Customer Sale Report
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-family: 'Arial Black';text-align: center">{{ fdate(request('date', now())) }}
        </td>
    </tr>

    <tr>
        <th class="text-left">Refer By - {{ optional($customer)->refer_by_customer_id == null ? 'User' : 'Customer' }}</th>
        <th colspan="6">{{ optional($customer)->refer_by_customer_id == null ? optional(optional($customer)->refer_user)->name : optional(optional($customer)->refer_customer)->name }}</th>
    </tr>

    <tr style="background: #b9b4b41a !important; color:black !important">
        <th>Sl</th>
        <th>Date</th>
        <th>Description</th>
        <th>Account Type</th>
        <th class="text-center">IN</th>
        <th class="text-center">OUT</th>
        <th class="text-right">Amount</th>

    </tr>
    </thead>




    <tbody>

    @php
        $total = 0;
        $in_total = 0;
        $out_total = 0;
    @endphp
    @foreach($cashFlows as $key => $item)

        @php
            $in = $item->balance_type == 'In' ? $item->amount : 0;
            $out = $item->balance_type == 'Out' ? $item->amount : 0;
            $total = $total + ($in - $out);
            $in_total += $in;
            $out_total += $out;
        @endphp
        <tr class="body-wi">
            <th >{{ $loop->iteration }}</th>
            <th class="body-wi">{{ $item->date }}</th>
            <th class="body-wi">{{ $item->description }}</th>
            <th class="body-wi">{{ optional($item->account)->name }}</th>
            <th class="text-center body-wi">{{ $in }}</th>
            <th class="text-center body-wi">{{ $out }}</th>
            <th class="text-right body-wi">{{ $total }}</th>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <th colspan="4"><strong>Grand Total:</strong></th>
        <th class="text-center">
            {{ $in_total }}
        </th>
        <th class="text-center">
            {{ $out_total }}
        </th>
        <th class="text-right">
            {{ $total }}
        </th>
    </tr>
    </tfoot>
</table>

