<script>
    $(document).on('keyup', ".product-search-input", $.debounce(500, function(e) {
        getProductInfo(this, e)
    }));
    function getProductInfo(params, event, page) {
        let _this = $(params)
        let value = _this.val()

        event.preventDefault()
        if (event.which != 38 && event.which != 40) {
            //     if (event.which == 17) {
            if (value != '') {

                $.ajax({
                    type:'GET',
                    url: "{{ url('dokani/product/get-searchable-products?page=') }}" + page,
                    data: {
                        search: value,
                    },
                    beforeSend: function(){
                        $('.ajax-loader').css("visibility", "visible");
                    },
                    success:function(data) {
                        selectedLiIndex = -1
                        products(data, value)

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

            // let price = Number(price).toFixed(2)
            let p_vat = Number(vat).toFixed(2)
            let p_total_vat = (Number(vat/100)*Number(price)).toFixed(2)
            // let buy_price = Number(buy_price).toFixed(2)

            let tr = `<tr class="mgrid">
                <td style="width:3%">
                    <span class="serial">${id}</span>
                    <input type="hidden" class="tr_product_id" name="product_ids[]" value="${product_id}" />
                </td>
                <td style="width:15%"> ${title}
                <input type="hidden" name="product_titles[]" value="${title}"/>
                </td>
                <td style="width:10%" class="text-left">
                <input type="hidden" name="product_codes[]" value="${code}"/>
                ${code}
                </td>
                <td style="width: 10%">
                     <input type="text" name="description[]" placeholder="Add Description" value="${description}" class="form-control" autocomplete="off">
                </td>
                <td style="width:10%" class="text-left">
                <div class="input-group">
                            <input class="form-control product_qty input-sm text-center" id="product-${product_id}" type="number"
                                 onkeyup="calculateEachRowSubtotal(this)" name="product_qty[]" value="${qty}" style="width: 70px">

                        </div>
                        <input type="hidden" class="product_stock" value="${stock}">

                </td>

                <td style="width:5%">
                    <strong class="">${unit}</strong>
                </td>
                <td style="width:10%">
                    <input type="text" name="product_price[]" class="form-control product-cost input-sm unit-price" onkeyup="calculateEachRowSubtotal(this)" value="${Number(price).toFixed(2)}"
                        autocomplete="off">
                    <input type='hidden' name="buy_price[]" value="${buy_price}">

                </td>

                <td style="width:15%">
                        <div class="input-group">
                            <input class="form-control product-discount-percent text-center input-sm" onkeyup="calculateDiscountAmount(this)" type="text" placeholder="Percent">
                            <span class="input-group-addon" style="padding: 6px 2px !important;">
                                <i class="fa fa-percent"></i>
                            </span>
                            <input type="text" name="product_discount[]" class="form-control product-discount input-sm text-center" onkeyup="calculateDiscountPercentage(this)" autocomplete="off" placeholder="Flat">
                        </div>
                    </td>

                <td style="width:10%;text-align: right">
                    <input type="hidden" name="product_vat[]" value="${p_vat}" class="product_vat"/>
                    <strong>${p_vat}</strong>
                </td>
                <td style="width: 5%;display: none">
                    <input type="hidden" name="vat_total[]" value="${p_vat}"/>
                    <strong class="sub_total_vat">${p_total_vat}</strong>
                </td>
                <td style="width:10%" class="text-right">
                        <input type="hidden" name="subtotal[]" value="${price}"/>
                        <strong class="subtotal">${Number(price)+ (Number(vat/100)*Number(price))}</strong>
                        <strong style="display: none" class="subtotal-without-discount-and-vat">${Number(price)+ (Number(vat/100)*Number(price))}</strong>
                    </td>


                <td style="width:4%; text-align: right">
                    <a href="javascript:void(0)" class="text-danger" onclick="removeField(this)">
                        <i class="far fa-times-circle fa-lg"></i>
                    </a>
                </td>
            </tr>`
            table.append(tr)
            calculateTotalVat()
            calculatePayable()
        }
    }



    function calculateSubtotal()
    {
        let subtotal = 0;

        $('.subtotal-without-discount-and-vat').each(function () {
            subtotal += Number($(this).text());
        })

        $('#subtotal').val(subtotal.toFixed(2));

        calculatePayable();
        calculateDueAmount();
    }


    function calculateEachRowTotalVatAmount(object)
    {
        let THIS = $(object).closest('tr');
        let unitPrice = Number(THIS.find('.unit-price').val());
        let quantity = Number(THIS.find('.product_qty').val());
        let vatPercent = Number(THIS.find('.product_vat').val());
        let eachTotalVatAmount = (vatPercent / 100) * (unitPrice * quantity)

        THIS.find('.vat_total').text(eachTotalVatAmount.toFixed(2));

        calculateTotalVat()
    }



    function calculateEachRowSubtotal(object)
    {

        let THIS = $(object).closest('tr');
        let unitPrice = Number(THIS.find('.unit-price').val());
        let quantity = Number(THIS.find('.product_qty').val());
        let discountAmount = Number(THIS.find('.product-discount').val());
        let vatAmount = Number(THIS.find('.vat_total').text());
        let stock = Number(THIS.find('.product_stock').val());

        if (stock >= quantity){
            let subtotal = ((unitPrice * quantity) + vatAmount) - discountAmount;
            let subtotalWithOutDiscountAndVat = unitPrice * quantity;

            THIS.find('.subtotal').text(subtotal.toFixed(2));
            THIS.find('.subtotal-without-discount-and-vat').text(subtotalWithOutDiscountAndVat.toFixed(2));
            calculateSubtotal()
        }
        else {
            toastr.warning('No Available Quantity')
            THIS.find('.product_qty').val(stock)
        }


    }



    function Decrease(object) {
        let _this = $(object)
        let input = _this.closest('.mgrid').find('.product_qty')
        let qty = input.val()
        if (qty > 1) {
            input.val(Number(qty - 1))
        }

        calculateEachRowTotalVatAmount(object)
        calculateDiscountAmount(object)
        calculateEachRowSubtotal(object)
        calculateSubtotal()
    }

    function Increase(object) {
        let _this = $(object)
        let input = _this.closest('.mgrid').find('.product_qty')
        let qty = input.val()
        input.val(Number(qty) + 1)

        calculateEachRowTotalVatAmount(object)
        calculateDiscountAmount(object)
        calculateEachRowSubtotal(object)
        calculateSubtotal()
    }


    function removeField(object) {
        $(object).closest('.mgrid').remove()
        serial()
        calculateTotalDiscount()
        calculateTotalVat()
        calculateSubtotal()
    }

    function serial() {
        $('.serial').each(function (index) {
            $(this).text(index + 1)
        })
    }


    function calculatePayable()
    {
        let subtotal = Number($('#subtotal').val());
        let pre_due = Number($('#previous_due').val())
        let totalDiscountAmount = Number($('#discount').val());
        let totalVatAmount = Number($('#total_vat').val());
        let deliveryCharge = Number($('#delivery_charge').val());
        let payable = (subtotal + deliveryCharge + totalVatAmount + pre_due) - totalDiscountAmount;

        $('#payable').val(payable.toFixed(2));

        calculateDueAmount()

    }


    function calculateDueAmount()
    {
        let payable = Number($('#payable').val());
        let paidAmount = Number($('#paid_amount').val());

        let dueAmount = payable - paidAmount;

        $('#due_amount').val(dueAmount.toFixed(2));
        $('.due_amount').text(dueAmount.toFixed(2));
    }




    // function DecreaseOrder(object) {
    //     let _this = $(object)
    //     let input = _this.closest('.mgrid').find('.product_qty')
    //     let qty = input.val()
    //     if (qty > 1) {
    //         input.val(Number(qty - 1))
    //     }
    //     updateCart(object)
    // }
    //
    // function IncreaseOrder(object) {
    //     let _this = $(object)
    //     let input = _this.closest('.mgrid').find('.product_qty')
    //     let qty = input.val()
    //     input.val(Number(qty) + 1)
    //     updateCart(object)
    // }
    //
    //


    // function updateCart(object, event) {
    //
    //     var discount = 0;
    //
    //     let _this = $(object).closest('.mgrid')
    //
    //     let qty = _this.find('.product_qty').val()
    //     let price = _this.find('.product-cost').val()
    //     let vat   = _this.find('.product_vat').val()
    //
    //     let product_discount   = _this.find('.product-discount').val()
    //
    //     discount = Number(product_discount * qty)
    //
    //     if (qty <= 0) {
    //         _this.find('.product_qty').val(qty)
    //     }
    //
    //
    //     let total = (((Number(qty) * Number(price)) + (Number(qty) * Number(vat/100)*Number(price)))-Number(discount)).toFixed(2)
    //     let vat_total = (Number(qty) * Number(vat/100)*Number(price)).toFixed(2)
    //
    //     _this.find('.subtotal').text(total)
    //     _this.find('.sub_total_vat').text(vat_total)
    //
    //
    //     calculate()
    //
    //
    //
    //     // if (event.which == 13) {
    //     //     $('.product-search-input').focus()
    //     //
    //     // }
    // }


    // function calculate() {
    //     let price = 0
    //     let vat = 0
    //
    //     $('.subtotal').each(function () {
    //         price += Number($(this).text())
    //
    //     })
    //
    //     $('.sub_total_vat').each(function () {
    //         vat += (Number($(this).text()))
    //
    //     })
    //     // let total       = Number($('#total').val() | 0)
    //     let delivery_charge = Number($('#delivery_charge').val())
    //     let pre_due = Number($('#previous_due').val())
    //     let discount = Number($('#discount').val())
    //     let grand_total = (price + pre_due) - discount
    //     let paid_amount = Number($('#paid_amount').val())
    //     let due_amount = Number(grand_total - paid_amount).toFixed(2)
    //
    //     if (delivery_charge){
    //         due_amount = Number(due_amount + delivery_charge).toFixed(2)
    //         $('#due_amount').val(due_amount)
    //     }
    //
    //
    //
    //
    //     $('#total').val(price)
    //     $('#grand_total').val(grand_total)
    //     $('#due_amount').val(due_amount)
    //     $('#total_vat').val(vat)
    //
    // }



    $('#discount,#paid_amount,#delivery_charge').keyup(function () {

        // calculate()
    })

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

        // addItemOnEnter($('.search-product').find(`li:eq(${selectedLiIndex})`), e)
    }



    function calculateDiscountAmount(object)
    {
        let THIS            = $(object).closest('tr');
        let discountPercent = Number(THIS.find('.product-discount-percent').val());
        let quantity        = Number(THIS.find('.product_qty').val());
        let unitPrice       = Number(THIS.find('.unit-price').val());
        let totalPrice      = quantity * unitPrice;

        let discountAmount  = (totalPrice * discountPercent) / 100;

        THIS.find('.product-discount').val(discountAmount.toFixed(2));

        // updateCart(THIS.find('.product-discount'));

        calculateTotalDiscount();
        calculateEachRowSubtotal(object)
    }








    function calculateDiscountPercentage(object)
    {
        let THIS            = $(object).closest('tr');
        let discountAmount  = Number(THIS.find('.product-discount').val());
        let quantity        = Number(THIS.find('.product_qty').val());
        let unitPrice       = Number(THIS.find('.unit-price').val());
        let totalPrice      = quantity * unitPrice;

        let discountPercent  = (discountAmount / totalPrice) * 100;

        THIS.find('.product-discount-percent').val(discountPercent.toFixed(2));

        calculateTotalDiscount();
        calculateEachRowSubtotal(object)
    }




    function calculateTotalDiscount()
    {
        let totalDiscount = 0;

        $('.product-discount').each(function () {
            totalDiscount += Number($(this).val());
        });

        let pointDiscount = Number($('#pointDiscount').val());
        totalDiscount = totalDiscount + pointDiscount;

        $('#discount').val(totalDiscount.toFixed(2));

        calculateSubtotal()
    }




    function calculateTotalVat()
    {
        let totalVat = 0;

        $('.vat_total').each(function () {
            totalVat += Number($(this).text());
        });

        $('#total_vat').val(totalVat.toFixed(2));

        calculateSubtotal()
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
