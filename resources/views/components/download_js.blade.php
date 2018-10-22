<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
{{-- default language en_US is bundled with bootstrapValidator.min.js --}}
@if (App::getLocale() == 'cs')
    {!! Html::script('js/jquery.bootstrapvalidator/cs_CZ.js') !!}
@endif

<script type="text/javascript">

    $(document).ready(function(){

        $('div.form-hide').hide();

        $("input[name=type]").on('click', function(e){
            var type = $(this).val();

            $('div.form-hide').fadeOut().promise().done(function(){

                $('div.form-hide :input').attr("disabled", true);

                // show parts according the type
                $('div.'+type+' :input').attr("disabled", false);
                $('div.'+type).fadeIn();

                if (type=="private") {
                    // enable download if validation pass
                    $(this).closest('form').find(':submit').prop("disabled", false);
                } else {
                    $(this).closest('form').find(':submit').prop("disabled", true);
                }

            });
        });

        $('#downloadForm form').bootstrapValidator({
                    feedbackIcons: {
                        valid: 'fa fa-check',
                        invalid: 'fa fa-times',
                        validating: 'fa fa-refresh'
                    },
                    live: 'enabled',
                    submitButtons: 'button[type="submit"]',
                    locale: 'cs_CZ',
                    excluded: [':disabled', ':hidden', ':not(:visible)']
        });

    });

</script>
