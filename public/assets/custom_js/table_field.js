function addItem(id, product_id, title, code, qty, price, vat, stock, table) {



    let is_item_added = true

    let customer_discount = $('#customer option:selected').data('discount');

    if (typeof customer_discount === "undefined") {
        customer_discount = 0;
    }



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

        // <td style="width:5%">
        //             <span class="serial">${id}</span>
        //             <input type="hidden" class="tr_product_id" name="product_ids[]" value="${product_id}" />
        //         </td>
        let tr = `<tr class="mgrid">
                    <td style="width:15%;">${title}
                        <input type="hidden" class="tr_product_id" name="product_ids[]" value="${product_id}" />
                        <input type="hidden" name="product_titles[]" value="${title}"/>
                    </td>
                    <td style="width:10%; padding: 0px !important" class="text-center">
                        <input type="hidden" name="product_codes[]" value="${code}"/>
                        <input class="form-control" readonly value="${code}" style="height: 46px; border: 0px; background: white !important; padding: 2px !important">
                    </td>
                    <td style="width:10%; padding: 0px !important" class="text-center">
                        <input type="hidden" class="product_stock" value="${stock}">
                        <input class="form-control" readonly value="${parseFloat(stock).toFixed(2)}" style="height: 46px; border: 0px; background: white !important; padding: 2px !important">
                    </td>
                    <td style="width:18%" class="text-left">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <a href="javascript:void(0)" onclick="Decrease(this)"><i class="fa fa-minus" style="color: rgb(126, 3, 3)"></i></a>
                            </span>
                            <input class="form-control product_qty input-sm only-number text-center" id="product-${product_id}" type="text" onkeyup="calculateEachRowSubtotal(this)" name="product_qty[]" value="${qty}">
                            <span class="input-group-addon">
                                <a href="javascript:void(0)" onclick="Increase(this)"><i class="fa fa-plus"></i></a>
                            </span>
                        </div>
                    </td>

                    <td style="width:15%">
                        <input type="text" name="product_price[]" class="form-control product-cost text-right input-sm unit-price" onkeyup="calculateEachRowSubtotal(this)" value="${Number(price).toFixed(2)}" autocomplete="off">
                    </td>

                    <td style="width:24%">
                        <div class="input-group">
                            <input class="form-control product-discount-percent text-center input-sm" value="${Number(customer_discount)}" onkeyup="calculateDiscountAmount(this)" data-cust_discount="${Number(customer_discount)}" type="text" placeholder="Percent">
                            <span class="input-group-addon" style="padding: 6px 2px !important;">
                                <i class="fa fa-percent"></i>
                            </span>
                            <input type="text" name="product_discount[]" class="form-control product-discount input-sm text-center" value="${Number((customer_discount * price)/100)}" onkeyup="calculateDiscountPercentage(this)" autocomplete="off" placeholder="Flat">
                        </div>
                    </td>

                    <td style="width:8%" class="text-right">
                        <input type="hidden" name="product_vat[]" value="${p_vat}" class="product_vat"/>
                        <strong>${p_vat}</strong>
                    </td>

                    <td style="width:15%" class="text-right">
                        <input type="hidden" name="subtotal[]" class="subtotal-indu-inp" value="${Number(price)+ (Number(vat/100)*Number(price)) - Number((customer_discount * price)/100)}"/>
                        <strong class="subtotal">${Number(price)+ (Number(vat/100)*Number(price)) - Number((customer_discount * price)/100)}</strong>
                        <strong style="display: none" class="subtotal-without-discount-and-vat">${Number(price)+ (Number(vat/100)*Number(price)) - Number((customer_discount * price)/100)}</strong>
                    </td>

                    <td style="display: none">
                        <input type="hidden" value="${p_vat}"/>
                        <strong class="vat_total">${p_total_vat}</strong>
                    </td>
                    <td style="width:5%">
                        <a href="javascript:void(0)" class="text-danger" onclick="removeField(this)">
                            <i class="ace-icon far fa-times-circle bigger-120"></i>
                        </a>
                    </td>
                </tr>`
        // table.append(tr)



        if (countItemInCart() === 0) {
            table?.append(tr)
        } else {
            $('#pos-table tbody tr:first').before(tr);
        }

        calculateTotalVat()
        calculatePayable()
        calculateTotalDiscount()
        // calculate()
    }
}


function countItemInCart()
{
    let count = 0;
    $('.mgrid').each(function () {
        count++;
    })

    return count;
}



