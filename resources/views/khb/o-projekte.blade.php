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