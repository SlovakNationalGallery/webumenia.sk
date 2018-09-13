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
<section class="intro content-section">
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
                <h2 class="text-center">{{ utrans('reprodukcie.print_heading') }}</h2>
                <div>{!! utrans('reprodukcie.print_body') !!}</div>
                <div class="row ">
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => "Samostatná Tlačená Reprodukcia",
                            "img_url" => "/images/reprodukcie/format-1-samostatna.jpg",
                            "description" => "<p>Reprodukcia je vytlačená na fine art rag (100% bavlna, pH neutrálna) alebo FineArt Baryt Photo Paper, okolo plochy diela je biely okraj (pre prípadné paspartovanie/rámovanie).</p><p>Rozmer reprodukcie zachováva pomer strán diela.</p>",
                            "pricing_options" => [
                                ["do <strong>A4</strong>", "(21 x 29,7 cm)",   "30 eur / ks"],
                                ["do <strong>A3</strong>", "(32,9 x 48,3 cm)", "40 eur / ks"],
                                ["do <strong>A2</strong>", "(42 x 59,4 cm)",   "45 eur / ks"],
                                ["do <strong>A1</strong>", "(59,4 x 84,1 cm)", "55 eur / ks"]
                            ]
                        ])
                    </div>
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => "Reprodukcia S Paspartou",
                            "img_url" => "/images/reprodukcie/format-2-pasparta.jpg",
                            "description" => "<p>Reprodukcia je vytlačená na fine art rag (100% bavlna, pH neutrálna) alebo FineArt Baryt Photo Paper.</p><p>Papierová pasparta je z jednej strany otvorená a umožňuje ďalšie rámovanie.</p><p>Šírka pasparty je 5-8 cm (podľa rozmerov diela). Maximálny rozmer reprodukcie v pasparte je A3+.</p>",
                            "pricing_options" => [
                                ["do <strong>A4</strong>", "(21 x 29,7 cm)",    "35 eur / ks"],
                                ["do <strong>A3+</strong>", "(32,9 x 48,3 cm)", "50 eur / ks"]
                            ]
                        ])
                    </div>
                </div>
                <div class="row ">
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => "Reprodukcia S Paspartou A Rámom",
                            "img_url" => "/images/reprodukcie/format-3-ram.jpg",
                            "description" => "<p>Reprodukcia je vytlačená na fine art rag (100% bavlna, pH neutrálna) alebo FineArt Baryt Photo Paper.</p><p>Dielo je vsadené v pasparte a v ráme so sklom.</p><p>Rámy sú drevené, nereliéfne, v dvoch farebných verziách (svetlé, tmavé). Ak vám farba rámu nevyhovuje, odporúčame formát reprodukcie s paspartou, pripravený na svojpomocné rámovanie.</p><p>Maximálny rozmer reprodukcie v pasparte a ráme je A3+.</p>",
                            "pricing_options" => [
                                ["do <strong>A4</strong>",  "(21 x 29,7 cm)",   "40 eur / ks"],
                                ["do <strong>A3+</strong>", "(32,9 x 48,3 cm)", "60 eur / ks"]
                            ]
                        ])
                    </div>
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => "Poster (Art Plagát)",
                            "img_url" => "/images/reprodukcie/format-4-plagat.jpg",
                            "description" => "<p>Reprodukcia je vytlačená na špeciálny, vysokokvalitný, mapový papier rozmeru A1.</p><p>Momentálne ponúkame v tejto forme iba reprodukcie <a href='https://www.webumenia.sk/kolekcia/144'>plagátov zo zbierok SNG</a>.</p>",
                            "pricing_options" => [
                                ["<strong>A1</strong>", "(60 x 90cm)", "35 eur / ks"]
                            ]
                        ])
                    </div>
                </div>
                <div class="row top-space grey">
                    <div class="col-12">
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
                    'items' => $items,
                ])
            </div>
        </div>
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{!! url_to('katalog', ['gallery' => 'Slovenská národná galéria, SNG']) !!}" class="btn btn-default btn-outline sans" >{{ trans('informacie.more-items_button') }} <strong>{!! App\Item::forReproduction()->count() !!}</strong>  <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>
    </div>
</section>

<hr>

<section class="underlined-links">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2 class="text-center">{{ utrans('reprodukcie.digital_heading') }}</h2>
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
            <div class="col-xs-12">
                @include('components.artwork_carousel', [
                    'slick_target' => "artworks-preview",
                    'items' => $items,
                ])
            </div>
        </div>
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{!! url_to('katalog', ['gallery' => 'Slovenská národná galéria, SNG']) !!}" class="btn btn-default btn-outline sans" >{{ trans('informacie.more-items_button') }} <strong>{!! App\Item::forReproduction()->count() !!}</strong>  <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>
    </div>
</section>

<section class="underlined-links">
    <div class="container">
        <div class="row grey">
            <div class="col-md-8 col-md-offset-2">
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
                    'items' => $items,
                ])
            </div>
        </div>
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{!! url_to('katalog', ['gallery' => 'Slovenská národná galéria, SNG']) !!}" class="btn btn-default btn-outline sans" >{{ trans('informacie.more-items_button') }} <strong>{!! App\Item::forReproduction()->count() !!}</strong>  <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>
    </div>
</section>

@stop

@section('javascript')
    @include('components.artwork_carousel_js', ['slick_query' => '.artworks-preview'])
@stop
