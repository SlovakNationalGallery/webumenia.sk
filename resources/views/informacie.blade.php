@extends('layouts.master')

@section('title')
{{ utrans('informacie.title') }} |
@parent
@stop

@section('content')

<section class="info underlined-links">
    <div class="container">
        <div class="row content-section">
            <div class="col-md-12 text-center">
                    <h1>{{ utrans('informacie.info_heading') }}</h1>
            </div>
        </div>
        <div class="row bottom-space">
            <div class="col-md-4">

                <p class="lead">{!! utrans('informacie.info_p_lead') !!}</p>
                <p>{!! utrans('informacie.info_p') !!}</p>

            </div>
            <div class="col-md-8 text-center">
                {!! file_get_contents(public_path('images/gallery-map.svg')) !!}
            </div>
        </div>
        <div class="row bottom-space galleries">
            @foreach(array_chunk($galleries, ceil(count($galleries)/3)) as $chunk)
                <div class="col-md-4">
                    <ul class="list-unstyled lead">
                    @foreach($chunk as $gallery)
                        <li><a href="{!! URL::to($gallery['url']) !!}" id="{{ $gallery['id'] }}">{!! utrans($gallery['lang_string']) !!}</a></li>
                    @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>
<section class="mozete underlined-links">
    <div class="container">
        <div class="row content-section">
            <div class="col-md-12 text-center mid-grey">
                    <h2 class="inherit">{{ utrans('informacie.mozete_heading') }}</h2>
            </div>
        </div>
        <div class="row ">

            <div class="col-md-4">
                <h4>{{ utrans('informacie.mozete_col_stahovat_heading') }}</h4>
                <ul class="fa-ul">
                    <li><span class="fa-li">&sect;</span>{!! utrans('informacie.mozete_col_stahovat_li_1') !!}</li>
                    <li><span class="fa-li">&sect;</span>{!! utrans('informacie.mozete_col_stahovat_li_2') !!}</li>
                    <li><span class="fa-li">&sect;</span>{!! utrans('informacie.mozete_col_stahovat_li_3') !!}</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h4>{{ utrans('informacie.mozete_col_vyuzivat_heading') }}</h4>
                <ul class="fa-ul">
                    <li><i class="fa-li icon-arrow-right"></i>{!! utrans('informacie.mozete_col_vyuzivat_li_1') !!}</li>
                    <li><i class="fa-li icon-arrow-right"></i>{!! utrans('informacie.mozete_col_vyuzivat_li_2') !!}</li>
                    <li><i class="fa-li icon-arrow-right"></i>{!! utrans('informacie.mozete_col_vyuzivat_li_3') !!}</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h4>{{ utrans('informacie.mozete_col_objednat_heading') }}</h4>
                <p>{!! utrans('informacie.mozete_col_objednat_p') !!}</p>
            </div>

        </div>
        <div class="row top-space">
            <div class="col-md-4 dib">
                <a href="{!! URL::to('katalog?is_free=' . '1') !!}" class="inherit lead pull-left no-border"><i class="icon-arrow-right"></i> &nbsp; {{ trans('informacie.mozete_free_artworks') }}</a>
            </div>
            <div class="col-md-4 dib"></div>
            <div class="col-md-4 dib">
                <a href="{!! URL::to('reprodukcie#print') !!}" class="inherit lead pull-left no-border"><i class="icon-arrow-right"></i> &nbsp; {{ trans('informacie.general_reproduction_prices') }}</a>
            </div>
        </div>

    </div>
</section>
<section class="mozete more-items">
    <div class="container">
        <div class="row top-space bottom-space">
            <div class="col-xs-12 text-center">
                <h3>{{ utrans('informacie.more-items_heading') }}</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                @include('components.artwork_carousel', [
                    'slick_target' => "artworks-preview",
                    'items' => $items_for_reproduction_sample,
                ])
            </div>
        </div>
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{{ url_to('katalog', ['is_for_reproduction' => 1]) }}" class="btn btn-default btn-outline sans" >{{ trans('informacie.more-items_button') }} <strong>{{ $items_for_reproduction_total }}</strong>  <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>
    </div>
</section>

<section class="mozete more-items underlined-links">
    <div class="container">
        <div class="row content-section"><!-- top-space bottom-space -->
            <div class="col-md-12 text-center mid-grey">
                <h2>{{ utrans('informacie.more-items_connect_heading') }}</h2>
            </div>
        </div>
        <div class="row bottom-space">
            <div class="col-md-4">
                <p class="lead">
                    {!! utrans('informacie.more-items_connect_col1_lead') !!}
                </p>
                <p>
                    {!! utrans('informacie.more-items_connect_col1_p') !!}
                </p>
            </div>
            <div class="col-md-4">
                <p class="lead">
                    {!! utrans('informacie.more-items_connect_col2_lead') !!}
                </p>
                <p>
                    {!! utrans('informacie.more-items_connect_col2_p') !!}
                </p>
            </div>
            <div class="col-md-4">
                <p class="lead">
                    {!! utrans('informacie.more-items_connect_col3_lead') !!}
                </p>
                <ul class="list-lab list-unstyled">
                    {!! trans('informacie.more-items_connect_col3_ul-content') !!}
                </ul>
            </div>
        </div>
    </div>
</section>

@stop

@section('javascript')
    {!! Html::script('js/components/artwork_carousel.js') !!}
    {!! Html::script('js/components/gallery-map.js') !!}
@stop
