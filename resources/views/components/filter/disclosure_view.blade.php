<div
    {{ $attributes->except('v-on:close')->merge(['class' => 'tw-grow tw-bg-white tw-flex tw-flex-col']) }}>
    <div class="tw-mx-4 tw-my-6 tw-flex tw-items-center tw-justify-between">
        {{ $header }}
        <div class="tw-flex">
            {{ $reset_button }}
            <button @click="{{ $attributes->get('v-on:close') }}">
                <x-icons.x class="tw-h-6 tw-w-6 tw-fill-current">
                </x-icons.x>
            </button>
        </div>
    </div>
    <div class="tw-flex tw-grow tw-flex-col">
        {{ $body }}
    </div>
</div>
