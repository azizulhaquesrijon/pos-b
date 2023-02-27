<ul class="dropdown-menu search-product" role="menu" style="z-index:99999">
    @foreach ($products as $item)
        <li class="single-product-li dropdown-item" onclick="GetInfo(this)">
            <a href="#">
                <span><img src="${value.image}" width="50" height="50" /></span>
                <span class="product-title"> {{ $item->name }}</span>
                <span>(Stock:<span class="product-stock">44</span>)</span>
                <span>Sku: <span class="sku-code">{{ $item->barcode }}</span></span>
                <span>Price: <span class="product-price">{{ $item->sell_price }}</span></span>
                <p style="display: none" class="product_id">{{ $item->id }}}</p>
                <input type="hidden" class="product-stock" value="4">
            </a>
        </li>
    @endforeach
</ul>
