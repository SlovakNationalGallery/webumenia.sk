@props([
    'article',
    'url' => $article->getUrl(),
    'showEduTags' => false,
])

<div class="tailwind-rules">
    <div
        class="tw-container tw-mx-auto tw-flex tw-h-[30rem] tw-max-w-screen-xl tw-flex-wrap tw-px-6 tw-py-4 md:tw-h-[25.75rem] md:tw-flex-nowrap">
        <div class="tw-h-1/3 tw-w-full md:tw-h-full md:tw-w-1/2">
            @if ($article->main_image)
                <a href="{{ $url }}" class="tw-h-full">
                    <img src="{{ $article->getThumbnailImage() }}"
                        class="tw-h-full tw-w-full tw-object-cover" alt="{{ $article->title }}">
                </a>
            @endif
        </div>
        <div
            class="tw-flex tw-h-2/3 tw-w-full tw-flex-col tw-pt-4 md:tw-box-border md:tw-h-full md:tw-max-h-full md:tw-w-1/2 md:tw-px-12 md:tw-py-10">
            @if ($article->category?->name)
                <div class="tw-pb-3 tw-text-sm tw-font-medium tw-text-gray-600 md:tw-text-lg">
                    {{ Str::ucfirst($article->category->name) }}
                </div>
            @endif
            <div class="tw-pb-2 tw-text-2xl tw-font-semibold md:tw-text-4xl">
                <a href="{{ $url }}">
                    {{ $article->title }}
                </a>
            </div>
            <div class="tw-pb-4 tw-text-sm tw-text-gray-600 md:tw-text-base">
                <a class="hover:tw-underline"
                    href="{{ route('frontend.article.index', ['author' => $article->author]) }}">
                    {{ $article->author }}</a>
                &nbsp;&middot;&nbsp;
                @dateShort($article->created_at)
            </div>
            <div
                class="tw-mb-9 tw-min-h-0 tw-flex-1 tw-overflow-hidden tw-bg-gradient-to-b tw-from-gray-800 tw-via-gray-800 tw-to-white tw-bg-clip-text tw-font-serif tw-text-base tw-text-transparent md:tw-text-xl">
                {{ strip_tags(html_entity_decode($article->summary)) }}
            </div>
            <div class="tw-text-sm tw-text-gray-600 md:tw-text-base">
                {{ $article->reading_time }}
            </div>
        </div>
    </div>
</div>
