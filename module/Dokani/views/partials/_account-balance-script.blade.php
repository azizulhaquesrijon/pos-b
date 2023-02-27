<script>

    $('#account_type_id').on('change', function() {

        let account_id = $(this).find(':selected').val();

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
                if (data.balance){
                    $('.balance').text(data.balance)
                    $('.acc_balance').val(data.balance);
                }
                else {
                    $('.balance').text(0)
                }
            },
            complete: function(){
                $('.ajax-loader-acc').css("visibility", "hidden");
            }
        });

    });

</script>



