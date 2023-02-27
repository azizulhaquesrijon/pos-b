<!------- START ------- FOR SEARCH ANY PRODUCT ------>
<script>


    function checkStockValidity(obj) {
        let current_qty = Number($(obj).closest('tr').find('.current-stock').text());
        if (Number($(obj).val()) > current_qty) {
            $(obj).val('');
            warning('toastr', 'Insufficiant stock!');
        }
        totalQuantity();
    }



    function totalQuantity() {
        let totalQuantity = 0
        let totalAmount = 0
        $('.transfer-quantity').each(function() {
            totalQuantity += Number($(this).val() ?? 0);
            totalAmount += Number($(this).closest('tr').find('.transfer-quantity').val() ?? 0) * Number($(this)
                .closest('tr').find('.purchase-price').val() ?? 0);

        })

        $('.total-quantity').val(totalQuantity);
        $('.total-amount').val(totalAmount);

    }




    function calculateAllAmount() {
        totalQuantity();
    }



    function resetTable() {
        $(".stock-table-body").empty();
        totalQuantity();
    }



    function branchSelectValidation(obj, appliedEvent) {
        let fromBranch = $('#branch_id').val();
        let toBranch = $('#to_branch_id').val();

        if (fromBranch == '' || toBranch == '') {

            $(obj).val('');
            warning('toastr', 'Please select branch first!');

        } else {
            if (fromBranch == toBranch) {
                warning('toastr', "From and To branch can't be same!");
            } else {
                searchAnyProduct(obj, appliedEvent)
            }
        }
    }




    $(document).on('click', '.remove-btn', function(){
        $(this).closest('tr').remove();
        calculateAllAmount();
    })
</script>



<script>
    $(document).on('keyup', ".product-search-input", $.debounce(500, function(e) {
        getProductInfo(this, e)
    }));


    function getProductInfo(params, event) {
        let _this = $(params)
        let value = _this.val()
        event.preventDefault()

        let branch_id = $('#branch_id option:selected').val()

        $.ajax({
            type: 'POST',
            url: "{{ route('dokani.get-searchable-products-ajax') }}",
            data: {
                _token: '{!! csrf_token() !!}',
                search: value,
                branch_id: branch_id
            },
            success: function(data) {

                products(data)

            },
        });

    }






    function products(data) {

        let table = $('#purchase-table tbody');
        let length = $('#purchase-table tbody tr').length + 1;
        let li = `<ul class="dropdown-menu search-product" role="menu" style="z-index: 99999999">`

        data.map(function(value) {

            li += `<li class="single-product-li dropdown-item" onclick="AddToTable(this)">
                        <a href="javascript:void(0)" style="display: grid; grid-template-columns: auto auto auto auto;">
                            <span class="product-title" style=""> ${value.name}</span>
                            <span style="text-align:right;">(Stock:<span class="product-stock">${value.stock}</span>)</span>
                            <span  style="text-align:right;">Barcode: <span class="sku-code">${value.product_code}</span></span>
                            <span  style="text-align:right;">Price: <span class="product-price">${value.product_cost}</span></span>
                            <span class="product-unit" style="display: none">${value.unit}</span>
                            <span class="description" style="display: none">${value.product_description}</span>
                            <p style="display: none" class="product_id">${value.id}</p>
                            <input type="hidden" class="hidden-product-stock" value="${value.stock}">
                        </a>
                    </li>`
        })

        li += '</ul>'


        $('.product-search').html(li)

        var mouse_is_inside = false;

        $(document).ready(function() {
            $('.search-product').click(function() {
                mouse_is_inside = true;
            }, function() {
                mouse_is_inside = false;
            });

            $("body").mouseup(function() {
                if (!mouse_is_inside) $('.search-product').hide();
            });
        });

    }


    function AddToTable(object)
    {
        let _this           = $(object);
        let product_id      = _this.closest('.single-product-li').find('.product_id').text();
        let product_title   = _this.closest('.single-product-li').find('.product-title').text();
        let product_code    = _this.closest('.single-product-li').find('.sku-code').text();
        let product_price   = Number(_this.closest('.single-product-li').find('.product-price').text()).toFixed(2);
        let unit            = _this.closest('.single-product-li').find('.product-unit').text();
        let description     = _this.closest('.single-product-li').find('.description').text();
        let product_stock   = _this.closest('.single-product-li').find('.product-stock').text();
        let length          = $('#purchase-table tbody tr').length + 1;

        if (description == 'null'){
            description = ''
        }
        let is_added = true;

        $('.stock-table-body tr').each(function(){
            if ($(this).find('.product-id').val() == product_id) {
                is_added = false;
            }
        })
        if (is_added == true) {
            $('.stock-table-body').append(`<tr>
                                            <td class="product-title">
                                                ${product_title}
                                                <input type="hidden" class="product-id" name="product_ids[]" value="${product_id}">
                                                <input type="hidden" name="current_stocks[]" value="${product_stock}">
                                            </td>
                                            <td class="barcode">${product_code}</td>
                                            <td class="unit">${unit}</td>
                                            <td class="unit-cost">
                                                <input type="text" name="unit_costs[]" class="form-control input-sm purchase-price" value="${product_price}">
                                            </td>
                                            <td class="text-center"><span class="current-stock">${product_stock}</span></td>
                                            <td class="text-center">
                                                <input type="text" name="transfer_qtys[]" class="form-control transfer-quantity input-sm" placeholder="Enter Qty" onkeyup="checkStockValidity(this)">
                                            </td>
                                            <td class="text-center comment">
                                                <input type="text" name="comments[]" class="form-control input-sm" placeholder="Type comment">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-xs btn-danger remove-btn">
                                                    <i class="far fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>`)
        }
    }
</script>
<!----------------------- END ------------------------->
