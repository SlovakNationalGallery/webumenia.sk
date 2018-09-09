<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
{{-- default language en_US is bundled with bootstrapValidator.min.js --}}
@if (App::getLocale() == 'cs')
    {!! Html::script('js/jquery.bootstrapvalidator/cs_CZ.js') !!}
@endif

<script type="text/javascript">

    $(document).ready(function(){

        $('div.form-hide').hide();

        $("#download-type .btn").on('click', function(e){
            e.preventDefault();
            $('div.form-hide').fadeOut();
            $('div.form-hide :input').attr("disabled", true);

            var type = $(this).data('type');
            // set type by button
            $('input[name=type]').val(type);
            // show parts according the type
            $('div.'+type).fadeIn();
            $('div.'+type+' :input').attr("disabled", false);

            // enable download if validation pass
            $(this).closest('form').find(':submit').prop("disabled", false);


        });

        /*
        $('#download').on('click', function(e){

            // $('#license').modal({})
            $.fileDownload($(this).attr('href'), {
                successCallback: function(url) {
                },
                failCallback: function(responseHtml, url) {
                    $('#license').modal('hide');
                    $('#downloadfail').modal('show');
                }
            });
            return false; //this is critical to stop the click event which will trigger a normal file download!
        });
        */


        $('#downloadForm form').bootstrapValidator({
                    feedbackIcons: {
                        valid: 'fa fa-check',
                        invalid: 'fa fa-times',
                        validating: 'fa fa-refresh'
                    },
                    live: 'enabled',
                    submitButtons: 'input[type="submit"]',
                    locale: 'cs_CZ',
                    excluded: [':disabled', ':hidden', ':not(:visible)']
        });

    });

</script>
