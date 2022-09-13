@php

if (isset($id)) {
  $article = App\Article::where('id',  $id)->firstOrFail();
}

$url = $url ?? $article->getUrl();
$showEduTags = $showEduTags ?? false;
    
@endphp
<div class="article-thumbnail">
  @if ($article->main_image)
    <a href="{{ $url }}" class="image-container">
      <img data-src="{!! $article->getThumbnailImage() !!}" class="img-responsive lazyload" alt="{{ $article->title }}">
    </a>
  @endif
  <a href="{{ $url }}">
    <h4 class="title">
      {!! $article->title_with_category !!}
    </h4>
  </a>
  <div class="attributes">{!! $article->getShortTextAttribute($article->summary, 250) !!}
    (<a href="{{ $url }}">{{ trans('general.more') }}</a>)
  </div>
  @if($showEduTags)
  <div class="mt-3">
    @foreach ($article->edu_target_age_groups ?? [] as $ageGroup)
      <a href="{{ route('frontend.educational-article.index', ['age_group' => $ageGroup]) }}" class="btn btn-default btn-xs btn-outline">{{ trans("edu.age_group.$ageGroup") }}</a>
    @endforeach
    @foreach ($article->edu_media_types ?? [] as $mediaType)
      <a href="{{ route('frontend.educational-article.index', ['media_type' => $mediaType]) }}" class="btn btn-default btn-xs btn-outline">{{ trans("edu.media_type.$mediaType") }}</a>
    @endforeach
    @foreach ($article->edu_keywords ?? [] as $keyword)
      <a href="{{ route('frontend.educational-article.index', ['keyword' => $keyword]) }}" class="btn btn-default btn-xs btn-outline">{{ $keyword }}</a>
    @endforeach
  </div>
  @endif
  <p class="meta mt-3">{!!$article->published_date!!} / {!!$article->author!!}</p>
</div>
