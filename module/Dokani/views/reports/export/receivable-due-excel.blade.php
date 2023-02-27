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
        <td colspan="7" style="font-family: 'Arial Black';text-align: center; font-size: 15px">Receivable Due Report
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-family: 'Arial Black';text-align: center">{{ fdate(request('date', now())) }}
        </td>
    </tr>



    <tr style="background: #b9b4b41a !important; color:black !important">
        <th>Sl</th>
        <th>Customer</th>
        <th>Mobile</th>
        <th class="text-right">Due</th>
        <th class="text-right">Advance</th>

    </tr>
    </thead>




    <tbody>

    @php
        $total_due = 0;
    @endphp
    @foreach($reports as $key => $item)

        @php
            // $grand_total += $total = $item->payable_amount * $item->product->sell_price;
            $total_due += $item->balance;
        @endphp
        <tr class="body-wi">
            <th>{{ $loop->iteration }}</th>
            <th class="body-wi">{{ $item->name }}</th>
            <th class="body-wi">{{ $item->mobile }}</th>
            <th class="text-center">{{ $item->balance }}</th>
            <th class="text-right">{{ $item->previous_due }}</th>

        </tr>
    @endforeach
    </tbody>

    <tfoot style="background: #c1c1c1">
    <tr>
        <th colspan="3"><strong>Total:</strong></th>
        <th class="text-right">
            {{ number_format($total_due,2) }}
        </th>
        <th></th>
    </tr>
    </tfoot>
</table>


