@extends('layouts.master')

@section('title')
{{ utrans('khb-o-projekte.title') }} |
@parent
@stop

@section('content')
<section class="about">
    <div class="row">
        <div class="col p-0">
            <div class="accordion" id="authorAccordion">
                @include('components.khb_accordion_card', [
                    'title' => utrans('khb-o-projekte.about-title'),
                    'content' => utrans('khb-o-projekte.about-content'),
                    'parrentId' => 'authorAccordion',
                    'show' => true,
                ])
                @include('components.khb_accordion_card', [
                    'title' => utrans('khb-o-projekte.content_structure-title'),
                    'content' => utrans('khb-o-projekte.content_structure-content'),
                    'parrentId' => 'authorAccordion',
                    'show' => false,
                ])
                @include('components.khb_accordion_card', [
                    'title' => utrans('khb-o-projekte.committee-title'),
                    'content' => utrans('khb-o-projekte.committee-content'),
                    'parrentId' => 'authorAccordion',
                    'show' => false,
                ])
                @include('components.khb_accordion_card', [
                    'title' => utrans('khb-o-projekte.team-title'),
                    'content' => utrans('khb-o-projekte.team-content'),
                    'parrentId' => 'authorAccordion',
                    'show' => false,
                ])
                @include('components.khb_accordion_card', [
                    'title' => utrans('khb-o-projekte.contact-title'),
                    'content' => utrans('khb-o-projekte.contact-content'),
                    'parrentId' => 'authorAccordion',
                    'show' => false,
                ])
                @include('components.khb_accordion_card', [
                    'title' => utrans('khb-o-projekte.copyright-title'),
                    'content' => utrans('khb-o-projekte.copyright-content'),
                    'parrentId' => 'authorAccordion',
                    'show' => false,
                ])
            </div>
        </div>
    </div>
</section>
@stop

@section('javascript')
{!! Html::script('js/plugins/readmore.min.js') !!}
<script type="text/javascript">
    $(document).ready(function(){
        $('.expandable').readmore({
            moreLink: '<a href="#" class="no-underline text-right">{{ trans("general.show_more") }} <i class="fas fa-chevron-right"></i></a>',
            lessLink: '<a href="#" class="no-underline text-right">{{ trans("general.show_less") }} <i class="fas fa-chevron-up"></i></a>',
            maxHeight: 400,
            afterToggle: function(trigger, element, expanded) {
              if(! expanded) { // The "Close" link was clicked
                $('html, body').animate( { scrollTop: element.offset().top }, {duration: 100 } );
              }
            }
        });
    });
</script>
@stop