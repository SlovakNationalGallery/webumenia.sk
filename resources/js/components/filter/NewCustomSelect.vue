<template>
    <div class="tw-min-w-max">
        <button
            :class="`tw-bg-white tw-border tw-border-gray-300
             hover:tw-border-gray-800 ${active ? '!tw-border-gray-800 tw-border-1' : ''} ${
                opened ? '!tw-border-gray-800 tw-border-2' : ''
            } tw-text-lg tw-font-bold tw-py-3.5 tw-px-4`"
        >
            <div class="tw-flex">
                <span>{{ name }}</span>
                <div class="tw-pl-4 tw-flex tw-items-center">
                    <font-awesome-icon
                        v-if="opened"
                        icon="fa-solid fa-caret-up"
                        class="tw-text-sky-400"
                    />
                    <font-awesome-icon
                        v-else
                        icon="fa-solid fa-caret-down"
                        class="tw-text-gray-800"
                    />
                </div>
            </div>
        </button>
        <!-- TODO: Position modal with js: https://css-tricks.com/popping-hidden-overflow/ -->
        <div
            v-if="opened"
            class="tw-w-[20rem] tw-h-[30rem] tw-border-2 tw-p-6 tw-bg-white tw-border-gray-800 tw-z-10 tw-absolute tw-top-36"
        >
            <!-- <Options :options="mockOptions" :placeholder="placeholder" /> -->
            <button
                :class="`tw-bg-white tw-px-4 tw-font-normal tw-py-1.5 tw-text-sm tw-border tw-border-gray-300 hover:tw-border-gray-800`"
            >
                <div class="tw-flex">
                    <div class="tw-pr-2 tw-flex tw-items-center">
                        <font-awesome-icon
                            icon="fa-solid fa-rotate-left"
                            class="tw-text-gray-800"
                        />
                    </div>
                    <span>zrušiť výber</span>
                </div>
            </button>
        </div>
    </div>
</template>

<script>
//import Options from './Options.vue'
import '@fortawesome/vue-fontawesome'

export default {
    props: {
        name: String,
        placeholder: String,
        active: Boolean,
        opened: Boolean,
    },
    // components: { Options },
}
</script>
