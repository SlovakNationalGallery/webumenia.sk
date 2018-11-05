@extends('layouts.master')

@section('title')
{{ utrans('reprodukcie.title') }} |
@parent
@stop

@section('content')

<div class="webumeniaCarousel">
    <div class="header-image" style="background-image: url(/images/reprodukcie/pracovisko.jpg);">
        <div class="outer-box">
            <div class="inner-box">
                <h1>{{ utrans('reprodukcie.title') }}</h1>
            </div>
        </div>
    </div>
</div>
<section class="intro content-section underlined-links">
    <div class="">
        <div class="container">
            <p class="lead text-center">
                {!! utrans('reprodukcie.lead') !!}</p>
        </div>
    </div>
</section>

<hr>

<section class="underlined-links">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2 id="print" class="text-center bottom-space">{{ utrans('reprodukcie.print_heading') }}</h2>
                <div>
                    {!! utrans('reprodukcie.print_body') !!}
                </div>
                <div class="row ">
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => trans('reprodukcie.print_offer_standalone_title'),
                            "img_url" => "/images/reprodukcie/format-1-samostatna.jpg",
                            "description" => trans('reprodukcie.print_offer_standalone_description'),
                            "pricing_options" => [
                                [trans('reprodukcie.print_offer_until')." <strong>A4</strong>", "(21 x 29,7 cm)",   "30"],
                                [trans('reprodukcie.print_offer_until')." <strong>A3</strong>", "(32,9 x 48,3 cm)", "40"],
                                [trans('reprodukcie.print_offer_until')." <strong>A2</strong>", "(42 x 59,4 cm)",   "45"],
                                [trans('reprodukcie.print_offer_until')." <strong>A1</strong>", "(59,4 x 84,1 cm)", "55"]
                            ]
                        ])
                    </div>
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => trans('reprodukcie.print_offer_passepartout_title'),
                            "img_url" => "/images/reprodukcie/format-2-pasparta.jpg",
                            "description" => trans('reprodukcie.print_offer_passepartout_description'),
                            "pricing_options" => [
                                [trans('reprodukcie.print_offer_until')." <strong>A4</strong>", "(21 x 29,7 cm)",    "35"],
                                [trans('reprodukcie.print_offer_until')." <strong>A3+</strong>", "(32,9 x 48,3 cm)", "50"]
                            ]
                        ])
                    </div>
                </div>
                <div class="row ">
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => trans('reprodukcie.print_offer_framed_title'),
                            "img_url" => "/images/reprodukcie/format-3-ram.jpg",
                            "description" => trans('reprodukcie.print_offer_framed_description'),
                            "pricing_options" => [
                                [trans('reprodukcie.print_offer_until')." <strong>A4</strong>",  "(21 x 29,7 cm)",   "40"],
                                [trans('reprodukcie.print_offer_until')." <strong>A3+</strong>", "(32,9 x 48,3 cm)", "60"]
                            ]
                        ])
                    </div>
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => trans('reprodukcie.print_offer_poster_title'),
                            "img_url" => "/images/reprodukcie/format-4-plagat.jpg",
                            "description" => trans('reprodukcie.print_offer_poster_description'),
                            "pricing_options" => [
                                ["<strong>A1</strong>", "(60 x 90cm)", "35"]
                            ]
                        ])
                    </div>
                </div>
                <div class="row top-space grey">
                    <div class="col-12">
                        <h3 class="text-center">{!! utrans('reprodukcie.info_title') !!}</h3>
                        {!! utrans('reprodukcie.print_list') !!}
                    </div>  
                </div>
            </div>
        </div>
    </div>
</section>

<section class="">
    <div class="container">
        <div class="row top-space bottom-space">
            <div class="col-xs-12 text-center grey">
                <h3>{{ utrans('reprodukcie.print_recommended') }}</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                @include('components.artwork_carousel', [
                    'slick_target' => "artworks-preview",
                    'items' => $items_print,
                ])
            </div>
        </div>
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{!! url_to('katalog', ['gallery' => 'Slovenská národná galéria, SNG']) !!}" class="btn btn-default btn-outline sans" >{{ trans('reprodukcie.more-items_button') }} <strong>{!! App\Item::forReproduction()->count() !!}</strong>  <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>
    </div>
</section>

<hr>

<section class="underlined-links">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2 id="digital" class="text-center bottom-space">{{ utrans('reprodukcie.digital_heading') }}</h2>
                <div>{!! utrans('reprodukcie.digital_body') !!}</div>
            </div>
        </div>
    </div>
</section>

<section class="">
    <div class="container">
        <div class="row top-space bottom-space">
            <div class="col-xs-12 text-center grey">
                <h3>{{ utrans('reprodukcie.digital_examples') }}</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <img class="img-responsive lazyload" data-src="/images/reprodukcie/digirepro-1.jpg" alt="{{trans('reprodukcie.digital_heading')}} 1">
            </div>
            <div class="col-xs-4">
                <img class="img-responsive lazyload" data-src="/images/reprodukcie/digirepro-2.jpg" alt="{{trans('reprodukcie.digital_heading')}} 2">
            </div>
            <div class="col-xs-4">
                <img class="img-responsive lazyload" data-src="/images/reprodukcie/digirepro-3.jpg" alt="{{trans('reprodukcie.digital_heading')}} 3">
            </div>
        </div>
    </div>
</section>

<section class="underlined-links top-space">
    <div class="container">
        <div class="row grey">
            <div class="col-md-8 col-md-offset-2">
                <h3 class="text-center">{!! utrans('reprodukcie.info_title') !!}</h3>
                {!! utrans('reprodukcie.digital_list') !!}
            </div>
        </div>
    </div>
</section>

<section class="">
    <div class="container">
        <div class="row top-space bottom-space">
            <div class="col-xs-12 text-center grey">
                <h4>{{ utrans('reprodukcie.digital_choice') }}</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                @include('components.artwork_carousel', [
                    'slick_target' => "artworks-preview",
                    'items' => $items_digital,
                ])
            </div>
        </div>
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{!! URL::to('katalog?is_free=' . '1') !!}" class="btn btn-default btn-outline sans" >{{ trans('reprodukcie.more-items_button') }} <strong>{!! App\Item::amount(['is_free' => true]) !!}</strong>  <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>
    </div>
</section>

@stop

@section('javascript')
    @include('components.artwork_carousel_js', ['slick_query' => '.artworks-preview'])
@stop
