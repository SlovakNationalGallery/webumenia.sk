@extends('layouts.master')

@section('content')
    <section class="tailwind-rules">
        <filter-new :authors="{{ $authors }}">
            <div class="tw-flex tw-space-x-3 tw-overflow-x-auto tw-bg-gray-200 tw-p-16">
                <div>
                    <filter-new-custom-select filter-name="authors"
                        placeholder="Napíšte meno autora / autorky">
                </div>
            </div>
            <filter-new-mobile-custom-select placeholder="Simple dummy text" />
        </filter-new>
    </section>

@stop
