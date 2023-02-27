<script>

    function getReturnProduct(obj, sale_detail_id)
    {

        $.ajax({
            url: `{{ route('dokani.get-return-product') }}`,
            type: 'GET',
            data: {
                sale_detail_id: sale_detail_id
            },

            success: function (response) {

                let db_qdy = Number(response.getReturnProduct.quantity);
                let quantity = $("#qty"+sale_detail_id).val();
                console.log(quantity)
                if (response.getReturnProduct == null) {
                    warning('toster', "This Barcode Already Return");
                    $(obj).val('');

                    return;
                }



                let sale_price = Number(response.getReturnProduct.price);


                    $('#qty').val(quantity);
                    let total_amount = sale_price * quantity;

                let discount_amount = Number(response.getReturnProduct.discount / response.getReturnProduct.quantity)
                //let discount_percent = discount_amount / (sale_price * quantity) * 100

                if (quantity <= db_qdy){
                    $(obj).closest('.exchange-tr').find('.return-product').append(`
                    <tr>
                        <td style="display: none">
                            <input type="text" name="return_sale_detail_id[]"       class="return-sale-detail-id"       value="${sale_detail_id}">
                            <input type="text" name="return_product_id[]"           class="return-product-id"           value="${response.getReturnProduct.product_id}">

                            <input type="text" name="return_sale_price[]"           class="return-sale-price"           value="${response.getReturnProduct.price}">
                            <input type="text" name="return_quantity[]"             class="return_quantity"             value="${ quantity }">
                            <input type="text" name="return_subtotal[]"             class="return-subtotal"             value="${total_amount}">
                        </td>
                        <td class="text-center sn-no"></td>
                        <td>
                            ${response.getReturnProduct.product.name}
                        </td>
                        <td>
                            ${response.getReturnProduct.product.category.name}
                        </td>
                        <td>
                            ${response.getReturnProduct.product.unit.name}
                        </td>

                        <td>
                            ${response.getReturnProduct.product.barcode}
                        </td>
                        <td>
                            <select name="return_type[]" class="select-type">
                                <option value="Good">Good</option>
                                <option value="Damaged">Damaged</option>
                            </select>
                        </td>
                        <td class="text-right">${sale_price.toFixed(2)}</td>
                        <td class="text-center">${ quantity }</td>

                        <td class="text-right return-total">${total_amount.toFixed(2)}</td>
                        <td class="text-center">
                             <a href="javascript:void(0)" onclick="removeItem(this, ${ response.getReturnProduct.product.barcode })"><i class="fa fa-times text-danger"></i></a>
                        </td>
                    </tr>
                `);
                }
                else {
                    warning('toster', "Not Valid quantity");

                    return;
                }


                calculateReturnableDiscount()

                calculateReturnableSubtotal()

                serial($(obj).closest('.exchange-tr').find('.return-product'))
            }
        });
    }




    function removeItem(obj, barcode_id)
    {
        $(obj).closest('.exchange-tr').find('.select-option').find("option[value='"+barcode_id+"']").prop("disabled", false);
        $(obj).closest('.exchange-tr').find('.select-option').find("option[value='']").prop("selected", true);
        $(obj).closest('tr').remove();


        calculateReturnableDiscount()

        calculateReturnableSubtotal()

        calculateExchangeSubtotal()

        calculateExchangeDiscountAmount($('#total_exchange_discount_percent'))
        calculateExchangeDiscountPercent($('#total_exchange_discount_amount'))
        calculateAllAmount()

        serial($(obj).closest('tr'));
    }




    function serial(obj)
    {
        $(obj).find('.sn-no').each(function(index) {
            $(this).text(index + 1)
        })
    }
</script>




