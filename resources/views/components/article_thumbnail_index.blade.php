@php

$url = $url ?? $article->getUrl();
$showEduTags = $showEduTags ?? false;

@endphp
<div class="tailwind-rules">
    <div
        class="tw-container tw-mx-auto tw-flex tw-h-[475px] tw-max-w-screen-xl tw-flex-wrap tw-px-6 tw-py-4 md:tw-h-[400px] md:tw-flex-nowrap md:tw-py-8">
        <div class="tw-h-1/3 tw-w-full md:tw-mr-4 md:tw-h-full md:tw-w-1/2">
            @if ($article->main_image)
                <a href="{{ $url }}" class="tw-h-full">
                    <img src="{!! $article->getThumbnailImage() !!}"
                        class="tw-h-full tw-w-full tw-object-cover" alt="{{ $article->title }}">
                </a>
            @endif
        </div>
        <div class="tw-flex tw-h-2/3 tw-w-full tw-flex-col tw-pt-4 md:tw-pt-0 md:tw-ml-4 md:tw-max-h-full md:tw-w-1/2">
            @if ($article->category?->name)
                <div class="tw-pb-3 tw-text-lg tw-font-medium tw-text-gray-600">
                    {{ $article->category->name }}
                </div>
            @endif
            <div class="tw-pb-2 tw-text-2xl tw-font-semibold">
                <a href="{{ $url }}">
                    {{ $article->title }}
                </a>
            </div>
            <div class="tw-pb-4 tw-text-base tw-text-gray-600">
                <span class="hover:tw-underline">
                    {{ $article->author }}</span> &nbsp;&middot;&nbsp;
                {!! $article->created_at->format('d. m. Y') !!}
            </div>
            <div class="tw-mb-3 tw-min-h-0 tw-overflow-hidden tw-font-serif">
                {{ strip_tags(html_entity_decode($article->summary)) }}
            </div>
            <div class="tw-text-sm tw-text-gray-600">
                {{ $article->reading_time }}
            </div>
        </div>
    </div>
</div>
