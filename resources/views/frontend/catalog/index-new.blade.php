@extends('layouts.master')

@section('content')
    <section class="tailwind-rules">
        <div class="tw-relative">
            <div class="tw-flex tw-space-x-3 tw-bg-gray-200 tw-p-16 tw-overflow-x-auto">
            <div>
                <filter-new-custom-select name="autor / autorka"
                    placeholder="Napíšte meno autora / autorky" opened />
            </div>
            <div>
                <filter-new-custom-select name="filter active" placeholder="Simple dummy text" active />
            </div>
            <div>
                <filter-new-custom-select name="filter vanilla" placeholder="Simple dummy text" />
            </div>
        </div>
        <filter-new-mobile-custom-select placeholder="Simple dummy text" />
    </section>

@stop
