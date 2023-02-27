function addItem(id, product_id, title, code, qty, price, table, isExpireFieldAvailable = false) {
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

        let expireField = '';

        if (isExpireFieldAvailable) { // if isExpireFieldAvailable = false
            expireField = `<td style="width:15%" class="text-left">
                               <input type="date" name="expiry_at[]" class="form-control date-picker" id="date" autocomplete="off">
                            </td>`
        }

        let tr = `<tr class="mgrid">
                <td style="width:5%">
                    <span class="serial">${id}</span>
                    <input type="hidden" class="tr_product_id" name="product_ids[]" value="${product_id}" />
                </td>
                <td style="width:20%"> ${title}
                <input type="hidden" name="product_titles[]" value="${title}"/>
                </td>
                <td style="width:15%" class="text-right">
                <input type="hidden" name="product_codes[]" value="${code}"/>
                ${code}
                </td>

                ${expireField}


                    <td style="width:20%" class="text-left">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <a href="javascript:void(0)" onclick="Decrease(this)"><i class="fa fa-minus"
                                           style="color: rgb(126, 3, 3)"></i></a>
                            </span>
                            <input class="form-control product_qty input-sm" id="product-${product_id}" type="number" onkeydown="focusOnEnter(event,'product-search-input')" onkeyup="updateCart(this,event)" name="product_qty[]" value="${qty}">
                            <span class="input-group-addon">
                                <a href="javascript:void(0)" onclick="Increase(this)"><i class="fa fa-plus"></i></a>
                            </span>
                        </div>
                </td>
                <td style="width:20%">
                    <input type="text" name="product_price[]" class="form-control product-cost input-sm" onkeyup="updateCart(this,event)" value="${price}"
                        autocomplete="off">
                </td>

                <td style="widht:20%">
                <input type="hidden" name="subtotal[]" value="${price}"/>
                <strong class="subtotal">${price}</strong>
                </td>
                <td style="widht:5%">
                    <a href="javascript:void(0)" class="text-danger" onclick="removeField(this)">
                        <i class="ace-icon far fa-times-circle bigger-120"></i>
                    </a>
                </td>
            </tr>`
        table.append(tr)
        calculate()
    }
}



function Decrease(object) {
    let _this = $(object)
    let input = _this.closest('.mgrid').find('.product_qty')
    let qty = input.val()
    if (qty > 1) {
        input.val(Number(qty - 1))
    }
    updateCart(object)
}

function Increase(object) {
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

    if (qty <= 0) {
        qty = 1
        _this.find('.product_qty').val(qty)
    }


    let total = ((Number(qty) * Number(price))).toFixed(2)

    _this.find('.subtotal').text(total)


    calculate()



    // if (event.which == 13) {
    //     $('.product-search-input').focus()

    // }
}



function calculate() {
    let price = 0

    $('.subtotal').each(function () {
        price += Number($(this).text())
    })


    // let total       = Number($('#total').val() | 0)
    let pre_due = Number($('#previous_due').val())
    let discount = Number($('#discount').val())
    let grand_total = (price + pre_due) - discount
    let paid_amount = Number($('#paid_amount').val())
    let due_amount = grand_total - paid_amount



    $('#total').val(price)
    // $('.payable_amount').text(grand_total)
    // $('#payable_amount').val(grand_total)
    $('#grand_total').val(grand_total)
    $('.grand_total').text(grand_total)
    $('#due_amount').val(due_amount)
    $('.due_amount').text(due_amount)

}



$('#discount,#paid_amount').keyup(function () {

    calculate()
})



// $('#paid_amount').keyup(function () {
//     let _this = $(this)
//     let paid_amount = Number(_this.val() || 0)
//     let grand_total = Number($('#grand_total').val() | 0)
//     $('#due_amount').val(grand_total - paid_amount)

// })





function focusOnEnter(event, id) {


    let keycode = (event.keyCode ? event.keyCode : event.which)

    if (keycode == 13) {
        event.preventDefault()
        $('.' + id).focus()
    }
}
