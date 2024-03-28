<toggle-controller v-slot="{isOn, toggle}">
    <div class="tw-border-b tw-border-gray-300">
        <button
            class="tw-flex tw-w-full tw-items-center tw-justify-between tw-bg-white tw-py-3.5 tw-px-4"
            v-on:click="toggle">
            {{ $header }}
            <x-icons.caret-up class="tw-h-4 tw-w-4 tw-fill-current" v-if="isOn" />
            <template v-else="">
                <x-icons.caret-down class="tw-h-4 tw-w-4 tw-fill-current" />
            </template>
        </button>
        <div v-if="isOn" class="tw-px-4 tw-pb-6">
            {{ $body }}
        </div>
    </div>
</toggle-controller>