<script>
    function getExchangeProduct(obj, event, branch_id)
    {

        let search = $(obj).val();
        let is_serial = $(obj).data('is-barcode');

        let keycode = (event.keyCode ? event.keyCode : event.which);

        if (keycode == 13) {

            let is_return = false;
            let return_count = 0;
            $(obj).closest('.exchange-tr').find('.return-product').find('tr').find('.sn-no').each(function(index) {
                is_return = true;
                return_count = return_count + 1;
            })

            if (is_return === false) {
                warning('toster', "You don't select any return barcode!");
                return;
            }




            if (is_serial == 1) {
                is_barcode = false;
                $('.exchange-tr').find('.barcode').each(function(index) {

                    if ($(this).text() == search) {
                        is_barcode = true;
                    }
                })

                if (is_barcode === true) {
                    warning('toster', "This barcode already exists!");
                    return;
                }
            }







            let exchange_count = 0;
            $(obj).closest('.exchange-tr').find('.exchange-product').find('tr').find('.sn-no').each(function(index) {
                exchange_count = exchange_count + 1;
            })

            if (exchange_count + 1 > return_count) {
                warning('toster', "You can't add more than return barcode!");
                return;
            }





            $.ajax({
                url: `{{ route('dokani.get-exchange-product') }}`,
                type: 'GET',
                data: {
                    search      : search,
                    product_id   : branch_id,
                },
                success: function (response) {

                    console.log(response);
                    let sale_price = Number(response.getExchangeProduct.sell_price);

                    let quantity = 1;
                    quantity = Number(response.getExchangeProduct.stocks.available_quantity);
                    let total_amount = sale_price * 1;

                    let qty_input = `<input type="text" name="exchange_quantity[]" class="qty-input exchange-quantity text-center only-number" value="1" onkeyup="calculateLineTotal(this)">`;

                    $(obj).closest('.exchange-tr').find('.exchange-product').append(`
                        <tr>
                             <td style="display: none">


                                <input type="text" name="exchange_product_id[]"         class="exchange-product-id"         value="${ response.getExchangeProduct.stocks.product_id }"
                                <input type="text" name="product_id[]"                  class="product-id"                  value="${ branch_id }"


                                <input type="text" name="exchange_barcode[]"            class="exchange-barcode"            value="${ response.getExchangeProduct.barcode }">
                                <input type="text" name="exchange_purchase_price[]"     class="exchange-purchase-price"     value="${ response.getExchangeProduct.purchase_price }">
                                <input type="text" name="exchange_sale_price[]"         class="exchange-sale-price"         value="${ response.getExchangeProduct.sell_price }">
                                <input type="text" name="exchange_subtotal[]"           class="exchange-subtotal"           value="${ total_amount }">
                                <input type="text" name="exchange_available_stock[]"    class="exchange-available-stock"    value="${ response.getExchangeProduct.stocks.available_quantity }">
                            </td>
                            <td class="text-center sn-no"></td>
                            <td>
                                ${ response.getExchangeProduct.name }
                            </td>
                            <td>${ response.getExchangeProduct.category.name }</td>
                            <td>${ response.getExchangeProduct.unit.name }</td>
                            <td class="barcode">${ response.getExchangeProduct.barcode }</td>
                            <td>

                            </td>
                            <td class="text-right">${ sale_price.toFixed(2) }</td>
                            <td class="text-center">
                                ${ qty_input }
                            </td>
                            <td class="text-right exchange-total">${ total_amount.toFixed(2) }</td>
                            <td class="text-center">
                                <a href="javascript:void(0)" onclick="removeItem(this, ${ response.getExchangeProduct.barcode })"><i class="fa fa-times text-danger"></i></a>
                            </td>
                        </tr>
                    `);

                    $(obj).val('')

                    calculateExchangeSubtotal()

                    serial($(obj).closest('.exchange-tr').find('.exchange-product'))
                }
            });
        }
    }
</script>




<script>
    function getInvoices(obj) {

        let invoices = $(obj).find('option:selected').data('customer-invoices');
        console.log(invoices)
        $(obj).closest('#saleProductExchangeSearchForm').find('#invoice_no').empty();
        $(obj).closest('#saleProductExchangeSearchForm').find('#invoice_no').append(
            `<option value="" selected>- Select -</option>`);

        $(invoices).each(function (index, invoice) {
            $(obj).closest('#saleProductExchangeSearchForm').find('#invoice_no').append(
                `<option value="${ invoice.invoice_no }">${ invoice.invoice_no }</option>`);
        })
    }
</script>





<script>
    $(".exchange").click(function () {

        $(this).toggleClass("btn-success btn-danger");

        $(this).closest("tr").next("tr").toggleClass("hide");

        if ($(this).closest("tr").next("tr").hasClass("hide")) {
            $(this).closest("tr").next("tr").children("td").slideUp();
            $(this).closest("tr").css('background-color', '#ffffff');
        } else {
            $(this).closest("tr").next("tr").children("td").slideDown();
            $(this).closest("tr").css('background-color', '#deffde');
        }
    });
