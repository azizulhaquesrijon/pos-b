<script>

    function getProductInfo(params, event) {

        let _this = $(params)
        let value = _this.val()
        event.preventDefault()
        // console.log(value)
        if (event.which != 38 && event.which != 40) {

            if (value != '') {

                $.ajax({
                    type:'POST',
                    url: "{{ route('dokani.get-searchable-products-ajax') }}",
                    data: {
                        _token: '{!! csrf_token() !!}',
                        search: value,
                    },
                    beforeSend: function(){
                        $('.ajax-loader').css("visibility", "visible");
                    },
                    success:function(data) {

                        selectedLiIndex = -1
                        products(data)

                        if (data.length == 1) {
                            AddToRowIfItemOne(data)

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


    function products(data) {
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






    function AddToRowIfItemOne(data) {
        let length = $('#order-table tbody tr').length + 1

        let keycode = (event.keyCode ? event.keyCode : event.which)

        if (keycode == 13) {

            event.preventDefault()

            if (data[0].stock > 0) {

                addItem(length, data[0].id, data[0].name, data[0].product_code, '', data[0]
                    .product_price, $('#order-table tbody'), data[0].stock)

            } else {
                alert('Product is not have enough stock')
            }

            $('.product-search').html('')
            $('#product-' + data[0].id).focus()
            $('.product-search-input').val('')
        }
    }


    function addProduct(id, product_id, title, code, qty, price, vat, table) {
        let is_item_added = true

        $('.tr_product_id').each(function (index, value) {
            if ($(this).val() == product_id) {
                is_item_added = false
                let closest_tr = $(this).closest('.mgrid')
                IncreaseOrder($(this))
                return false
            }
        })

        if (is_item_added == true) {

            let tr = `<tr class="mgrid">
                <td style="width:3%">
                    <span class="serial">${id}</span>
                    <input type="hidden" class="tr_product_id" name="product_ids[]" value="${product_id}" />
                </td>
                <td style="width:20%"> ${title}
                <input type="hidden" name="product_titles[]" value="${title}"/>
                </td>
                <td style="width:15%" class="text-left">
                <input type="hidden" name="product_codes[]" value="${code}"/>
                ${code}
                </td>
                <td style="width: 15%">
                     <input type="text" name="description[]" placeholder="Add Description" class="form-control"autocomplete="off">
                </td>
                <td style="width:10%" class="text-left">
                <div class="input-group">

                            <input class="form-control product_qty input-sm" id="product-${product_id}" type="number" onkeydown="focusOnEnter(event,'product-search-input')" onkeyup="updateCart(this,event)" name="product_qty[]" value="${qty}">

                        </div>

                </td>
                <td style="width:10%">
                    <input type="text" name="product_price[]" class="form-control product-cost input-sm" onkeyup="updateCart(this,event)" value="${Number(price).toFixed(2)}"
                        autocomplete="off">
                </td>

                <td style="widht:20%">
                    <input type="hidden" name="product_vat[]" value="${vat}" class="product_vat"/>
                    <strong>${Math.ceil(Number(vat))}</strong>
                </td>
                <td style="width: 5%">
                    <input type="hidden" name="vat_total[]" value="${vat}"/>
                    <strong class="sub_total_vat">${Math.ceil((Number(vat/100)*Number(price)))}</strong>
                </td>
                <td style="widht:20%">
                <input type="hidden" name="subtotal[]" value="${price}"/>
                <strong class="subtotal">${Math.ceil(Number(price)+ (Number(vat/100)*Number(price)))}</strong>
                </td>


                <td style="widht:5%">
                    <a href="#" class="text-danger" onclick="removeField(this)">
                        <i class="ace-icon fa fa-trash bigger-120"></i>
                    </a>
                </td>
            </tr>`
            table.append(tr)
            calculate()
        }
    }



    function DecreaseOrder(object) {
        let _this = $(object)
        let input = _this.closest('.mgrid').find('.product_qty')
        let qty = input.val()
        if (qty > 1) {
            input.val(Number(qty - 1))
        }
        updateCart(object)
    }

    function IncreaseOrder(object) {
        let _this = $(object)
        let input = _this.closest('.mgrid').find('.product_qty')
        let qty = input.val()
        input.val(Number(qty) + 1)
        updateCart(object)
    }


    function removeField(object) {
        $(object).closest('.mgrid').remove()
        serial()
        calculate()
    }
    function serial() {
        $('.serial').each(function (index) {
            $(this).text(index + 1)
        })
    }

    function updateCart(object, event) {



        let _this = $(object).closest('.mgrid')

        let qty = _this.find('.product_qty').val()
        let price = _this.find('.product-cost').val()
        let vat   = _this.find('.product_vat').val()

        if (qty <= 0) {
            qty = 1
            _this.find('.product_qty').val(qty)
        }


        let total = Math.ceil((Number(qty) * Number(price)) + (Number(qty) * Number(vat/100)*Number(price))).toFixed(2)
        let vat_total = Math.ceil(Number(qty) * Number(vat/100)*Number(price))

        _this.find('.subtotal').text(total)
        _this.find('.sub_total_vat').text(vat_total)


        calculate()



        // if (event.which == 13) {
        //     $('.product-search-input').focus()
        //
        // }
    }


    function calculate() {
        let price = 0
        let vat = 0
        let defaultTotalVat = 0;

        $('.subtotal').each(function () {
            price += Number($(this).text())
        })

        $('.sub_total_vat').each(function () {
            vat += Number($(this).text())
        })
        // let total       = Number($('#total').val() | 0)
        let delivery_charge = Number($('#delivery_charge').val())
        let pre_due = Number($('#previous_due').val())
        let discount = Number($('#discount').val())
        let grand_total = (price + pre_due) - discount
        let paid_amount = Number($('#paid_amount').val())


        let defaultVat   = $('.default_vat').val()

        if(defaultVat){
            defaultTotalVat = (price * defaultVat) / 100;
        }
        let totalVat = Number(defaultTotalVat) + Number(vat)

        let due_amount = grand_total - paid_amount + totalVat;

        let totalPayable = grand_total + totalVat;

        if (delivery_charge){
            due_amount = due_amount + delivery_charge;
            totalPayable += delivery_charge;
            $('#due_amount').val(due_amount)
        }




        $('#total').val(price)
        $('#grand_total').val(grand_total)
        $('#due_amount').val(due_amount)
        $('#total_vat').val(Number(totalVat))
        $('#payable_amount').val(Number(totalPayable))

    }



    $('#discount,#paid_amount,#delivery_charge').keyup(function () {

        calculate()
    })


    function arrowUpDownInit(e) {

        e.preventDefault()

        $('.search-product').find('li').removeClass('background')

        var li = $('.search-product').find('li')

        var selectedItem


        // if (e.which === 40) {
        //
        //     selectedLiIndex += 1
        //
        // } else if (e.which === 38) {
        //
        //     selectedLiIndex -= 1
        // }




        // if (selectedLiIndex < 0) {
        //     selectedLiIndex = 0
        // }
        //
        //
        //
        // if (li.length <= selectedLiIndex) {
        //     selectedLiIndex = 0
        // }

        if (e.which == 40 || e.which == 38) {

            selectedItem = $('.search-product').find(`li:eq(${selectedLiIndex})`).addClass('background')
            select(selectedItem)

        }
        // addItemOnEnter($('.search-product').find(`li:eq(${selectedLiIndex})`), e)

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

