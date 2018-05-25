@extends('layouts.master')

@section('title')
{{ utrans('informacie.title') }} |
@parent
@stop

@section('content')

<section class="info">
    <div class="container">
        <div class="row content-section">
            <div class="col-md-12 text-center">
                    <h1>{{ utrans('informacie.info_heading') }}</h1>
            </div>
        </div>
        <div class="row bottom-space vertical-align">
            <div class="col-md-4">

                <p class="">{!! utrans('informacie.info_p_lead') !!}</p>
                <p>{!! utrans('informacie.info_p') !!}</p>

            </div>
            <div class="col-md-4 text-center">
                <img srcset="/images/mapa-ng.png 1x, /images/mapa-ng@2x.png 2x" src="" alt="Mapa NG" class="img-responsive" style="margin: 20px auto 40px" />
            </div>
            <div class="col-md-4 text-center">
                @php
                    $galleries = [];

                    $galleries['cs'] = [
                        [
                            'name'        => 'Klášter sv. Anežky České',
                            'description' => 'Středověké umění v Čechách<br>a střední Evropě 1200-1550',
                            'url'         => 'kolekcia/2',
                        ],
                        [
                            'name'        => 'Schwarzenberský palác',
                            'description' => 'Od rudolfinského umění,<br>až po baroko v Čechách',
                            'url'         => 'kolekcia/3',
                        ],
                        [
                            'name'        => 'Šternberský palác',
                            'description' => 'Evropské umění od antiky<br>do baroka',
                            'url'         => 'kolekcia/4',
                        ],
                        [
                            'name'        => 'Veletržní palác',
                            'description' => 'Umění 19. 20. a 21. století',
                            'url'         => 'kolekcia/5',
                        ],
                        [
                            'name'        => 'Palác Kinských',
                            'description' => 'Umění Asie',
                            'url'         => 'kolekcia/6',
                        ],
                    ];

                    $galleries['en'] = [
                        [
                            'name'        => 'Convent of St. Agnes of Bohemia',
                            'description' => 'Medieval Art in&nbsp;Bohemia and&nbsp;Central Europe 1200–1550',
                            'url'         => 'kolekcia/2',
                        ],
                        [
                            'name'        => 'Schwarzenberg Palace',
                            'description' => 'Art from the Rudolfine Era to the Baroque in&nbsp;Bohemia',
                            'url'         => 'kolekcia/3',
                        ],
                        [
                            'name'        => 'Sternberg Palace',
                            'description' => 'European Art from Antiquity to Baroque',
                            'url'         => 'kolekcia/4',
                        ],
                        [
                            'name'        => 'Trade Fair Palace',
                            'description' => 'The Art of the 19th, 20th and 21st Centuries',
                            'url'         => 'kolekcia/5',
                        ],
                        [
                            'name'        => 'Kinský Palace',
                            'description' => 'The Art of Asia — exhibition closed',
                            'url'         => 'kolekcia/6',
                        ],
                    ];

                @endphp
                <ul class="list-unstyled lead">
                    @foreach ($galleries[\App::getLocale()] as $gallery)
                        <li>
                            <a href="{!! URL::to($gallery['url']) !!}">{{ utrans($gallery['name']) }}</a>
                            <p>{!! $gallery['description'] !!}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="mozete">
    <div class="container">
        <div class="row content-section">
            <div class="col-md-12 text-center mid-grey">
                    <h2 class="inherit">{{ utrans('informacie.mozete_heading') }}</h2>
            </div>
        </div>
        <div class="row ">

            <div class="col-md-6">
                <h4>{{ utrans('informacie.mozete_col_stahovat_heading') }}</h4>
                <ul class="fa-ul">
                    <li><span class="fa-li">&sect;</span>{!! utrans('informacie.mozete_col_stahovat_li_1') !!}</li>
                    <li><span class="fa-li">&sect;</span>{!! utrans('informacie.mozete_col_stahovat_li_2') !!}</li>
                    <li><span class="fa-li">&sect;</span>{!! utrans('informacie.mozete_col_stahovat_li_3') !!}</li>
                </ul>
            </div>

            <div class="col-md-6">
                <h4>{{ utrans('informacie.mozete_col_vyuzivat_heading') }}</h4>
                <ul class="fa-ul">
                    <li><i class="fa-li icon-arrow-right"></i>{!! utrans('informacie.mozete_col_vyuzivat_li_1') !!}</li>
                    <li><i class="fa-li icon-arrow-right"></i>{!! utrans('informacie.mozete_col_vyuzivat_li_2') !!}</li>
                    <li><i class="fa-li icon-arrow-right"></i>{!! utrans('informacie.mozete_col_vyuzivat_li_3') !!}</li>
                </ul>
            </div>

        </div>
        <div class="row top-space">
            <div class="col-md-4 dib">
                <a href="{!! URL::to('katalog?is_free=' . '1') !!}" class="inherit lead pull-left no-border"><i class="icon-arrow-right"></i> &nbsp; {{ trans('informacie.mozete_free_artworks') }}</a>
            </div>
            <div class="col-md-4 dib"></div>
            <div class="col-md-4 dib"></div>
        </div>

    </div>
</section>

@stop

@section('javascript')
    {{-- @include('components.artwork_carousel_js', ['slick_query' => '.artworks-preview']) --}}
@stop