</script>






{{-- RETURNABLE CALCULATIONS --}}
<script>
    function calculateReturnableDiscount()
    {
        let returnableDiscountAmount = 0;
        let salePrice = 0;

        $('.return-discount-amount').each(function() {

            returnableDiscountAmount += Number($(this).val() * $(this).closest('tr').find('.return-quantity').val());

            salePrice += Number($(this).closest('tr').find('.return-sale-price').val() * $(this).closest('tr').find('.return-quantity').val());
        })

        let discountPercent = (returnableDiscountAmount / salePrice) * 100;

        if (isNaN(discountPercent)) {
            discountPercent = 0;
        }

        $('#total_return_discount_percent').val(discountPercent.toFixed(2));
        $('#total_return_discount_amount').val(returnableDiscountAmount.toFixed(2));

        calculateAllAmount()
    }


    function calculateReturnableSubtotal()
    {
        let returnableTotalCost = 0;
        let returnableSubtotal = 0;

        $('.return-total').each(function() {
            returnableTotalCost += Number($(this).closest('tr').find('.return-purchase-price').val() * $(this).closest('tr').find('.return-quantity').val());
            console.log('return: '+ returnableTotalCost)
            returnableSubtotal += Number($(this).text());
        })

        let returnableDiscountAmount = Number($('#total_return_discount_amount').val());

        $('#total_return_cost').val(returnableTotalCost.toFixed(2));
        $('#total_return_subtotal').val(returnableSubtotal.toFixed(2));
        $('#return_total_amount').val((returnableSubtotal - returnableDiscountAmount).toFixed(2));

        calculateAllAmount()
    }
</script>






