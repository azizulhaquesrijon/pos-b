<script>
    $(document).ready(function() {

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault()

            $('li').removeClass('active')
            $(this).parent('li').addClass('active')

            var myurl = $(this).attr('href')
            var page = $(this).attr('href').split('page=')[1]

            loadData(page)
        })

    })

    function loadData(page) {

        let category_id = $('#category option:selected').val()

        $.ajax({
            url: '/dokani/product/get-products',

            data: {
                page: page,
                category_id: category_id
            },
            type: "get",
            datatype: "html"
        }).done(function(data) {
            $(".product-list").empty().html(data)
            location.hash = page
        }).fail(function(jqXHR, ajaxOptions, thrownError) {
            alert('No response from server')
        })
    }
</script>

<!-- Product Search Ajax -->
<script>
    function getProductByCategory(obj) {

        axios({
            method: 'get',
            url: "/dokani/product/get-products?category_id=" + $(obj).val(),

        }).then(function(response) {
            $('.product-list').html(response.data)
        })
    }


    $(document).on('keyup', ".product-search-input", $.debounce(500, function(e) {
        getProductInfo(this, e)
    }));

    function getProductInfo(params, event) {
        let _this = $(params)
        let value = _this.val()
        event.preventDefault()



        if (event.which != 38 && event.which != 40) {
        //     if (event.which == 17) {
            if (value != '') {


                $.ajax({
                    type:'POST',
                    url: "{{ route('dokani.get-searchable-products-ajax') }}",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        search: value,
                    },
                    // beforeSend: function(){
                    //     $('.ajax-loader').css("visibility", "visible");
                    // },
                    success:function(data) {

                        selectedLiIndex = -1
                        products(data)

                        if (data.length == 1) {
                            AddToRowIfItemOne(data)

                        }
                    },
                    // complete: function(){
                    //     $('.ajax-loader').css("visibility", "hidden");
                    // }
                });

            } else {
                $('.product-search').html('')
            }

        }
        arrowUpDownInit(event)
    }
</script>





<!-- PRODUCT UI LIST -->
<script>


    function products(data) {
        let table = $('#pos-table tbody');
        let length = $('#pos-table tbody tr').length + 1;
        let li = `<ul class="dropdown-menu search-product" role="menu" style="z-index: 99999999">`

        data.map(function(value) {

            li += `<li class="single-product-li dropdown-item" onclick="GetInfo(this)">
            <a href="#">
                <span ><img src="${value.image}" width="50" height="50"/></span>
                <span class="product-title"> ${value.name}</span>
                <span>(Stock:<span class="product-stock">${value.stock}</span>)</span>
                <span>Sku: <span class="sku-code">${value.product_code}</span></span>
                <span>Price: <span class="product-price">${value.product_price}</span></span>
                <span>VAT: <span class="product-vat">${value.vat}</span></span>
                <p style="display: none" class="product_id">${value.id}</p>
                <input type="hidden" class="product-stock" value="${value.stock}">
            </a>
        </li>`

            let bar = $('.product-search-input').val()

            if (bar == value.product_code){
                addItem(length, value.id, value.name, value.product_code, 1, value.product_price, value.vat, table)
            }
            $('.product-search-input').val(null).focus();

        })


        li += '</ul>'

        $('.product-search').html(li)


    }





    function AddToRowIfItemOne(data) {
        let length = $('#pos-table tbody tr').length + 1

        let keycode = (event.keyCode ? event.keyCode : event.which)

        if (keycode == 13) {

            event.preventDefault()

            if (data[0].stock > 0) {

                addItem(length, data[0].id, data[0].name, data[0].product_code, '', data[0]
                    .product_price, $('#pos-table tbody'), data[0].stock)

            } else {
                alert('Product is not have enough stock')
            }

            $('.product-search').html('')
            $('#product-' + data[0].id).focus()
            $('.product-search-input').val('')
        }
    }
</script>

{{-- UI ELEMENT SELECT USING UP DOWN ARROW KEY --}}
<script>
    let selectedLiIndex = -1
    $(document).bind('keydown', focusDiscountInput)
    $('.product-search-input').focus()

    function focusDiscountInput() {

        if (event.ctrlKey && event.code === "Space") {
            $('#discount').focus()

            event.preventDefault()
        }
    }

    $('#discount').on('keydown', function(event) {

        let keycode = (event.keyCode ? event.keyCode : event.which)

        if (event.ctrlKey && event.code === "Space") {
            $('#paid_amount').focus()

            event.preventDefault()
        }

        if (keycode == 13) {
            $('#paid_amount').focus()
            event.preventDefault()
        }
    })



    function arrowUpDownInit(e) {

        e.preventDefault()

        $('.search-product').find('li').removeClass('background')

        var li = $('.search-product').find('li')

        var selectedItem


        if (e.which === 40) {

            selectedLiIndex += 1

        } else if (e.which === 38) {

            selectedLiIndex -= 1
        }




        if (selectedLiIndex < 0) {
            selectedLiIndex = 0
        }



        if (li.length <= selectedLiIndex) {
            selectedLiIndex = 0
        }

        if (e.which == 40 || e.which == 38) {

            selectedItem = $('.search-product').find(`li:eq(${selectedLiIndex})`).addClass('background')
            select(selectedItem)

        }
        addItemOnEnter($('.search-product').find(`li:eq(${selectedLiIndex})`), e)

    }
</script>

<script>
    function addItemOnEnter(object, e) {

        if (e.which == 13) {

            let _this = $(object)

            let product_id = _this.find('.product_id').text()
            let product_title = _this.find('.product-title').text()
            let product_code = _this.find('.sku-code').text()
            let product_price = _this.find('.product-price').text()

            let product_stock = _this.find('.product-stock').text()

            let table = $('#pos-table tbody')
            let length = $('#pos-table tbody tr').length + 1


            if (product_id != '') {

                if (product_stock > 0) {

                    // add item into the sale table
                    addItem(length, product_id, product_title.trim(), product_code, '', product_price.trim(),
                        table,
                        product_stock)


                    $('.product-search').html('')
                    $('.product-search-input').val('')

                    $('#product-' + product_id).focus()
                    product_id = ''

                } else {
                    alert('Product is not have enough stock')
                }
            }

        }
    }
</script>



<script>
    function select(el, nodes) {

        var ul = $('.search-product')


        var elHeight = $(el).height()
        var scrollTop = ul.scrollTop()
        var viewport = scrollTop + ul.height()
        var elOffset = (elHeight + 10) * selectedLiIndex

        if (elOffset < scrollTop || (elOffset + elHeight) > viewport)
            $(ul).scrollTop(elOffset)

        selectedItem = $('.search-product').find(`li:eq(${selectedLiIndex})`).addClass('background')

    }
</script>
