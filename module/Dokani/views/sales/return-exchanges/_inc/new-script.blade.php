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
            url: '/dokani/product/get-purchase-products',

            data: {
                page: page,
                category_id: category_id
            },
            type: "get",
            datatype: "html"
        }).done(function(data) {
            $(".product-list1").empty().html(data)
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
            url: "/dokani/product/get-purchase-products?category_id=" + $(obj).val(),

        }).then(function(response) {
            $('.product-list1').html(response.data)
        })
    }

    $(document).on('keyup', ".product-search-input", $.debounce(500, function(e) {
        getProductInfo(this, e, 'product-search')
    }));

    $(document).on('keyup', ".return-product-search-input", $.debounce(500, function(e) {
        getProductInfo(this, e, 'return-product-search')
    }));

    function getProductInfo(params, event, searchField) {


        let _this = $(params)
        let value = _this.val()
        let branch_id = $('#branch_id').val();
        // console.log({value, branch_id});
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
                        branch_id: branch_id,
                    },
                    beforeSend: function(){
                        $('.ajax-loader').css("visibility", "visible");
                    },
                    success:function(data) {
                        selectedLiIndex = -1
                        products(data, searchField)

                        if (data.length == 1) {
                            AddToRowIfItemOne(data[0], event)
                        }
                    },
                    complete: function(){
                        $('.ajax-loader').css("visibility", "hidden");
                    }
                });

            } else {
                $('.'+searchField).html('')
            }

        }
        arrowUpDownInit(event)
    }
</script>





