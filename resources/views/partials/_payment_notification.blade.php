
@php
    if(auth()->user()->type == 'owner'){

        $end_date = optional(auth()->user()->package)->end_date;
    }
    else{

        if(auth()->user()->id == 1){
            $end_date = $pack->now();
        }else{
            if(auth()->user()->dokan_id == null){
                $pack = \Module\Dokani\Models\Package::where('user_id', auth()->user()->dokan_id)->first();
                $end_date = $pack->end_date;
            }else{
                $end_date = now();
            }
        }
    }
    $expireText = "expired";

    if($end_date > date('Y-m-d')){
        $expireText = "will expire";
    }
@endphp
<script>
    $(document).ready(function () {
        $.get('{{route('dokani.subscription.alert')}}', function (res) {
            // console.log(res.success)
            if (res.success) {
                $('body').prepend(
                    `
                    @if(optional(auth()->user())->type == 'owner')
                        <div class="alert alert-danger text-center" role="alert" style="z-index: 9999999999;
                                                    margin: 0;padding: 5px;position: fixed;left: 0;right: 0;">
                            <a href="{{ route('dokani.subscription.index') }}" class="btn-link alert-link smp-button" style="border: none; outline: none">
                            <u>Your Software Subscription {{ $expireText }} at {{ $end_date }} (Click to Payment)</u>
                            </a>
                        </div>
                    @else
                    <div class="alert alert-danger text-center" role="alert" style="z-index: 9999999999;
                                                margin: 0;padding: 5px;position: fixed;left: 0;right: 0;">
                        <a href="javascript:void(0)" class="btn-link alert-link smp-button" style="border: none; outline: none">
                            <u>Your Software Subscription {{ $expireText }} at {{ $end_date }} </u>
                            </a>
                        </div>
                    @endif

                    `
                );
                $('#navbar').css('margin-top', '35px');
                $('.pos-rel').css('top', '35px');
            }


            if (res.overdue) {
                $('button').each(function () {
                    if (['save', 'update',].includes($(this).text().toString().trim().toLowerCase())) {
                        $(this).attr('disabled', true);
                    }
                });
                $('button[type=submit]:not(:disabled)').each(function () {
                    $(this).attr('disabled', true);
                });
                $('input[type=submit]:not(:disabled)').each(function () {
                    $(this).attr('disabled', true);
                });
                $('button[title=Delete]').attr('disabled', true);
                $('.smp-button').attr('disabled', false);
            }
        })
    })
</script>