function calculateSubtotal()
{
    let subtotal = 0;

    $('.subtotal-without-discount-and-vat').each(function () {
        subtotal += Number($(this).text());
    })

    $('#subtotal').val(subtotal.toFixed(2));
    $('.pos-subtotal').text(subtotal.toFixed(2));
    $('#all_subtotal').val(subtotal.toFixed(2));

    calculateTotalVat();
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
        THIS.find('.subtotal-indu-inp').val(subtotal.toFixed(2));
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
    let totalDiscountAmount = Number($('#discount').val());
    let totalVatAmount = Number($('#total_vat').val());
    let deliveryCharge = Number($('#delivery_charge').val());
    console.log(totalDiscountAmount);
    let payable = (subtotal + deliveryCharge + totalVatAmount) - totalDiscountAmount;

    $('#payable').val(payable.toFixed(2));
    $('.payable').text(payable.toFixed(2));
    $('.delivery_charge').text(deliveryCharge.toFixed(2));

    calculateDueAmount()
}


function calculateDueAmount()
{
    let payable = Number($('#payable').val());
    let paidAmount = Number($('#paid_amount').val());
    // let changeAmount = Number($('#change_amount').val());

    var dueAmount = 0;
    var changeAmount = 0;


    if(payable > paidAmount){
        dueAmount = payable - paidAmount;
    }else{
        changeAmount = paidAmount - payable;
    }
    // let dueAmount = payable - paidAmount + changeAmount;
    // let dueAmount = payable - paidAmount;

    $('#due_amount').val(dueAmount.toFixed(2));
    $('.due_amount').text(dueAmount.toFixed(2));
    $('.paid_amount').text(paidAmount.toFixed(2));

    if(changeAmount > 0){
        $('.change_amount_account').show();
    }else{
        $('.change_amount_account').hide();
    }
    $('.change_amount').text(changeAmount.toFixed(2));
    $('#change_amount').val(changeAmount.toFixed(2));

    // if (dueAmount != 0){
    //     $(".save-btn").prop("disabled", true);
    //     $(".save-btn").attr("title","Due amount should be 0");
    // }
    // else {
    //     $(".save-btn").prop("disabled", false);
    //     $(".save-btn").attr("title","Sale");
    // }
}

// function calculate() {
//     let price = 0
//     let vat = 0

//     $('.subtotal').each(function () {
//         price += Number($(this).text())
//     })

//     $('.vat_total').each(function () {
//         vat += Number($(this).text())
//     })
//     var discount = 0;
//     // let total       = Number($('#total').val() | 0)
//     let delivery_charge = Number($('#delivery_charge').val())
//     let paid_amount = Number($('#paid_amount').val())
//     let change_amount = Number($('#change_amount').val())
//     // console.log(change_amount)

//     if ($('.sale_discount_type').find(":selected").val() == '৳'){
//         discount = Number($('#discount').val())
//     }else {
//         discount = (Number($('#discount').val())/ 100 * price)
//     }
//     let grand_total = (price) - discount
//     let due_amount = Math.ceil((grand_total - paid_amount) + change_amount);
//     if (delivery_charge){
//         due_amount = due_amount + delivery_charge
//         $('#due_amount').val(due_amount)
//     }

//     if (due_amount != 0){

//         $(".save-btn").prop("disabled", true);
//         $(".save-btn").attr("title","Due amount should be 0");
//     }
//     else {
//         $(".save-btn").prop("disabled", false);
//     }




//     $('#total').val(price)
//     $('#subtotal').val(grand_total)
//     $('#due_amount').val(due_amount)
//     $('#total_vat').val(vat)

// }



$('#discount,#paid_amount,#delivery_charge,#change_amount').keyup(function () {

    // calculate()
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




function calculateDiscountAmount(object)
{
    let THIS            = $(object).closest('tr');
    let brackDiscount   = $(object).data('cust_discount');

    let discountPercent = Number(THIS.find('.product-discount-percent').val());
    let quantity        = Number(THIS.find('.product_qty').val());
    let unitPrice       = Number(THIS.find('.unit-price').val());

    if(brackDiscount < discountPercent){
        THIS.find('.product-discount-percent').val(brackDiscount);
        toastr.warning('Customer Max Discount '+brackDiscount);
    }

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
    $('.discount').text(totalDiscount.toFixed(2));

    calculateSubtotal()
}




function calculateTotalVat()
{
    let totalVat = 0;
    let defaultTotalVat = 0;

    let subtotal = $('#subtotal').val();

    let defaultVat = $('#default_vat').val();

    if(defaultVat){
        defaultTotalVat = (subtotal * defaultVat) / 100;
    }

    $('.vat_total').each(function () {
        totalVat += Number($(this).text());
    });

    totalVat = totalVat + defaultTotalVat;

    $('#total_vat').val(totalVat.toFixed(2));
    $('.total_vat').text(totalVat.toFixed(2));

    // calculateSubtotal()
}
