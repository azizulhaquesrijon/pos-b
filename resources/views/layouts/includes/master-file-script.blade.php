<!-- jquery -->
<script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.query-object.js') }}"></script>



<!-- ace scripts -->
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-tag.min.js') }}"></script>




<script src="{{ asset('assets/js/toastr.min.js') }}"></script>




<!-- sweetalert2 -->
<script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>



<!-- ace element control -->
<script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
<script src="{{ asset('assets/js/ace.min.js') }}"></script>




<script type="text/javascript">
    function withDefault(value, default_value) {
        return value ? value : default_value
    }
</script>






<!-- Autocomplete script -->
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>






<!-- datepicker & timepicker script -->
<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>



<!-- chosen script -->
<script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>




<script src="{{ asset('assets/js/select2.min.js') }}"></script>



<!-- custom toster -->
<script src="{{ asset('assets/custom_js/message-display.js') }}"></script>



<!-- custom keyboard Shortcut -->
<script src="{{ asset('assets/custom_js/keyboardShortcut.js') }}"></script>



<!-- file upload js -->
<script src="{{ asset('assets/custom_js/file_upload.js') }}"></script>



<!-- delete confirm dialog -->
<script type="text/javascript" src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>


<!-- dynamically include script -->
@yield('js')
@yield('script')

<script>
    function log(message) {
        console.log(message)
    }

    let path = window.location.href

    path = path.replace('#', '')

    let selector = "a[href='" + path + "']"


    if (!$(selector).closest('li').hasClass('hasQuery')) {
        path = path.split('?')[0]
    }

    selector = "a[href='" + path + "']"

    selector = selector.replace('edit', '');
    selector = selector.replace('print', '');
    selector = selector.replace('show', '');

    if ($(selector).length < 1) {

        selector = selector.substring(0, selector.lastIndexOf('/'))

        if ($(selector).length < 1) {

            selector = selector.substring(0, selector.lastIndexOf('/'))

            // if ($(selector).length < 1) {

            //     selector = selector.substring(0, selector.lastIndexOf('/'))

            //     if ($(selector).length < 1) {

            //         selector = selector.substring(0, selector.lastIndexOf('/'))
            //     }
            // }
        }
    }



    let a_tag = $(selector)



    let li_tag = a_tag.closest('li')


    if (!$(selector).closest('li').hasClass('unlink') || $(selector).closest('li').hasClass('hasChild')) {
        li_tag.addClass('active')
    }

    li_tag.parents('li').add(this).each(function() {

        $(this).addClass('open')

    });
</script>


<script>

    $('.select2').select2();
</script>

<script>
    $('select').each(function() {
        let selected = $(this).data('selected');

        if (typeof selected === "undefined") {
            return;
        }

        $(this).val(selected).prop('selected', 'selected')

    })
</script>



<!-- Bank Payment -->

<script>

    $('#account_type_id').on('change', function () {

        let name = $(this).find(':selected').text()
        console.log(name)
        if (name == 'Bank'){

            var item =`<label class="control-label col-sm-3 no-padding-right"></label>
                <div class="col-sm-9" style="display: flex">
                    <input type="text" name="check_no" placeholder="Enter Check No" class="form-control">&nbsp;&nbsp;
                    <input type="text" name="check_date" placeholder="Enter Check Date" class="form-control date-picker" autocomplete="off">
                </div>`

            var item1 =`
                <div class="col-sm-12" style="display: flex">
                    <input type="text" name="check_no" placeholder="Enter Check No" class="form-control">&nbsp;&nbsp;
                    <input type="text" name="check_date" placeholder="Enter Check Date" class="form-control date-picker" autocomplete="off">
                </div>`

            $('.bankItem').html(item)
            $('.bankItem1').html(item1)

            $('.date-picker').datepicker({
                autoclose: true,
                format:'yyyy-mm-dd',
                todayHighlight: true
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });
        }
        else {
            $('.bankItem').empty()
        }
    })

</script>

<!-- global scripts -->
<script type="text/javascript">
    // <!-- popover -->
    $('[data-rel=popover]').popover({
        html: true,
        container: 'body'
    });





    // auto fadeout success message, when redirect with success message
    $('.success').fadeIn('slow').delay(10000).fadeOut('slow');




    // alert message display
    function showAlertMessage(message, time = 1000, type = 'error') {
        swal.fire({
            title: type.toUpperCase(),
            html: "<b>" + message + "</b>",
            type: type,
            timer: time
        })
    }



    // success alert message like popup window
    @if (session()->get('message'))
        swal.fire({
        title: "Success",
        html: "<b>{{ session()->get('message') }}</b>",
        type: "success",
        timer: 1000
        });



        // success alert message like popup window
    @elseif (session()->get('success'))
        swal.fire({
        title: "Success",
        html: "<b>{{ session()->get('success') }}</b>",
        type: "success",
        timer: 1000
        });


        // error alert message like popup window
    @elseif(session()->get('error'))
        swal.fire({
        title: "Error",
        html: "<b>{{ session()->get('error') }}</b>",
        type: "error",
        timer: 1000
        });
    @endif




    // input field only number accept method
    function onlyNumber(evnt) {
        let keyCode = evnt.charCode
        let str = evnt.target.value
        let n = str.includes(".")

        if (keyCode == 13) {
            evnt.preventDefault();
        }

        if (keyCode == 46 && n) {
            return false
        }

        if (str.length > 12) {
            showAlertMessage('Number length out of range', 3000)
            return false
        }
        return (keyCode >= 48 && keyCode <= 57) || keyCode == 13 || keyCode == 46
    }


    // input field only number accept class
    $('.only-number').keypress(function() {
        return onlyNumber(event)
    })





    function getPercentAmount(total_amount, percent) {
        return (total_amount * percent) / 100
    }





    function getPercent(total_amount, discount_amount) {
        if (total_amount < 1) {
            return 0;
        }

        return (discount_amount * 100) / total_amount
    }
</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>





<!-- software payment notification script -->
@if (request()->segment(1) != 'em')
    {{-- @include('partials._payment_notification') --}}
@endif