<!-- PRODUCT UI LIST -->
<script>
    function products(data, searchField) {

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


        $('.'+searchField).html(li)

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
        let length = $('#purchase-table tbody tr').length + 1

        let keycode = (event.keyCode ? event.keyCode : event.which)

        if (keycode == 13) {

            event.preventDefault()

            // if (data?.stock > 0) {
            let description = data?.product_description
            if (description == null){
                description = ''
            }

            addPurchaseItem(length, data?.id, data?.name, data?.product_code, data?.product_stock, 1, data?.product_price ?? 0,
                data?.unit, description, $('#purchase-table tbody'), data?.product_cost)
            // } else {
            //     alert('Product is not have enough stock')
            // }

            $('.product-search').html('')
            // $('#product-' + data?.id).focus()
            $('.product-search-input').val('').focus()
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

        // addItemOnEnter($('.search-product').find(`li:eq(${selectedLiIndex})`), e)
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
            let product_cost = _this.find('.buy-price').text()
            let product_vat = _this.find('.product-vat').text()
            let product_unit = _this.find('.product-unit').text()
            let description = _this.find('.description').text()

            let product_stock = _this.find('.product-stock').text()

            let table = $('#purchase-table tbody')
            let length = $('#purchase-table tbody tr').length + 1


            if (product_id != '') {

                if (description == null){
                    description = ''
                }

                // if (product_stock > 0) {

                // add item into the sale table
                addPurchaseItem(length, product_id, product_title.trim(), product_code, product_stock, 1, product_price.trim(),
                    product_unit,description,table, product_cost)


                $('.product-search').html('')
                $('.product-search-input').val('').focus()

                $('#product-' + product_id)
                product_id = ''

                // } else {
                //     alert('Product is not have enough stock')
                // }
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



<script>

    function addPurchaseItem(id, product_id, title, code, stock, qty, price,unit, description, table, product_cost) {
        let is_item_added = true

        $('.tr_product_id').each(function (index, value) {
            if ($(this).val() == product_id) {
                is_item_added = false
                let closest_tr = $(this).closest('.mgrid')
                Increase($(this))
                return false
            }
        })
        let customer_wise_discount = $('.customer_discount').val();
        let discount = (price * customer_wise_discount) / 100;

        if (is_item_added == true) {

            let tr = `<tr class="mgrid">
                        <td style="width:4%">
                            <span class="serial">${id}</span>
                            <input type="hidden" class="tr_product_id" name="product_ids[]" value="${product_id}" />
                        </td>
                        <td style="width:10%"> ${title}
                        <input type="hidden" name="product_titles[]" value="${title}"/>
                        </td>
                        <td style="width:10%" class="text-left">
                        <input type="hidden" name="product_codes[]" value="${code}"/>
                        ${code}
                        </td>
                        <td style="width:5%" class="text-left">
                        <input type="hidden" name="product_stocks[]" value="${Number(stock).toFixed(2)}"/>
                        ${Number(stock).toFixed(2)}
                        </td>
                        <td style="width: 10%">
                            <input type="text" name="description[]" placeholder="Add Description" value="${description}" class="form-control" autocomplete="off">
                        </td>
                        @if(optional(auth()->user()->businessProfile)->has_expiry_date == 0)
                            <td style="width:10%" class="text-left">
                                <input type="text" name="expiry_at[]" class="form-control date-picker" id="date" autocomplete="off">
                            </td>
                        @endif
                        <td style="width:5%" class="text-left">
                            <div class="input-group">

                                <input class="form-control product_qty input-sm" id="product-${product_id}"
                                    type="number" onkeydown="focusOnEnter(event,'product-search-input')"
                                    onkeyup="updateCart(this,event)" name="product_qty[]" value="${qty}">

                                </div>
                        </td>
                        <td style="width:5%">
                            <strong class="">${unit}</strong>
                        </td>
                        <td style="width:8%">
                            <input type="text" name="product_discount[]" class="form-control product_discount" onkeyup="calculate()" value="${Number(discount).toFixed(2)}">
                        </td>
                        <td style="width:10%">
                            <input type="text" name="product_price[]" class="form-control product-cost input-sm" onkeyup="updateCart(this,event)" value="${price}" autocomplete="off">
                            <input type="hidden" name="product_cost[]" class="form-control product_cost" value="${product_cost}" readonly>
                        </td>

                        <td style="width:10%">
                        <input type="hidden" class="subtotal-input" name="subtotal[]" value="${price * qty - Number(discount).toFixed(2) }"/>
                        <strong class="subtotal text-right">${price * qty - Number(discount).toFixed(2)}</strong>
                        </td>
                        <td style="width:4%">
                            <a href="#" class="text-danger" onclick="removeField(this)">
                                <i class="far fa-times-circle fa-lg"></i>
                            </a>
                        </td>
                    </tr>`
            table.append(tr)
            calculate()
            $('.date-picker').datepicker({
                autoclose: true,
                format:'yyyy-mm-dd',
                todayHighlight: true
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });
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

        let customer_wise_discount = $('.customer_discount').val();


        let _this = $(object).closest('.mgrid')
        let qty = _this.find('.product_qty').val()
        let price = _this.find('.product-cost').val()

        if (qty <= 0) {
            _this.find('.product_qty').val(qty)
        }


        let total = ((Number(qty) * Number(price))).toFixed(2)
        let discount = Number((total*customer_wise_discount)/100)
        _this.find('.product_discount').val(discount);

        _this.find('.subtotal').text(total - discount)
        _this.find('.subtotal-input').text(total)

        calculate()



        // if (event.which == 13) {
        //     $('.product-search-input').focus()

        // }
    }

    calculate();


    function calculate() {

        /* ----------------- EXCHHANGE TOTAL CALCULATION -------------*/
        let price = totalProductCost = discountAmount = 0
        let customer_wise_discount = $('.customer_discount').val();


        let totalExchangeQty = 0;
        $('.product_qty').each(function () {
            totalExchangeQty += Number($(this).val())
        })
        $('.product_discount').each(function () {
            discountAmount += Number($(this).val())
        })
        $('#total_exchange_quantity').val(totalExchangeQty);

        $('.subtotal').each(function () {
            price += Number($(this).text())
        })
        $('.product_cost').each(function () {
            totalProductCost += Number($(this).val())
        })

        $('#total_exchange_discount_percent').val(customer_wise_discount)

        $('#total_exchange_discount_amount').val(discountAmount)

        let grand_total = price - discountAmount

        let exchange_vat_percent = $('.exchange-vat').val();

        let total_exchange_vat_amount = (grand_total * exchange_vat_percent) / 100;

        grand_total = grand_total + total_exchange_vat_amount;




        $('#total_exchange_cost').val(totalProductCost)
        $('#total_exchange_subtotal').val(price)
        $('#total_exchange_vat_percent').val(grand_total != 0 ? exchange_vat_percent : 0);
        $('#total_exchange_vat_amount').val(total_exchange_vat_amount)
        $('#total_exchange_amount').val(grand_total)



        /* ----------------- NEW PAYABLE TOTAL CALCULATION -------------*/

        let totalReturnAmount   = $('#return_total_amount').val();
        let totalExchangeAmount = $('#total_exchange_amount').val();
        let rounding            = totalExchangeAmount - totalReturnAmount;
        let paymentDiscount     = Number($('#total_exchange_discount_amount').val() ?? 0)
        let totalPayable        = totalExchangeAmount - totalReturnAmount;

        $('#rounding').val(0);
        // $('#payable_amount').val(rounding - paymentDiscount);
        $('#payable_amount').val(totalPayable);

        let paid_amount = $('#paid_amount').val() ?? 0;

        // if(totalPayable < 0){
        //     // paid_amount = 0;
        //     // $('#paid_amount').val(paid_amount);
        // }else{
        // }

        $('#due_amount').val(totalPayable - paid_amount);

        if(totalPayable > 0 && paid_amount > totalPayable){
            $('#due_amount').val(0);
        }

        if(totalPayable > 0 && paid_amount > 0){
            $('#change_amount').val( totalPayable - paid_amount <= 0 ? paid_amount - totalPayable : 0);
        }









    }



    $('#discount,#paid_amount').keyup(function () {

        calculate()
    })



    function focusOnEnter(event, id) {


        let keycode = (event.keyCode ? event.keyCode : event.which)

        if (keycode == 13) {
            event.preventDefault()
            $('.' + id).focus()
        }
    }
</script>

