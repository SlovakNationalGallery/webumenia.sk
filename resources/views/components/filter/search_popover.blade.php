<filter-popover-controller {{ $attributes->except('v-bind:is-active') }}>
    <template #button="pc">
        <div class="tw-border"
            v-bind:class="pc.isOpen ?'tw-border-gray-800' : 'tw-border-transparent'">
            <button
                class="tw-border tw-bg-white tw-py-2.5 tw-px-4 hover:tw-border-gray-800"
                v-bind:class="(pc.isOpen || {{ $attributes->get('v-bind:is-active') }}) ?'tw-border-gray-800' : 'tw-border-gray-300'"
                @click="pc.togglePopover(pc.popoverName)">
                <div class="tw-flex">
                    {{ $popover_label }}
                    <div class="tw-flex tw-items-center tw-pl-4">
                        <x-icons.caret-up v-if="pc.isOpen" class="tw-h-4 tw-w-4 tw-fill-sky-300" />
                        <template v-else>
                            <x-icons.caret-down class="tw-h-4 tw-w-4 tw-fill-current" />
                        </template>
                    </div>
                </div>
            </button>
        </div>
    </template>
    <template #body="pc">
        <transition enter-from-class="tw-opacity-0" leave-to-class="tw-opacity-0"
            enter-active-class="tw-transition tw-duration-100"
            leave-active-class="tw-transition tw-duration-100">
            <div v-if="pc.isOpen" v-click-away="pc.closeOpenedPopover">
                {{ $body }}
            </div>
        </transition>
    </template>
</filter-popover-controller>
