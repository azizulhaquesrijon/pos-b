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
        <td colspan="12" style="font-family: 'Arial Black';text-align: center; font-size: 15px">Sale Report
        </td>
    </tr>
    <tr>
        <td colspan="12" style="font-family: 'Arial Black';text-align: center">{{ fdate(request('date', now())) }}
        </td>
    </tr>



    <tr style="background: #c1c1c1 !important;">
        <th>Sl</th>
        <th>Date</th>
        <th>Sale ID</th>
        <th class="text-center">Customer</th>
        <th class="text-center">Quantity</th>
        <th class="text-right">Sale Price</th>
        <th class="text-right">Purchase Price</th>
        <th class="text-right">Profit</th>
    </tr>
    </thead>
    @if(request()->filled('invoice_no') || request()->filled('created_by') || request()->filled('from') || request()->filled('to') || request()->filled('customer_id') || request()->filled('cus_area_id') || request()->filled('cus_category_id'))
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
            <th>{{ $item->date }}</th>
            <th><a href="{{ route('dokani.sales.show', $item->id) }}" target="_blank">{{ $item->id }}</a></th>
            <th class="text-center">{{ optional($item->customer)->name }}</th>
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
    <tr style="background: #c1c1c1 !important;">
        <th colspan="4"><strong>Total:</strong></th>
        <th class="text-right">
            {{ number_format($qty_total,2) }}
        </th>
        <th class="text-right">
            {{ number_format($sale_total,2) }}
        </th>
        <th class="text-right">
            {{ number_format($grand_purchase_total,2) }}
        </th>
        <th class="text-right">
            {{ number_format($profit_total,2) }}
        </th>
    </tr>
    </tfoot>
    @endif
</table>


