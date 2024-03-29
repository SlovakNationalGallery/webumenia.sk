@props(['collection'])

<div class="tailwind-rules">
    <div class="tw-max-w-full tw-justify-center tw-align-middle tw-font-normal file:tw-flex">
        <div class="tw-bg-gray-200 tw-p-6 md:tw-pt-4">
            <div class="tw-mb-3 tw-text-base tw-font-medium">
                {{ trans('articles.we_recommend') }}
            </div>
            <div
                class="tw-mx-auto tw-min-h-[20rem] md:tw-flex md:tw-min-h-[12.5rem] md:tw-items-stretch">
                <div class="tw-h-[10rem] tw-w-full md:tw-h-auto md:tw-w-1/2">
                    @if ($collection->getThumbnailImage())
                        {{-- TODO: Remove tw-border once we remove conflicting css classes --}}
                        <a href="{{ $collection->getUrl() }}"
                            class="tw-block tw-h-full tw-w-full !tw-border-0">
                            <img src="{{ $collection->getThumbnailImage() }}"
                                class="tw-m-0 tw-h-full tw-w-full tw-object-cover tw-p-0"
                                alt="{{ $collection->title }}">
                        </a>
                    @endif
                </div>
                <div class="tw-flex tw-w-full tw-flex-col md:tw-box-border md:tw-w-1/2 md:tw-pl-6">
                    <div class="tw-pt-4 tw-pb-1 tw-text-2xl tw-font-semibold md:tw-pt-0">
                        {{-- TODO: Remove tw-border once we remove conflicting css classes --}}
                        <a href="{{ $collection->getUrl() }}" class="!tw-border-0">
                            {{ $collection->name }}
                        </a>
                    </div>
                    <div class="tw-pb-4 tw-text-sm tw-text-gray-600 md:tw-text-base">
                        {{ $collection->author }}
                    </div>
                    <div
                        class="tw-mb-3 tw-font-serif tw-text-sm tw-text-gray-800 tw-line-clamp-5 md:tw-text-base">
                        {{ strip_tags(html_entity_decode($collection->text)) }}
                    </div>
                    <div class="tw-mt-auto tw-text-sm tw-text-gray-600">
                        {{ $collection->reading_time }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
