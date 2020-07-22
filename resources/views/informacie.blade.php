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
                @foreach(trans('informacie.info_p') as $paragraph)
                    <p>{!! str_replace('registr@ngprague.cz', '<a href="mailto:registr@ngprague.cz">registr@ngprague.cz</a>', e($paragraph)) !!}</p>
                @endforeach
            </div>
            <div class="col-md-4 text-center">
                <img srcset="/images/mapa-ng.png 1x, /images/mapa-ng@2x.png 2x" src="" alt="Mapa NG" class="img-responsive" style="margin: 20px auto 40px" />
            </div>
            <div class="col-md-4 text-center">
                <ul class="list-unstyled lead">
                    @foreach ($collections as $collection)
                    <li>
                        <a href="{{ $collection->getUrl() }}">{{ $collection['name'] }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
{{--  
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
--}}

@stop

@section('javascript')
    {{-- @include('components.artwork_carousel_js', ['slick_query' => '.artworks-preview']) --}}
@stop
