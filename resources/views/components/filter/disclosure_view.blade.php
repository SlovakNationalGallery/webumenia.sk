<div class="tw-grow tw-bg-white tw-flex tw-flex-col" v-if="{{ $attributes->get('v-if') }}">
    <div class="tw-mx-4 tw-my-6 tw-flex tw-items-end tw-justify-between">
        {{ $header }}
        <div class="tw-flex">
            {{ $reset_button }}
            <button @click="{{ $attributes->get('@close') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="tw-h-6 tw-w-6 tw-fill-current"
                    viewBox="0 0 256 256">
                    <path
                        d="M208.49,191.51a12,12,0,0,1-17,17L128,145,64.49,208.49a12,12,0,0,1-17-17L111,128,47.51,64.49a12,12,0,0,1,17-17L128,111l63.51-63.52a12,12,0,0,1,17,17L145,128Z">
                    </path>
                </svg>
            </button>
        </div>
    </div>
    <div class="tw-flex tw-grow tw-h-40 tw-flex-col">
        {{ $body }}
    </div>
</div>
