
<script>


    $('#account_type_id').on('change', function() {

        let account_id = $(this).find(':selected').val();
        let paid_amount = $('#paid_amount').val()

        $.ajax({
            type:'GET',
            url: "{{ route('dokani.get-account-balance-ajax') }}",
            data: {
                _token: '{!! csrf_token() !!}',
                account_id: account_id,
            },
            beforeSend: function(){
                $('.ajax-loader-acc').css("visibility", "visible");
            },
            success:function(data) {

                if (data.balance <= 0 || !data.balance){

                    $('.balance').text(data.balance ?? 0)
                    toastr.warning('No available balance');

                    $("#submit_btn").prop("disabled", true);
                }
                else if (data.balance < paid_amount){
                    toastr.warning('Paid amount can not be bigger then balance');

                    $("#submit_btn").prop("disabled", true);
                }
                else {
                    $("#submit_btn").prop("disabled", false);
                    $('.balance').text(data.balance)
                    $('.acc_balance').val(data.balance);
                }

            },
            complete: function(){
                $('.ajax-loader-acc').css("visibility", "hidden");
            }
        });

    });

</script>
