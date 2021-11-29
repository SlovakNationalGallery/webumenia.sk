@extends('layouts.master')

@section('og')
<meta property="og:title" content="{!! $author->formatedName !!}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:type" content="object" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! asset_timed( $author->getImagePath() ) !!}" />
@stop

@section('title')
{!! $author->formatedName !!} |
@parent
@stop

@section('description')
    <meta name="description" content="{{ $description }}">
@stop

@section('content')

@if ( ! $author->hasTranslation(App::getLocale()) )
    <section>
        <div class="container top-section">
            <div class="row">
                @include('includes.message_untranslated')
            </div>
        </div>
    </section>
@endif

<section class="author detail content-section" itemscope itemtype="http://schema.org/{{ $author->isCorporateBody() ? 'Organization' : 'Person' }}">
    <div class="container">
        <div class="attributes">
            <div class="row">
                <div class="col-sm-4 text-center extra-padding top-space">
                        <img src="{!! $author->getImagePath() !!}" class="img-responsive img-circle" alt="{{ $author->name }}"  itemprop="image">
                        <div class="content-section">
                            {!! trans_choice('authority.artworks', $author->items_count, ['artworks_url' => url_to('katalog', ['author' => $author->name]), 'artworks_count' => $author->items_count]) !!}
                            <br/>
                            {!! trans_choice('authority.collections', $author->collections_count, ['collections_count' => $author->collections_count] ) !!}
                            <br/>
                            {!! trans_choice('authority.views', $author->view_count, ['view_count' => $author->view_count]) !!}
                            <br/>
                            @include('components.share_buttons', [
                                'title' => $author->formatedName,
                                'url' => Request::url(),
                                'img' => URL::to( $author->getImagePath() ),
                                'class' =>'pt-4'
                            ])
                        </div>
                        @if ( $author->tags->count() > 0)
                            <div class="tags">
                                <h4>{{ utrans('authority.tags') }}: </h4>
                                @foreach ($author->tags as $tag)
                                    <a href="{!!URL::to('katalog?tag=' . $tag)!!}" class="btn btn-default btn-xs btn-outline">{!! $tag !!}</a>
                                @endforeach
                            </div>
                        @endif
                </div>
                <div class="col-sm-8 popis">
                    <a href="{!! str_contains(URL::previous(), '/autori') ?  URL::previous() : URL::to('/autori') !!} " class="inherit no-border"><i class="icon-arrow-left"></i> {{ utrans('authority.back-to-artists') }}</a>
                    <h1 itemprop="name">{!! $author->formatedName !!}</h1>
                    @if ( $author->names->count() > 0)
                        <p class="lead">{{ trans('authority.alternative_names') }} <em>{!! implode("</em>, <em>", $author->formatedNames) !!}</em></p>
                    @endif

                    {!! $html_description !!}

                    @if ($author->type_organization)
                    <p class="lead">
                        <strong>{{ $author->type_organization }}</strong>
                    </p>
                    @endif

                    {{-- @if ( $author->biography) --}}
                    <div class="text-left biography">
                        {!!  $author->biography !!}
                    </div>
                    {{-- @endif --}}

                    @if ( $author->events->count() > 0)
                        <div class="events">
                            <h4 class="top-space">{{ utrans('authority.places') }}</h4>
                            @foreach ($author->events as $i=>$event)
                                <strong><a href="{!! url_to('autori', ['place' => $event->place]) !!}">{!! $event->place !!}</a></strong> {!! add_brackets(App\Authority::formatMultiAttribute($event->event)) !!}{{ ($i+1 < $author->events->count()) ? ', ' : '' }}
                            @endforeach
                        </div>
                    @endif
                    @if ($author->sourceLinks->count() > 0)
                        <div class="links">
                            <h4 class="top-space">{{ utrans('authority.source_links') }}</h4>
                            <ul class="list-unstyled">
                                @foreach($author->sourceLinks as $link)
                                    <li>
                                        @if($link->url)
                                            <a href="{{ $link->url }}" target="_blank">{{ $link->label }}</a>
                                        @else
                                            {{ $link->label }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($author->externalLinks->count() > 0)
                        <div class="links">
                            <h4 class="top-space">{{ utrans('authority.external_links') }}</h4>
                            <ul class="list-unstyled">
                                @foreach($author->externalLinks as $link)
                                    <li>
                                        @if($link->url)
                                            <a href="{{ $link->url }}" target="_blank">{{ $link->label }}</a>
                                        @else
                                            {{ $link->label }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ( $author->relationships->count() > 0)
                    <h4 class="top-space">{{ utrans('authority.relationships') }}</h4>
                    <table class="table table-condensed relationships">
                        <thead>
                            <tr>
                            @foreach ($author->getAssociativeRelationships() as $type => $relatedAutorities)
                                <th>{{ $type }}</th>
                            @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            @foreach ($author->getAssociativeRelationships() as $type => $relatedAutorities)
                                <td>
                                @foreach ($relatedAutorities as $relatedAuthority)
                                    <a href="{{ $relatedAuthority->id }}" class="no-border"
                                       itemprop="{{ $author->isCorporateBody() ?
                                                    ($relatedAuthority->isCorporateBody() ? 'knowsAbout' : 'member') :
                                                    ($relatedAuthority->isCorporateBody() ? 'memberOf' : 'knows') }}">
                                        <strong>{{ formatName($relatedAuthority->name) }}</strong>
                                        <i class="icon-arrow-right"></i>
                                    </a><br>
                                @endforeach
                                </td>
                            @endforeach
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>{{-- row --}}
        </div> {{-- /attributes --}}
    </div>
</section>

@if ($author->items_count > 0)
<section class="author preview detail">
    <div class="container">
        <div class="row content-section">
            <div class="col-xs-12 text-center">
                <h3>{{ trans_choice('authority.artworks_by_artist', $author->sex) }}</h3>
            </div>
        </div>{{-- row --}}
        <div class="row">
            <div class="col-xs-12">
                @include('components.artwork_carousel', [
                    'slick_target' => "artworks-preview",
                    'slick_variant' => "large",
                    'items' => $previewItems,
                ])
            </div>
        </div>{{-- row --}}
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{!! url_to('katalog', ['author' => $author->name]) !!}" class="btn btn-default btn-outline sans" >{!! trans_choice('authority.button_show-all-artworks', $author->items_count, ['artworks_count' => $author->items_count])!!} <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>

    </div>
</section>
@endif



@stop


@section('javascript')
{!! Html::script('js/readmore.min.js') !!}
{!! Html::script('js/components/artwork_carousel.js') !!}
{!! Html::script('js/components/share_buttons.js') !!}

<script type="text/javascript">
    $(document).ready(function(){

        $('.expandable').readmore({
            moreLink: '<a href="#"><i class="fa fa-chevron-down"></i> {{ trans("general.show_more") }}</a>',
            lessLink: '<a href="#"><i class="fa fa-chevron-up"></i> {{ trans("general.show_less") }}</a>',
            maxHeight: 40,
            afterToggle: function(trigger, element, expanded) {
              if(! expanded) { // The "Close" link was clicked
                $('html, body').animate( { scrollTop: element.offset().top }, {duration: 100 } );
              }
            }
        });

    });
</script>
@stop
