<template>
    <div>
        <div class="tw-flex tw-justify-end">
            <button
                @click="handleReset"
                class="tw-flex tw-text-sm tw-items-center tw-border tw-border-gray-300 tw-py-1 tw-px-1.5"
            >
                <svg
                    class="tw-mr-1.5 tw-w-4 tw-h-4 tw-fill-current"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 256"
                >
                    <path
                        d="M228,128a100,100,0,0,1-98.66,100H128a99.39,99.39,0,0,1-68.62-27.29,12,12,0,0,1,16.48-17.45,76,76,0,1,0-1.57-109c-.13.13-.25.25-.39.37L54.89,92H72a12,12,0,0,1,0,24H24a12,12,0,0,1-12-12V56a12,12,0,0,1,24,0V76.72L57.48,57.06A100,100,0,0,1,228,128Z"
                    ></path>
                </svg>
                <span>resetovať</span>
            </button>
        </div>
        <div class="tw-mt-6">
            <slider
                :value="[value.from, value.to]"
                :min="min || 0"
                :max="max || 30"
                tooltip="none"
                :duration="0"
                :dotSize="24"
                :height="2"
                lazy
                @drag-end="$emit('change', value)"
                @dragging="value = { from: $event[0], to: $event[1] }"
                class="tw-cursor-pointer"
            >
                <template #dot>
                    <svg
                        width="24"
                        height="25"
                        viewBox="0 0 24 25"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <circle cx="12" cy="12.6421" r="12" class="tw-fill-sky-300" />
                        <path opacity="0.4" d="M9 6.64209V18.6421" stroke="black" />
                        <path opacity="0.4" d="M15 6.64209V18.6421" stroke="black" />
                    </svg>
                </template>
                <template #process="{ style }">
                    <div class="vue-slider-process tw-bg-sky-300" :style="style"></div>
                </template>
            </slider>
        </div>
        <div class="tw-flex tw-justify-between tw-mt-4">
            <div>
                <input
                    class="tw-py-2 tw-pl-2 tw-w-14 tw-font-bold tw-text-sm tw-border tw-border-gray-300"
                    maxlength="5"
                    pattern="[-]?[0-9]{1,4}"
                    step="5"
                    @change="$emit('change', value)"
                    v-model.lazy="value.from"
                />
            </div>
            <div>
                <input
                    class="tw-text-right tw-pr-2 tw-py-2 tw-w-14 tw-font-bold tw-text-sm tw-border tw-border-gray-300"
                    maxlength="5"
                    pattern="[-]?[0-9]{1,4}"
                    step="5"
                    @change="$emit('change', value)"
                    v-model.lazy="value.to"
                />
            </div>
        </div>
    </div>
</template>

<script>
import VueSlider from 'vue-slider-component'

export default {
    props: {
        defaultFrom: Number,
        defaultTo: Number,
        min: Number,
        max: Number,
    },
    components: {
        slider: VueSlider,
    },
    methods: {
        handleReset() {
            this.value = { from: this.min, to: this.max }
            this.$emit('change')
        },
    },
    data() {
        return {
            value: { from: this.defaultFrom || this.min, to: this.defaultTo || this.max },
        }
    },
}
</script>
