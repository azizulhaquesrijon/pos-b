

function chosenSelectInit()
{
    if(!ace.vars['touch']) {
        $('.chosen-select').chosen({allow_single_deselect:true});
        $('.chosen-select-180').chosen({allow_single_deselect:true});
        $('.chosen-select-200').chosen({allow_single_deselect:true});
        $('.chosen-select-220').chosen({allow_single_deselect:true});
        $('.chosen-select-230').chosen({allow_single_deselect:true});
        $('.chosen-select-280').chosen({allow_single_deselect:true});
        $('.chosen-select-380').chosen({allow_single_deselect:true});
        $('.chosen-select-450').chosen({allow_single_deselect:true});
        $('.chosen-select-100-percent').chosen({allow_single_deselect:true});
        //resize the chosen on window resize

        $(window)
            .off('resize.chosen')
            .on('resize.chosen', function() {
                $('.chosen-select-100-percent').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': '100%'});
                })
            }).trigger('resize.chosen');
        //resize chosen on sidebar collapse/expand
        $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
            if(event_name != 'sidebar_collapsed') return;
            $('.chosen-select-100-percent').each(function() {
                var $this = $(this);
                $this.next().css({'width': '100%'});
            })
        });

        $(window)
            .off('resize.chosen')
            .on('resize.chosen', function() {
                $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': $this.parent().width()});
                })
            }).trigger('resize.chosen');
        //resize chosen on sidebar collapse/expand
        $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
            if(event_name != 'sidebar_collapsed') return;
            $('.chosen-select').each(function() {
                var $this = $(this);
                $this.next().css({'width': $this.parent().width()});
            })
        });

        // chosen-select-180
        $(window)
            .off('resize.chosen')
            .on('resize.chosen', function() {
                $('.chosen-select-180').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': '180px'});
                })
            }).trigger('resize.chosen');
        //resize chosen on sidebar collapse/expand
        $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
            if(event_name != 'sidebar_collapsed') return;
            $('.chosen-select-180').each(function() {
                var $this = $(this);
                $this.next().css({'width': '180px'});
            })
        });

        // chosen-select-200
        $(window)
            .off('resize.chosen')
            .on('resize.chosen', function() {
                $('.chosen-select-200').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': '200px'});
                })
            }).trigger('resize.chosen');
        //resize chosen on sidebar collapse/expand
        $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
            if(event_name != 'sidebar_collapsed') return;
            $('.chosen-select-200').each(function() {
                var $this = $(this);
                $this.next().css({'width': '200px'});
            })
        });

        // chosen-select-220
        $(window)
            .off('resize.chosen')
            .on('resize.chosen', function() {
                $('.chosen-select-220').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': '220px'});
                })
            }).trigger('resize.chosen');
        //resize chosen on sidebar collapse/expand
        $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
            if(event_name != 'sidebar_collapsed') return;
            $('.chosen-select-220').each(function() {
                var $this = $(this);
                $this.next().css({'width': '220px'});
            })
        });

        // chosen-select-230
        $(window)
            .off('resize.chosen')
            .on('resize.chosen', function() {
                $('.chosen-select-230').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': '230px'});
                })
            }).trigger('resize.chosen');

        $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
            if(event_name != 'sidebar_collapsed') return;
            $('.chosen-select-230').each(function() {
                var $this = $(this);
                $this.next().css({'width': '230px'});
            })
        });
    }

    // chosen-select-280
    $(window)
        .off('resize.chosen')
        .on('resize.chosen', function() {
            $('.chosen-select-280').each(function() {
                var $this = $(this);
                $this.next().css({'width': '280px'});
            })
        }).trigger('resize.chosen');

    $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
        if(event_name != 'sidebar_collapsed') return;
        $('.chosen-select-280').each(function() {
            var $this = $(this);
            $this.next().css({'width': '280px'});
        })
    });

    // chosen-select-380
    $(window)
        .off('resize.chosen')
        .on('resize.chosen', function() {
            $('.chosen-select-380').each(function() {
                var $this = $(this);
                $this.next().css({'width': '380px'});
            })
        }).trigger('resize.chosen');

    $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
        if(event_name != 'sidebar_collapsed') return;
        $('.chosen-select-380').each(function() {
            var $this = $(this);
            $this.next().css({'width': '380px'});
        })
    });

    // chosen-select-450
    $(window)
        .off('resize.chosen')
        .on('resize.chosen', function() {
            $('.chosen-select-450').each(function() {
                var $this = $(this);
                $this.next().css({'width': '450ppx'});
            })
        }).trigger('resize.chosen');

    $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
        if(event_name != 'sidebar_collapsed') return;
        $('.chosen-select-450').each(function() {
            var $this = $(this);
            $this.next().css({'width': '450px'});
        })
    });
}

jQuery(function($){
    chosenSelectInit()
})
