@props(['article'])

<div>
    @if ($article->main_image)
        <a href="{{ $article->getUrl() }}" class="image-container">
            <img data-src="{!! $article->getThumbnailImage() !!}" class="img-responsive lazyload"
                alt="{{ $article->title }}">
        </a>
    @endif
    <a href="{{ $article->getUrl() }}">
        <h4 class="title">
            {!! $article->title_with_category !!}
        </h4>
    </a>
    <div class="attributes">{!! $article->getShortTextAttribute($article->summary, 250) !!}
        (<a href="{{ $article->getUrl() }}">{{ trans('general.more') }}</a>)
    </div>
    <p class="meta mt-3">{!! $article->published_date !!} / {!! $article->author !!}</p>
</div>
