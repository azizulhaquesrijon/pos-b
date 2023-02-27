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
        <td colspan="12" style="font-family: 'Arial Black';text-align: center; font-size: 15px">Product Ledger Report
        </td>
    </tr>
    <tr>
        <td colspan="12" style="font-family: 'Arial Black';text-align: center">{{ fdate(request('date', now())) }}
        </td>
    </tr>



    <tr style="background: #b9b4b41a !important; color:black !important">
        <th>Sl</th>
        <th>Product Name</th>
        <th>Product Unit</th>
        <th class="text-right"> Date</th>
        <th class="text-right">Description</th>
        <th class="text-right">In</th>
        <th class="text-right">Out</th>
        <th class="text-right">Damage</th>
        <th class="text-right">Available Qty</th>

    </tr>
    </thead>




    <tbody>

    @php
        $total_in = 0;
        $total_out = 0;
        $total_available = 0;
        $grand_total_available = 0;
    @endphp

    @foreach ($reports as $key => $item)
        @php
            $total_in += $in = $item->stock_type == 'In' ? $item->quantity : 0.00;
            $total_out += $out = $item->stock_type == 'Out' ? $item->quantity : 0.00;
            $total_available += $in - $out;
        @endphp
        <tr>
            <th>{{ $loop->iteration }}</th>
            <th>{{ optional($item->product)->name }}</th>
            <th>{{ optional(optional($item->product)->unit)->name }}</th>
            <th class="text-right">{{ $item->date }}</th>
            <th class="text-right">{{ $item->sourceable_type }}</th>
            <th class="text-right">{{ $item->stock_type == 'In' ? $item->quantity : 0.00 }}</th>
            <th class="text-right">{{ $item->stock_type == 'Out' ? $item->quantity : 0.00 }}</th>
            <th class="text-right">{{ 0.00 }}</th>
            <th class="text-right">{{ $total_available }}</th>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <th colspan="5"><strong>Total Qty:</strong></th>
        <th class="text-right">
            {{ $total_in }}
        </th>
        <th class="text-right">
            {{ $total_out }}
        </th>
        <th class="text-right">
            {{ 0 }}
        </th>
        <th class="text-right">
            {{ $total_available }}
        </th>

    </tr>
    </tfoot>
</table>

