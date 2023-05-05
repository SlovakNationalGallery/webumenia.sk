<template>
    <div>
        <slider
            :model-value="[value.from, value.to]"
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
    watch: {
        defaultFrom(newDefaultFrom) {
            this.value.from = newDefaultFrom || this.min
        },
        defaultTo(newDefaultTo) {
            this.value.to = newDefaultTo || this.max
        },
    },
    data() {
        return {
            value: { from: this.defaultFrom || this.min, to: this.defaultTo || this.max },
        }
    },
}
</script>
