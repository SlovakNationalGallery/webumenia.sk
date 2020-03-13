@extends('layouts.master')

@section('title')
{{ trans('reprodukcie.title') }} |
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
            <p class="lead text-center top-space">
                {!! trans('reprodukcie.lead', ['total' => $total]) !!}
            </p>
        </div>
    </div>
</section>

<hr>

<section class="underlined-links">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="alert alert-warning">
                    {!! trans('reprodukcie.alert_covid-19') !!}
                </div>

                <h2 id="print" class="text-center bottom-space">{{ trans('reprodukcie.print_heading') }}</h2>
                <div>
                    {!! trans('reprodukcie.print_body') !!}
                </div>
                <div class="row top-space">
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => trans('reprodukcie.print_offer_standalone_title'),
                            "img_url" => "/images/reprodukcie/format-1-samostatna.jpg",
                            "img_full_url" => "/images/reprodukcie/full/format-1-samostatna.jpg",
                            "description" => trans('reprodukcie.print_offer_standalone_description'),
                            "pricing_options" => [
                                [trans('reprodukcie.print_offer_until')." <strong>A4</strong>", "(21 x 29,7 cm)",   "28"],
                                [trans('reprodukcie.print_offer_until')." <strong>A3+</strong>", "(32,9 x 48,3 cm)", "40"],
                                [trans('reprodukcie.print_offer_until')." <strong>A2</strong>", "(42 x 59,4 cm)",   "50"],
                                [trans('reprodukcie.print_offer_until')." <strong>A1</strong>", "(59,4 x 84,1 cm)", "60"]
                            ]
                        ])
                    </div>
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => trans('reprodukcie.print_offer_passepartout_title'),
                            "img_url" => "/images/reprodukcie/format-2-pasparta.jpg",
                            "img_full_url" => "/images/reprodukcie/full/format-2-pasparta.jpg",
                            "description" => trans('reprodukcie.print_offer_passepartout_description'),
                            "pricing_options" => [
                                [trans('reprodukcie.print_offer_until')." <strong>A4</strong>", "(min. 21 x 29,7 cm)",    "38"],
                                [trans('reprodukcie.print_offer_until')." <strong>A3+</strong>", "(min. 30 x 40 cm)", "55"]
                            ]
                        ])
                    </div>
                </div>
                <div class="row ">
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => trans('reprodukcie.print_offer_framed_title'),
                            "img_url" => "/images/reprodukcie/format-3-ram.jpg",
                            "img_full_url" => "/images/reprodukcie/full/format-3-ram.jpg",
                            "description" => trans('reprodukcie.print_offer_framed_description'),
                            "pricing_options" => [
                                [trans('reprodukcie.print_offer_until')." <strong>A4</strong>",  "(35 x 45 cm)",   "48"],
                                [trans('reprodukcie.print_offer_until')." <strong>A3+</strong>", "(47 x 58 cm)", "65"]
                            ]
                        ])
                    </div>
                    <div class="col-sm-6">
                        @include('components.repro_offer', [
                            "title" => trans('reprodukcie.print_offer_poster_title'),
                            "img_url" => "/images/reprodukcie/format-4-plagat.jpg",
                            "img_full_url" => "/images/reprodukcie/full/format-4-plagat.jpg",
                            "description" => trans('reprodukcie.print_offer_poster_description'),
                            "pricing_options" => [
                                ["<strong>A1</strong>", "(60 x 90cm)", "38"]
                            ]
                        ])
                    </div>
                </div>
                <div class="row top-space grey">
                    <div class="col-12">
                        <h3 class="text-center">{!! trans('reprodukcie.info_title') !!}</h3>
                        {!! trans('reprodukcie.print_list') !!}
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
                <h3>{{ trans('reprodukcie.print_recommended') }}</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                @include('components.artwork_carousel', [
                    'slick_target' => "artworks-preview",
                    'items' => $items_recommended,
                ])
            </div>
        </div>
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{!! url_to('katalog', ['gallery' => 'Slovenská národná galéria, SNG', 'has_image' => '1', 'has_iip' => '1']) !!}" class="btn btn-default btn-outline sans" >{{ trans('reprodukcie.more-items_button') }} <strong>{{ $total }}</strong>  <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>
    </div>
</section>

<hr>

<section class="underlined-links">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h2 id="digital" class="text-center bottom-space">{{ trans('reprodukcie.digital_heading') }}</h2>
                <div>{!! trans('reprodukcie.digital_body') !!}</div>
            </div>
        </div>
    </div>
</section>

<section class="">
    <div class="container">
        <div class="row top-space bottom-space">
            <div class="col-xs-12 text-center grey">
                <h3>{{ trans('reprodukcie.digital_examples') }}</h3>
            </div>
        </div>
        <div class="row bottom-space">
            <div class="col-xs-6">
                <a href="/images/reprodukcie/full/digirepro-1.jpg" class="popup"><img class="img-responsive lazyload" data-src="/images/reprodukcie/digirepro-1.jpg" alt="{{trans('reprodukcie.digital_example_1')}}"></a>
            </div>
            <div class="col-xs-6">
                <a href="/images/reprodukcie/full/digirepro-2.jpg" class="popup"><img class="img-responsive lazyload" data-src="/images/reprodukcie/digirepro-2.jpg" alt="{{trans('reprodukcie.digital_example_2')}}"></a>
            </div>
        </div>
        <div class="row bottom-space">
            <div class="col-xs-6">
                <a href="/images/reprodukcie/full/digirepro-3.jpg" class="popup"><img class="img-responsive lazyload" data-src="/images/reprodukcie/digirepro-3.jpg" alt="{{trans('reprodukcie.digital_example_3')}}"></a>
            </div>
            <div class="col-xs-6">
                <a href="/images/reprodukcie/full/digirepro-4.jpg" class="popup"><img class="img-responsive lazyload" data-src="/images/reprodukcie/digirepro-4.jpg" alt="{{trans('reprodukcie.digital_example_4')}}"></a>
            </div>
        </div>
    </div>
</section>

<section class="underlined-links top-space">
    <div class="container">
        <div class="row grey">
            <div class="col-md-8 col-md-offset-2">
                <h3 class="text-center">{!! trans('reprodukcie.info_title') !!}</h3>
                {!! trans('reprodukcie.digital_list') !!}
            </div>
        </div>
    </div>
</section>

<section class="">
    <div class="container">
        <div class="row top-space bottom-space">
            <div class="col-xs-12 text-center grey">
                <h3>{{ trans('reprodukcie.digital_choice') }}</h3>
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
                <a href="{!! url_to('katalog', ['gallery' => 'Slovenská národná galéria, SNG', 'has_image' => '1', 'has_iip' => '1']) !!}" class="btn btn-default btn-outline sans" >{{ trans('reprodukcie.more-items_button') }} <strong>{{ $total }}</strong>  <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>
    </div>
</section>


{{-- modal for image preview --}}
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
      	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <img src="" class="imagepreview" style="width: 100%;" >
        <p class="text-center top-space imagetitle"></p>
      </div>
    </div>
  </div>
</div>
{{-- /modal for image preview --}}

@stop

@section('javascript')
    @include('components.artwork_carousel_js', ['slick_query' => '.artworks-preview'])

    <script>
        $(function() {
            $('.popup').on('click', function(e) {
                e.preventDefault();
                $('.imagepreview').attr('src', $(this).attr('href'));
                $('.imagetitle').html($(this).find('img').attr('alt'));
                $('#imagemodal').modal('show');
            });
        });
    </script>
@stop
