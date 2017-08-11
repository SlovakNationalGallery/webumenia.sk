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

                <p class="lead">{!! utrans('informacie.info_p_lead') !!}</p>
                <p>{!! utrans('informacie.info_p') !!}</p>

            </div>
            <div class="col-md-4 text-center">
                <img srcset="/images/galerie-na-mape.png 1x, /images/galerie-na-mape@2x.png 2x" src="" alt="Galérie na mape" class="img-responsive" style="margin: 20px auto 40px" />
            </div>
            <div class="col-md-4 text-center">
                <ul class="list-unstyled lead">
                    <li><a href="{!! URL::to('katalog?gallery=Slovenská národná galéria, SNG') !!}">{{ utrans('informacie.info_gallery_SNG') }}</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Oravská galéria, OGD') !!}">{{ utrans('informacie.info_gallery_OGD') }}</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Galéria umenia Ernesta Zmetáka, GNZ') !!}">{{ utrans('informacie.info_gallery_GNZ') }}</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Liptovská galéria Petra Michala Bohúňa, GPB') !!}">{{ utrans('informacie.info_gallery_GPB') }}</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Galéria mesta Bratislavy, GMB') !!}">{{ utrans('informacie.info_gallery_GMB') }}</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Galéria+Miloša+Alexandra+Bazovského, GBT') !!}">{{ utrans('informacie.info_gallery_GBT') }}</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Nitrianska+galéria, NGN') !!}">{{ utrans('informacie.info_gallery_NGN') }}</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Stredoslovenská galéria, SGB') !!}">{{ utrans('informacie.info_gallery_SGB') }}</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Galéria umelcov Spiša, GUS') !!}">{{ utrans('informacie.info_gallery_GUS') }}</a></li>
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
                <a href="#" data-toggle="modal" data-target="#priceList" class="inherit lead pull-left no-border"><i class="icon-arrow-right"></i> &nbsp; {{ trans('informacie.general_reproduction_prices') }}</a>
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
                <div class="artworks-preview">
                @foreach ($items as $item)
                    <a href="{!! $item->getUrl() !!}" class="no-border"><img data-lazy="{!! $item->getImagePath() !!}" class="img-responsive-width " alt="{!! $item->getTitleWithAuthors() !!} " title="{!! $item->getTitleWithAuthors() !!} " ></a>
                @endforeach
                </div>
            </div>
        </div>
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{!! url_to('katalog', ['gallery' => 'Slovenská národná galéria, SNG']) !!}" class="btn btn-default btn-outline sans" >{{ trans('informacie.more-items_button') }} <strong>{!! App\Item::forReproduction()->count() !!}</strong>  <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>
    </div>
</section>

<section class="mozete more-items">
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

<!-- Modal -->
<div tabindex="-1" class="modal fade" id="priceList" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h1>{{ trans('informacie.general_reproduction_prices') }}</h1>
            </div>
            <div class="modal-body">
                
                <p>{{ utrans('informacie.modal_disclaimer') }}</p>

                <table class="table table-striped">
                    {!! utrans('informacie.modal_table-content') !!}
                </table>

                <p>
                    <sup class="text-danger">*</sup> {!! utrans('informacie.modal_unavailable') !!}
                </p>

            </div>
            <div class="modal-footer">
                <div class="text-center"><button type="button" data-dismiss="modal" class="btn btn-default btn-outline sans">{{ trans('general.close') }}</button></div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
{!! Html::script('js/slick.js') !!}
<script type="text/javascript">
    $(document).ready(function(){

        $('.artworks-preview').slick({
            dots: false,
            lazyLoad: 'progressive',
            infinite: false,
            speed: 300,
            slidesToShow: 1,
            slide: 'a',
            centerMode: false,
            variableWidth: true,
        });

    });
</script>

@stop
