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
        <td colspan="7" style="font-family: 'Arial Black';text-align: center; font-size: 15px">Purchase Report
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-family: 'Arial Black';text-align: center">{{ fdate(request('date', now())) }}
        </td>
    </tr>



    <tr style="background: #b9b4b41a !important; color:black !important">
        <th>Sl</th>
        <th>Date</th>
        <th>Purchase ID</th>
        <th class="text-center">Supplier</th>
        <th class="text-right">Purchase Price</th>
        <th class="text-right">Paid Price</th>

    </tr>
    </thead>



    @if(request()->filled('invoice_no') || request()->filled('supplier_id') || request()->filled('from') || request()->filled('to'))
    <tbody>

    @php
        $paid_total = 0;
        $purchase_total = 0;
    @endphp
    @foreach($reports as $key => $item)

        @php
            // $grand_total += $total = $item->payable_amount * $item->product->sell_price;
            $paid_total += $item->paid_amount;
            $purchase_total += $item->payable_amount;
        @endphp
        <tr class="body-wi">
            <th >{{ $loop->iteration }}</th>
            <th class="body-wi">{{ $item->date }}</th>
            <th class="body-wi">{{ $item->id }}</th>
            <th class="text-center">{{ optional($item->supplier)->name }}</th>
            <th class="text-right">{{ number_format($item->payable_amount,2) }}</th>

            <th class="text-right">
                {{ number_format($item->paid_amount,2) }}
            </th>
        </tr>
    @endforeach
    </tbody>

    <tfoot style="background: #c1c1c1">
    <tr>
        <th colspan="4"><strong>Total:</strong></th>
        <th class="text-right">
            {{ number_format($purchase_total,2) }}
        </th>
        <th>{{ number_format($paid_total,2) }}</th>
    </tr>
    </tfoot>
    @endif

</table>

