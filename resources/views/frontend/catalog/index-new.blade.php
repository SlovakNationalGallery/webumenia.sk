@extends('layouts.master')

@section('content')
    <section class="tailwind-rules">
        <filter-new :authors="{{ $authors }}" v-slot="{filters}">
            <div class="tw-flex tw-space-x-3 tw-overflow-x-auto tw-bg-gray-200 tw-p-16">
                <template v-for="filterName in Object.keys(filters)">
                    <filter-new-custom-select :filter-name="filterName"
                        placeholder="Napíšte meno autora / autorky">
                </template>
            </div>
            <filter-new-mobile-custom-select :filters="filters" placeholder="Simple dummy text" />
        </filter-new>
    </section>

@stop
