<script>
    $(document).on('keyup', ".product-search-input", $.debounce(500, function(e) {
        getProductInfo(this, e)
    }));
    function getProductInfo(params, event, page) {
        let _this = $(params)
        let value = _this.val()

        let branch_id = $('#branch_id').find('option:selected').val();
        console.log(branch_id);

        event.preventDefault()
        if (event.which != 38 && event.which != 40) {
            //     if (event.which == 17) {
            if (value != '') {

                $.ajax({
                    type:'GET',
                    url: "{{ url('dokani/product/get-finish-products?page=') }}" + page,
                    data: {
                        search: value,
                        branch_id:branch_id
                    },
                    beforeSend: function(){
                        $('.ajax-loader').css("visibility", "visible");
                    },
                    success:function(data) {
                        selectedLiIndex = -1
                        products(data, value)
                        // console.log(data);

                        if (data.length == 1) {
                            AddToRowIfItemOne(data[0], event)

                        }
                    },
                    complete: function(){
                        $('.ajax-loader').css("visibility", "hidden");
                    }
                });

            } else {
                $('.product-search').html('')
            }

        }
        arrowUpDownInit(event)
    }


    function products(data,search_value) {
        let li = `<table id="dynamic-table" class="table table-bordered table-hover search-product" style="z-index:99999">
                                <thead>
                                <tr class="table-header-bg" style="position: sticky; top: 0px">
                                    <th class="pl-3" style="color: white !important; width: 20%" >Name</th>
                                    <th class="pl-3" style="color: white !important; width: 20%" >Stock</th>
                                    <th class="pl-3" style="color: white !important; width: 20%" >Sku</th>
                                    <th class="pl-3" style="color: white !important; width: 20%" >Price</th>
                                    <th class="pl-3" style="color: white !important; width: 20%" >Vat</th>
                                    <th class="pl-3" style="color: white !important; width: 20%" >Unit</th>
                                </tr>
                                </thead><tbody>`
        if (data.length > 0){
            data.map(function(value) {
                let product_price = Number(value.product_price).toFixed(2)
                let product_vat = Number(value.vat).toFixed(2)
                let bg_color = '';
                let product_add = '';
                if (value.stock > 0){
                    bg_color = '';
                    product_add='GetInfo(this)';
                }
                else {
                    bg_color = 'bg-warning';
                    product_add='';
                }

                li += `<tr class="single-product-li dropdown-item ${bg_color}" onclick="${product_add}">
                <a href="javascript:void(0)">
                    <td class="product-title" width="20%"><strong>${value.name}</strong></td>
                    <td width="20%"><span class="product-stock">${value.stock}</span></td>
                    <td width="20%"><span class="sku-code">${value.product_code}</span></td>
                    <td width="20%"><span class="product-price">${product_price}</span></td>
                    <td width="20%"><span class="product-vat">${product_vat}</span></td>
                    <td class="buy-price" style="display: none">${value.product_cost}</td>
                    <td class="description" style="display: none">${value.product_description}</td>
                    <td class="product-unit">${value.unit}</td>
                    <td style="display: none" class="product_id">${value.id}</td>
                </a>
            </tr>`
            })
        }else {
                li += `<tr>
                        <td colspan="30" class="text-center text-danger py-3"
                        style="background: #eaf4fa80 !important; font-size: 18px">
                        <strong>No product found!</strong>
                        </td>
                    </tr>`
            }
            li += '</tbody></table>'

            $('.product-search').html(li)

            var mouse_is_inside = false;

            $(document).ready(function()
            {
                $('.search-product').click(function(){
                    mouse_is_inside = true;
                }, function(){
                    mouse_is_inside = false;
                });

                $("body").mouseup(function(){
                    if(! mouse_is_inside) $('.search-product').hide();
                });
            });
    }





    function AddToRowIfItemOne(data, event) {

        let length = $('#pos-table tbody tr').length + 1

        let keycode = (event.keyCode ? event.keyCode : event.which)

        if (keycode == 13) {

            event.preventDefault()

            if (data?.stock > 0) {
                let description = data?.product_description
                if (description == null){
                    description = ''
                }

                addProduct(length, data?.id, data?.name, data?.product_code, 1, data?.product_price ?? 0,
                    data?.product_vat ?? 0,data?.product_cost ?? 0, data?.unit, data?.stock ?? 0, description, $('#pos-table tbody'))
            } else {
                alert('Product is not have enough stock')
            }

            $('.product-search').html('')
            // $('#product-' + data?.id).focus()
            $('.product-search-input').val('').focus()
        }
    }




    let selectedLiIndex = -1
    $(document).bind('keydown', focusDiscountInput)
    $('.product-search-input').focus()

    function focusDiscountInput() {

        if (event.ctrlKey && event.code === "Space") {
            $('#discount').focus()

            event.preventDefault()
        }
    }


    function addProduct(id, product_id, title, code, qty, price, vat, buy_price, unit, stock,description, table) {
        let is_item_added = true

        $('.tr_product_id').each(function (index, value) {
            if ($(this).val() == product_id) {
                is_item_added = false
                let closest_tr = $(this).closest('.mgrid')
                Increase($(this))
                return false
            }
        })

        if (is_item_added == true) {

            let p_vat = Number(vat).toFixed(2)
            let p_total_vat = (Number(vat/100)*Number(price)).toFixed(2)

            let tr = `<tr class="mgrid">
                        <td style="width:3%">
                            <span class="serial">${id}</span>
                            <input type="hidden" class="tr_product_id" name="product_ids[]" value="${product_id}" />
                            <input type="hidden" class="tr_product_cost unit-cost" name="product_cost[]" value="${buy_price}" />
                        </td>
                        <td style="width:15%"> ${title}
                        <input type="hidden" name="product_titles[]" value="${title}"/>
                        </td>
                        <td style="width:10%" class="text-left">
                        <input type="hidden" name="product_codes[]" value="${code}"/>
                        ${code}
                        </td>
                        <td style="width: 10%">
                            <input type="text" name="commends[]" class="form-control" autocomplete="off" placeholder="Commends">
                            <input type="hidden" name="description[]" placeholder="Add Description" value="${description}" class="form-control" autocomplete="off">
                        </td>
                        <td style="width:10%" class="text-center">
                        <div class="input-group">
                                    <input class="form-control quantity input-sm text-center" id="product-${product_id}" type="number"
                                    onkeyup="calculateSubtotal()" name="product_qty[]" value="${qty}" style="width: 70px">

                                </div>
                                <input type="hidden" class="product_stock" value="${stock}">

                        </td>

                        <td style="width:5%;text-align: center">
                            <strong class="">${unit}</strong>
                        </td>

                        <td style="width:4%; text-align: right">
                            <a href="javascript:void(0)" class="text-danger" onclick="removeField(this)">
                                <i class="far fa-times-circle fa-lg"></i>
                            </a>
                        </td>
                    </tr>`
            table.append(tr)
            calculateSubtotal();
        }
    }




    function removeField(object) {
        $(object).closest('.mgrid').remove()
        serial()
    }

    function serial() {
        $('.serial').each(function (index) {
            $(this).text(index + 1)
        })
    }


    function focusOnEnter(event, id) {


        let keycode = (event.keyCode ? event.keyCode : event.which)

        if (keycode == 13) {
            event.preventDefault()
            $('.' + id).focus()
        }
    }



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
    }



    function addItemOnEnter(object, e) {

        if (e.which == 13) {

            let _this = $(object)

            let product_id = _this.find('.product_id').text()
            let product_title = _this.find('.product-title').text()
            let product_code = _this.find('.sku-code').text()
            let product_price = _this.find('.product-price').text()
            let product_vat = _this.find('.product-vat').text()
            let buy_price = _this.find('.buy-price').text()
            let unit = _this.find('.product-unit').text()

            let product_stock = _this.find('.product-stock').text()

            let table = $('#pos-table tbody')
            let length = $('#pos-table tbody tr').length + 1


            if (product_id != '') {

                if (product_stock > 0) {
                    // add item into the sale table
                    addItem(length, product_id, product_title.trim(), product_code, 1, product_price.trim() ?? 0,
                        product_vat ?? 0, buy_price, unit, product_stock ?? 0, table);


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
