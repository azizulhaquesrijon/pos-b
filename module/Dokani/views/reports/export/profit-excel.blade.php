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
        <td colspan="12" style="font-family: 'Arial Black';text-align: center; font-size: 15px">Profit Report
        </td>
    </tr>
    <tr>
        <td colspan="12" style="font-family: 'Arial Black';text-align: center">{{ fdate(request('date', now())) }}
        </td>
    </tr>



    <tr style="background: #c1c1c1 !important;">
        <th>Sl</th>
        <th class="text-center">Description</th>
        <th class="text-center">Quantity</th>
        <th class="text-right">Sale Price</th>
        <th class="text-right">Purchase Price</th>
        <th class="text-right">Profit</th>
    </tr>
    </thead>

    <tbody>

    @php
        $profit_total = 0;
        $sale_total = 0;
        $purchase_total = 0;
        $grand_purchase_total = 0;
        $qty_total = 0;
    @endphp
    @foreach($reports as $key => $item)

        @php
            // $grand_total += $total = $item->payable_amount * $item->product->sell_price;
             $purchase = 0;
             foreach ($item->details as $detail){
                 $purchase += $detail->buy_price * $detail->quantity;

             }
             $profit_total += $item->payable_amount - $purchase;
             $sale_total += $item->payable_amount;
             $grand_purchase_total += $purchase;
             $qty_total += $item->total_qty;
        @endphp
        <tr>
            <th>{{ $loop->iteration }}</th>
            <th class="text-center">{{ 'Sale ('.$item->date.')' }}</th>
            <th class="text-center">{{ $item->total_qty ?? 0 }}</th>
            <th class="text-right">{{ number_format($item->payable_amount,2) }}</th>
            <th class="text-right">
                {{ number_format($purchase,2) }}
            </th>
            <th class="text-right">
                {{ number_format($item->payable_amount - $purchase,2) }}
            </th>


        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <th colspan="5" class="text-right" style="border-bottom: 1px solid transparent;"><strong>Gross Profit:</strong></th>
        <th class="text-right">
            {{ number_format($profit_total,2) }}
        </th>
    </tr>
    <tr>
        <th colspan="5" class="text-right" style="border-bottom: 1px solid transparent;"><strong>Total Expense:</strong></th>
        <th class="text-right">
            {{ '(-) '.number_format($expense,2) ?? 0 }}
        </th>
    </tr>

    <tr style="background: #c1c1c1 !important;">
        <th colspan="5" class="text-right"><strong>Net Profit:</strong></th>
        <th class="text-right">
            {{ number_format($profit_total - $expense,2) }}
        </th>
    </tr>
    </tfoot>
</table>


