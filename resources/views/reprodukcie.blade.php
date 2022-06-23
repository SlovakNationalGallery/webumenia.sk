@extends('layouts.master')

@section('title')
    {{ trans('reprodukcie.title') }} |
    @parent
@stop

@section('content')

    <div class="tailwind-rules">
        <div class="tw-flex tw-flex-col tw-items-center">
            <div class="tw-relative tw-flex tw-w-full tw-justify-center tw-bg-gray-300">
                <img src="{{ asset('/images/reprodukcie/pracovisko.jpg') }}"
                    class="tw-absolute tw-h-full tw-w-full tw-object-cover">
                <h1
                    class="mx-4 tw-relative tw-my-40 tw-text-5xl tw-text-white md:tw-my-52 md:tw-text-6xl">
                    {{ utrans('reprodukcie.title') }}</h1>
            </div>

            <div
                class="tw-mt-12 tw-text-center tw-text-gray-600 tw-underline-offset-4 tw-transition-colors prose-a:tw-underline prose-a:tw-decoration-gray-300 hover:prose-a:tw-decoration-gray-800 prose-strong:tw-text-gray-800 md:tw-w-8/12 lg:tw-text-2xl">
                {!! trans('reprodukcie.lead', ['total' => $total]) !!}
            </div>

            <hr class="tw-my-12 tw-w-full tw-border-t">

            <section id="print"
                class="tw-container tw-flex tw-max-w-screen-xl tw-flex-col tw-items-center tw-px-6">
                <div class="lg:tw-w-8/12">
                    @include('components.notice', compact('notice'))

                    <h2 class="tw-my-5 tw-text-center tw-text-2xl">
                        {{ trans('reprodukcie.print_heading') }}
                    </h2>
                    <div
                        class="tw-font-serif prose-p:tw-mb-4 prose-a:tw-underline prose-a:tw-decoration-gray-300 prose-a:tw-underline-offset-2 prose-a:tw-transition-colors hover:prose-a:tw-decoration-gray-800 md:tw-text-lg">
                        {!! trans('reprodukcie.print_body') !!}
                    </div>

                    <div class="tw-mt-8 tw-grid tw-gap-x-8 tw-gap-y-10 md:tw-grid-cols-2">
                        <x-reproductions.repro-offer :title="trans('reprodukcie.print_offer_standalone_title')"
                            img-url="/images/reprodukcie/format-1-samostatna.jpg"
                            img-full-url="/images/reprodukcie/full/format-1-samostatna.jpg"
                            :description="trans('reprodukcie.print_offer_standalone_description')" :pricing-options="[
                                [
                                    trans('reprodukcie.print_offer_until') . ' <strong>A4</strong>',
                                    '(21 x 29,7 cm)',
                                    '28',
                                ],
                                [
                                    trans('reprodukcie.print_offer_until') .
                                    ' <strong>A3+</strong>',
                                    '(32,9 x 48,3 cm)',
                                    '40',
                                ],
                                [
                                    trans('reprodukcie.print_offer_until') . ' <strong>A2</strong>',
                                    '(42 x 59,4 cm)',
                                    '50',
                                ],
                                [
                                    trans('reprodukcie.print_offer_until') . ' <strong>A1</strong>',
                                    '(59,4 x 84,1 cm)',
                                    '60',
                                ],
                            ]" />

                        <x-reproductions.repro-offer :title="trans('reprodukcie.print_offer_passepartout_title')"
                            img-url="/images/reprodukcie/format-2-pasparta.jpg"
                            img-full-url="/images/reprodukcie/full/format-2-pasparta.jpg"
                            :description="trans('reprodukcie.print_offer_passepartout_description')" :pricing-options="[
                                [
                                    trans('reprodukcie.print_offer_until') . ' <strong>A4</strong>',
                                    '(min. 21 x 29,7 cm)',
                                    '38',
                                ],
                                [
                                    trans('reprodukcie.print_offer_until') .
                                    ' <strong>A3+</strong>',
                                    '(min. 30 x 40 cm)',
                                    '55',
                                ],
                            ]" />

                        <x-reproductions.repro-offer :title="trans('reprodukcie.print_offer_framed_title')"
                            img_url="/images/reprodukcie/format-3-ram.jpg"
                            img_full_url="/images/reprodukcie/full/format-3-ram.jpg" :description="trans('reprodukcie.print_offer_framed_description')"
                            :pricing-options="[
                                [
                                    trans('reprodukcie.print_offer_until') . ' <strong>A4</strong>',
                                    '(35 x 45 cm)',
                                    '48',
                                ],
                                [
                                    trans('reprodukcie.print_offer_until') .
                                    ' <strong>A3+</strong>',
                                    '(47 x 58 cm)',
                                    '65',
                                ],
                            ]" />
                        <x-reproductions.repro-offer :title="trans('reprodukcie.print_offer_poster_title')"
                            img_url="/images/reprodukcie/format-4-plagat.jpg"
                            img_full_url="/images/reprodukcie/full/format-4-plagat.jpg"
                            :description="trans('reprodukcie.print_offer_poster_description')" :pricing-options="[['<strong>A1</strong>', '(60 x 90cm)', '38']]" />
                    </div>

                    <div class="tw-mt-10 tw-text-gray-600">
                        <h3 class="tw-text-center tw-text-lg">
                            {{ trans('reprodukcie.info_title') }}
                        </h3>
                        <div
                            class="tw-mt-5 tw-font-serif prose-ul:tw-ml-5 prose-ul:tw-list-disc prose-li:tw-mb-2">
                            {!! trans('reprodukcie.print_list') !!}
                        </div>
                    </div>
                </div>

                <h3 class="tw-mt-10 tw-text-center tw-text-lg tw-text-gray-600">
                    {{ trans('reprodukcie.print_recommended') }}
                </h3>

                <x-reproductions.carousel class="tw-mt-6 tw-self-stretch">
                    @foreach ($items as $item)
                        <a href="{{ route('dielo', ['id' => $item]) }}"
                            class="tw-ml-4 tw-w-max first:tw-ml-0">
                            <x-item_image :id="$item->id"
                                src="{{ route('dielo.nahlad', ['id' => $item->id, 'width' => 70]) }}"
                                class="tw-h-48"
                                onload="this.onload=null;this.sizes=Math.ceil(this.getBoundingClientRect().width/window.innerWidth*100)+'vw';"
                                sizes="1px" />
                        </a>
                    @endforeach
                </x-reproductions.carousel>

                <x-reproductions.button :href="route('frontend.catalog.index', ['is_for_reproduction' => 1])" class="tw-mt-8 tw-inline-block">
                    {{ trans('reprodukcie.more-items_button') }}
                    ({{ $total }})
                </x-reproductions.button>
            </section>

            <hr class="tw-my-12 tw-w-full tw-border-t">

            <section id="digital"
                class="tw-container tw-flex tw-max-w-screen-xl tw-flex-col tw-items-center tw-px-6">
                <div class="lg:tw-w-8/12">
                    <h2 class="tw-my-5 tw-text-center tw-text-2xl">
                        {{ trans('reprodukcie.digital_heading') }}
                    </h2>
                    <div
                        class="tw-font-serif prose-p:tw-mb-4 prose-a:tw-underline prose-a:tw-decoration-gray-300 prose-a:tw-underline-offset-2 prose-a:tw-transition-colors hover:prose-a:tw-decoration-gray-800 md:tw-text-lg">
                        {!! trans('reprodukcie.digital_body') !!}
                    </div>

                    <h3 class="tw-mt-10 tw-text-center tw-text-lg tw-text-gray-600">
                        {{ trans('reprodukcie.digital_examples') }}
                    </h3>
                </div>

                <div class="tw-mt-8 tw-grid tw-gap-y-4 tw-gap-x-6 tw-self-stretch md:tw-grid-cols-2">
                    <a href="/images/reprodukcie/full/digirepro-1.jpg" class="popup">
                        <img class="tw-w-full" src="/images/reprodukcie/digirepro-1.jpg"
                            alt="{{ trans('reprodukcie.digital_example_1') }}">
                    </a>
                    <a href="/images/reprodukcie/full/digirepro-2.jpg" class="popup">
                        <img class="tw-w-full" src="/images/reprodukcie/digirepro-2.jpg"
                            alt="{{ trans('reprodukcie.digital_example_2') }}">
                    </a>
                    <a href="/images/reprodukcie/full/digirepro-3.jpg" class="popup">
                        <img class="tw-w-full" src="/images/reprodukcie/digirepro-3.jpg"
                            alt="{{ trans('reprodukcie.digital_example_3') }}">
                    </a>
                    <a href="/images/reprodukcie/full/digirepro-4.jpg" class="popup">
                        <img class="tw-w-full" src="/images/reprodukcie/digirepro-4.jpg"
                            alt="{{ trans('reprodukcie.digital_example_4') }}">
                    </a>
                </div>

                <div class="tw-mt-10 tw-text-gray-600 lg:tw-w-8/12">
                    <h3 class="tw-text-center tw-text-lg">
                        {{ trans('reprodukcie.info_title') }}
                    </h3>
                    <div
                        class="tw-mt-5 tw-font-serif prose-ul:tw-ml-5 prose-ul:tw-list-disc prose-li:tw-mb-2">
                        {!! trans('reprodukcie.digital_list') !!}
                    </div>
                </div>

                <h3 class="tw-mt-10 tw-text-center tw-text-lg tw-text-gray-600">
                    {{ trans('reprodukcie.digital_choice') }}
                </h3>

                <x-reproductions.carousel class="tw-mt-6 tw-self-stretch">
                    @foreach ($items as $item)
                        <a href="{{ route('dielo', ['id' => $item]) }}"
                            class="tw-ml-4 tw-w-max first:tw-ml-0">
                            <x-item_image :id="$item->id"
                                src="{{ route('dielo.nahlad', ['id' => $item->id, 'width' => 70]) }}"
                                class="tw-h-48"
                                onload="this.onload=null;this.sizes=Math.ceil(this.getBoundingClientRect().width/window.innerWidth*100)+'vw';"
                                sizes="1px" />
                        </a>
                    @endforeach
                </x-reproductions.carousel>

                <x-reproductions.button :href="route('frontend.catalog.index', ['is_for_reproduction' => 1])" class="tw-mt-8 tw-mb-16 tw-inline-block">
                    {{ trans('reprodukcie.more-items_button') }}
                    ({{ $total }})
                </x-reproductions.button>
            </section>
        </div>
    </div>

    {{-- modal for image preview --}}
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <img src="" class="imagepreview" style="width: 100%;">
                    <p class="text-center top-space imagetitle"></p>
                </div>
            </div>
        </div>
    </div>
    {{-- /modal for image preview --}}

@stop

@section('javascript')
    @include('components.artwork_carousel_js', [
        'slick_query' => '.artworks-preview',
    ])

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
