    <filter-popover-controller {{ $attributes }}>
        <template #button="pc">
            <div class="tw-border tw-border-transparent" :class="{'tw-border-gray-800': pc.isOpen}">
                <button
                    class="tw-border tw-border-gray-300 tw-bg-white tw-py-2.5 tw-px-4 tw-text-lg tw-font-bold hover:tw-border-gray-800"
                    v-bind:class="{'tw-border-gray-800': pc.isActive || pc.isOpen}"
                    @click="pc.togglePopover(pc.popoverName)">
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
        </template>
        <template #body="pc">
            <div v-if="pc.isOpen" v-on-clickaway="pc.closeOpenedPopover">
                {{ $body }}
            </div>
        </template>
    </filter-popover-controller>
