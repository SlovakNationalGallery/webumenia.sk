<popover-controller v-slot="pc" {{ $attributes }}>
    <div class="tw-pb-2">
        <div class="tw-border tw-border-transparent" :class="{'tw-border-gray-800': pc.isOpen}">
            <button :id="`button-${pc.name}`"
                class="tw-border tw-border-gray-300 tw-bg-white tw-py-2.5 tw-px-4 tw-text-lg tw-font-bold hover:tw-border-gray-800"
                :class="{'tw-border-gray-800': pc.active || pc.isOpen}"
                @click="pc.togglePopover(pc.name)">
                <div class="tw-flex">
                    {{ $popover_label }}
                    <div class="tw-flex tw-items-center tw-pl-4">
                        <x-icons.caret-up v-if="pc.isOpen" class="tw-h-4 tw-w-4 tw-fill-sky-300">
                        </x-icons.caret-up>
                        <x-icons.caret-down v-else class="tw-h-4 tw-w-4 tw-fill-current">
                        </x-icons.caret-down>
                    </div>
                </div>
            </button>
        </div>
        <div :id="`body-${pc . name}`" class="tw-z-10">
            <clickaway-wrapper v-if="pc.isOpen" :on-clickaway="pc . closeOpenedPopover">
                <div>
                    {{ $body }}
                </div>
            </clickaway-wrapper>
        </div>
    </div>
</popover-controller>
