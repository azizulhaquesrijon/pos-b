<div class="card-body">
    <div class="pos-product">
        <div class="row all-products pt-sm-65" style="margin-top: 10px; margin-left: 0px !important; margin-right: 0px !important;">
            @foreach ($products->where('available_qty', '>', 0) as $item)
                <div class="col-md-4 p-1 pt-1 product_card" style="margin-top:5px">
                    <div style="background: white" class="single-product" onclick="{{ $item->available_qty <= 0 ? '' : 'GetProduct(this)' }}" style="background: #f9f9f9 !important; border: 1.5px solid #3e3e3e !important;">
                        <p style="display: none" class="product_id">{{ $item->id }}</p>
                        <p style="display: none" class="product-vat">{{ $item->vat }}</p>
                        <p style="display: none" class="product_stock">{{ $item->available_qty }}</p>

                        @if (file_exists($item->image))
                            <div class="img" style="position: relative">
                                <img src="{{ asset($item->image) }}" class="img-fluid">
                            </div>
                        @else
                            <div class="img" style="position: relative">
                                <img src="{{ asset('assets/images/default.png') }}" class="img-fluid">
                            </div>
                        @endif

                        <span class="product-sku sku-code">{{ $item->barcode }}</span>

                        <div class="description" style="padding: 5px;background: #dfdfdf;border-radius: 0px 0px 5px 5px;">
                            <p class="product-title">
                                <strong>{{ Str::limit($item->name), 25, '...' }}</strong>
                            </p>
                            <div class="row" style="margin-top: 0px;margin-bottom: 5px;display: flex;justify-content: space-between">
                                <div class="">
                                    <span style="font-size: 13px; font-weight: 600; color: rgb(12 185 0 / 94%)">{{ round($item->product_price ?? $item->sell_price,2) }}৳</span>
                                    <input type="hidden" class="category_vat"
                                        value="{{ optional($item->category)->vat }}">
                                </div>
                                <div class="">
                                    <span style="font-size: 13px; font-weight: 600; color: rgb(255 2 2 / 94%)">Qty : {{ number_format($item->available_qty,0,'.','') ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="price product-price" style="display: none">
                            {{ round($item->product_price ?? $item->sell_price,2) }}
                        </div>

                        @if($item->available_qty <= 0)
                            <div class="overlay">
                                <div class="text">
                                    <i class="fa fa-warning"></i> No Available Quantity
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            @endforeach

        </div>
    </div>
</div>
@include('partials._paginate', ['data' => $products])
