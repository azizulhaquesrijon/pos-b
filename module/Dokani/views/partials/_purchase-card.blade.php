<div class="card-body">
    <div class="pos-product">
        <div class="row justify-content-center all-products pt-sm-65" style="margin-top: 10px">
            @foreach ($products as $item)
                <div class="col-md-2 col-4 p-1" style="margin-top:5px">
                    <div class="single-product" onclick="GetProduct(this)">
                        <div class="img">
                            <img src="{{ asset(file_exists($item->image) ? $item->image : 'assets/images/default.png') }}" class="img-fluid">
                        </div>
                        <p style="display: none" class="product_id">{{ $item->id }}</p>
                        <div class="description">
                            <p class="" style="background-color: #dfeff9; height: 74px;border-radius: 5px;padding: 5px;">
                                <strong class="product-title">
                                    {{ Str::limit($item->name), 20, '...' }}
                                </strong>
                                <br>
                                {{ $item->barcode }}
                            </p>
                            <div class="d-flex" style="display: none">
                                <div class="col-12 pl-0 pt-0">Sku: <span
                                        class="sku-code">{{ $item->barcode }}</span></div>
                            </div>
                        </div>
                        <div class="price product-price">{{ round($item->product_price ?? $item->purchase_price,2) }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@include('partials._paginate', ['data' => $products])

