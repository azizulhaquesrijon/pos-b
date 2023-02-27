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
        <td colspan="12" style="font-family: 'Arial Black';text-align: center; font-size: 15px">Inventory Report
        </td>
    </tr>
    <tr>
        <td colspan="12" style="font-family: 'Arial Black';text-align: center">{{ fdate(request('date', now())) }}
        </td>
    </tr>



    <tr style="background: #b9b4b41a !important; color:black !important">
        <th>Sl</th>
        <th>P. Name</th>
        <th>P. Barcode</th>
        <th>P. Unit</th>
        <th class="text-right"> Opening Qty</th>
        <th class="text-right">Purchased Qty</th>
        <th class="text-right">Sold Qty</th>
        <th class="text-right">Sold Return Qty</th>
        <th class="text-right">Purchased Return Qty</th>
        <th class="text-right">Available Qty</th>
        <th class="text-right">Product Cost</th>
        <th class="text-right">Value</th>

    </tr>
    </thead>




    <tbody>

    @php
        $total_value = 0;
        $total_opening_qty = 0;
        $total_purchase_qty = 0;
        $total_purchase_return_qty = 0;
        $total_sold_qty = 0;
        $total_sold_return_qty = 0;
        $total_available_qty = 0;
        $total_cost = 0;
    @endphp
    @foreach($reports as $key => $item)

        @php
            $total_value += $total = $item->purchase_price * $item->available_qty;
            $total_opening_qty += $item->opening_qty;
            $total_purchase_qty += $item->purchased_qty;
            $total_purchase_return_qty += $item->purchase_return_qty;
            $total_sold_qty += $item->sold_qty;
            $total_sold_return_qty += $item->sold_return_qty;
            $total_available_qty += $item->available_qty;
            $total_cost += $item->purchase_price;
        @endphp
        <tr class="body-wi">
            <th>{{ $loop->iteration }}</th>
            <th>{{ $item->name }}</th>
            <th>{{ $item->barcode }}</th>
            <th>{{ optional($item->unit)->name }}</th>
            <th class="text-right">{{ $item->opening_qty }}</th>
            <th class="text-right">{{ $item->purchased_qty }}</th>
            <th class="text-right">{{ $item->sold_qty }}</th>
            <th class="text-right">{{ $item->sold_return_qty }}</th>
            <th class="text-right">{{ $item->purchase_return_qty }}</th>
            <th class="text-right">{{ $item->available_qty }}</th>
            <th class="text-right">{{ number_format($item->purchase_price) }}
            </th>
            <th class="text-right">
                {{ number_format($total) }}
            </th>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <th colspan="4"><strong>Total:</strong></th>
        <th class="text-right">{{ $total_opening_qty }}</th>
        <th class="text-right">{{ $total_purchase_qty }}</th>
        <th class="text-right">{{ $total_sold_qty }}</th>
        <th class="text-right">{{ $total_sold_return_qty }}</th>
        <th class="text-right">{{ $total_purchase_return_qty }}</th>
        <th class="text-right">{{ $total_available_qty }}</th>
{{--        <th class="text-right">{{ $total_cost }}</th>--}}
        <th class="text-right">{{ $total_value }}</th>
    </tr>
    </tfoot>
</table>