{{-- EXCHANGABLE CALCULATIONS --}}
<script>
    function calculateLineTotal(obj)
    {
        let salePrice = Number($(obj).closest('tr').find('.exchange-sale-price').val());
        let quantity = Number($(obj).val());


        let availableStock = Number($(obj).closest('tr').find('.exchange-available-stock').val());

        if (quantity > availableStock) {

            warning('toster', 'Your available stock is ' + availableStock.toFixed(2));

            quantity = availableStock;

            $(obj).val(quantity.toFixed(2));
        }


        let total = salePrice * quantity;

        $(obj).closest('tr').find('.exchange-total').text(total.toFixed(2));

        calculateExchangeSubtotal()
    }






    function calculateExchangeSubtotal()
    {
        let exchangableTotalCost = 0;
        let exchangeSubtotal = 0;

        $('.exchange-total').each(function() {
            exchangableTotalCost += Number($(this).closest('tr').find('.exchange-purchase-price').val() * $(this).closest('tr').find('.exchange-quantity').val());
            exchangeSubtotal += Number($(this).text());
        })

        let exchangeVat        = $('.exchange-vat').val();

        alert(exchangeVat);
        let exchangeVatAmount  = (exchangeSubtotal * exchangeVat) / 100;
        let exchangeGrandtotal = exchangeSubtotal + exchangeVatAmount;

        $('#total_exchange_cost').val(exchangableTotalCost.toFixed(2));
        $('#total_exchange_subtotal').val(exchangeSubtotal.toFixed(2));

        calculateAllAmount();
    }






    function calculateExchangeDiscountAmount(obj)
    {
        let exchangeSubtotal = Number($('#total_exchange_subtotal').val());

        if (exchangeSubtotal <= 0) {
            warning('toster', 'Please Add Exchange Product');

            $('#total_exchange_discount_amount').val(0);
            $(obj).val(0);

            return;
        }

        let exchangableDiscountPercent  = Number($(obj).val());
        let exchangeDiscountAmount   = Number(exchangeSubtotal - (exchangeSubtotal - ( exchangeSubtotal * exchangableDiscountPercent / 100 )));

        $('#total_exchange_discount_amount').val(exchangeDiscountAmount.toFixed(2));

        calculateAllAmount();
    }






    function calculateExchangeDiscountPercent(obj)
    {
        let exchangeSubtotal = Number($('#total_exchange_subtotal').val());

        if (exchangeSubtotal <= 0) {
            warning('toster', 'Please Add Exchange Product');

            $('#total_exchange_discount_percent').val(0);
            $(obj).val(0);

            return;
        }

        let exchangeDiscountAmount = Number($(obj).val());
        let exchangableDiscountPercent = Number((exchangeDiscountAmount / exchangeSubtotal) * 100);

        $('#total_exchange_discount_percent').val(exchangableDiscountPercent.toFixed(2));

        calculateAllAmount();
    }





    function calculateAllAmount()
    {
        let exchangeSubtotal = Number($('#total_exchange_subtotal').val());
        let exchangeDiscountAmount = Number($('#total_exchange_discount_amount').val());
        let exchangeDiscountedSubtotal = Number(exchangeSubtotal - exchangeDiscountAmount);
        let exchangePaidAmount = Number($('#paid_amount').val());


        let returneTotalAmount = Number($('#return_total_amount').val());
        let exchanePayableAmount = exchangeDiscountedSubtotal - returneTotalAmount;
        let maxExchangePayableAmount = Math.ceil(exchanePayableAmount);

        let exchangeRounding = maxExchangePayableAmount - exchanePayableAmount;



        let exchangeDueAmount = maxExchangePayableAmount - exchangePaidAmount;
        let exchangeChangeAmount = exchangePaidAmount - maxExchangePayableAmount;

        if (maxExchangePayableAmount > exchangePaidAmount) {
            exchangeChangeAmount = 0
        }

        if (exchangeChangeAmount > 0) {
            exchangeDueAmount = 0;
        }

        $('#total_exchange_amount').val(exchangeDiscountedSubtotal.toFixed(2));
        $('#payable_amount').val(maxExchangePayableAmount.toFixed(2));
        $('#rounding').val(exchangeRounding.toFixed(2));
        $('#due_amount').val(exchangeDueAmount.toFixed(2));
        $('#change_amount').val(exchangeChangeAmount.toFixed(2));
    }


    function addAccount()
    {
        $('#payment-table').append(accountRow)

        accSerial()

       // getPosAccountsByBranch()

        $('.select2').select2()

        calculatePaymentAmount();
    }



    function calculatePaymentAmount()
    {
        let totalPaymentAmount = 0;
        $('.payment-amount').each(function () {
            totalPaymentAmount += Number($(this).val());
        })

        $('#total_payment_amount').text(totalPaymentAmount.toFixed(2))
        $('#paid_amount').val(totalPaymentAmount.toFixed(2))

        calculateAllAmount()
    }


    calculatePaymentAmount()


    function removeAccount(obj)
    {
        $(obj).closest("tr").remove();
        accSerial();
        calculatePaymentAmount();
    }


    function accSerial()
    {
        $('.acc-sn-no').each(function(index) {
            $(this).text(index + 1)
        })
    }
</script>


<script>
    // function getPosAccountsByBranch()
    // {
    //     let getPosAccounts = $('#branch_id').find('option:selected').data('pos-accounts');
    //
    //     $('.pos-accounts').empty();
    //     $('.pos-accounts').append(`<option value="" selected>- Select -</option>`);
    //
    //     $.each(getPosAccounts, function (key, item) {
    //         $('.pos-accounts').append(`<option value="${ item.id }">${ item.name } &mdash; ${ item.account_no }</option>`).select2();
    //     })
    // }
    //
    // getPosAccountsByBranch()




    function checkAccountExistOrNot(obj)
    {
        let accountId = $(obj).closest('tr').find('.pos-accounts').find('option:selected').val();

        let is_find = 0;

        $('.pos-accounts').each(function(index) {
            let account_id = $(this).val()

            if (accountId == account_id) {

               is_find += 1;
            }
        })


        if (is_find > 1) {
            warning('toster', 'Account already exists!');

            $(obj).closest('tr').find('.pos-accounts').val('');
            $(obj).closest('tr').find('.pos-accounts').select2();
            $(obj).closest('tr').find('.payment-amount').val('');

            calculatePaymentAmount()

            return;
        }
    }




    $('#submitBtn').on('click', function () {


        let returnableSubtotal = Number($('#total_return_subtotal').val());

        if (returnableSubtotal <= 0) {
            warning('toster', 'Please Select Any Return Product');

            return;
        }


        $('#productReturnAndExchangeForm').submit();

    })
</script>
