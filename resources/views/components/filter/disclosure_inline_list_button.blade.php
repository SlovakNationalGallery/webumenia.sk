<toggle-controller v-slot="{isOpen, toggle}">
    <div class="tw-border-b tw-border-gray-300 hover:tw-border-gray-800">
        <button
            class="tw-flex tw-w-full tw-items-center tw-justify-between tw-bg-white tw-py-3.5 tw-px-4 tw-text-lg tw-font-medium hover:tw-border-gray-800"
            v-on:click="toggle">
            {{ $header }}
            <x-icons.caret-down class="tw-h-4 tw-w-4 tw-fill-current" v-if="isOpen" />
            <x-icons.caret-up class="tw-h-4 tw-w-4 tw-fill-current" v-else />
        </button>
        <div v-if="isOpen" class="tw-px-4 tw-pb-6">
            {{ $body }}
        </div>
    </div>
</toggle-controller>
