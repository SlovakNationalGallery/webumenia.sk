<template>
    <div>
        <slider
            :model-value="hue"
            @update:model-value="hue = $event"
            @dragging="immediateHue = $event"
            tooltip="none"
            :min="0"
            :max="360"
            :duration="0"
            :dot-size="44"
            :height="12"
            :process="false"
            :rail-style="{
                background:
                    'linear-gradient(to right, #d82626 0%, #d8d826 17%, #26d826 33%, #26d8d8 50%, #2626d8 67%, #d826d8 83%, #d82626 100%)',
            }"
            lazy
            class="tw-cursor-pointer"
        >
            <template #dot>
                <div class="tw-flex tw-h-full tw-w-full tw-justify-center tw-items-center">
                    <svg
                        width="24"
                        height="25"
                        viewBox="0 0 24 25"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <circle cx="12" cy="12.6421" r="12" :fill="`#${hueCircleFill}`" />
                        <path opacity="0.4" d="M9 6.64209V18.6421" stroke="black" />
                        <path opacity="0.4" d="M15 6.64209V18.6421" stroke="black" />
                    </svg>
                </div>
            </template>
        </slider>
        <slider
            v-if="color"
            :model-value="lightness"
            @update:model-value="lightness = $event"
            @dragging="immediateLightness = $event"
            tooltip="none"
            :min="0"
            :max="1"
            :interval="0.01"
            :duration="0"
            :dot-size="44"
            :height="12"
            :process="false"
            :rail-style="{ background: lightnessBackground }"
            lazy
            class="tw-cursor-pointer tw-mt-3"
        >
            <template #dot>
                <div class="tw-flex tw-h-full tw-w-full tw-justify-center tw-items-center">
                    <svg
                        width="24"
                        height="25"
                        viewBox="0 0 24 25"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <circle cx="12" cy="12.6421" r="12" :fill="`#${lightnessCircleFill}`" />
                        <path opacity="0.4" d="M9 6.64209V18.6421" stroke="black" />
                        <path opacity="0.4" d="M15 6.64209V18.6421" stroke="black" />
                    </svg>
                </div>
            </template>
        </slider>
    </div>
</template>
<script>
import tinycolor from 'tinycolor2'
import VueSlider from 'vue-slider-component'

const defaultHue = 180
const defaultLightness = 0.5

export default {
    props: {
        defaultColor: String,
    },
    data() {
        const hue = this.defaultColor ? tinycolor(this.defaultColor).toHsl()?.h : defaultHue
        const lightness = this.defaultColor
            ? tinycolor(this.defaultColor)?.toHsl()?.l
            : defaultLightness
        return {
            hue,
            immediateHue: hue,
            lightness,
            immediateLightness: lightness,
        }
    },
    components: {
        slider: VueSlider,
    },
    watch: {
        defaultColor(newDefaultColor) {
            this.hue = newDefaultColor ? tinycolor(newDefaultColor).toHsl()?.h : defaultHue
            this.lightness = newDefaultColor
                ? tinycolor(newDefaultColor)?.toHsl()?.l
                : defaultLightness
        },
        hue(newHue) {
            this.immediateHue = newHue
            this.$emit('change', this.color)
        },
        lightness(newLightness) {
            this.immediateLightness = newLightness
            this.$emit('change', this.color)
        },
    },
    computed: {
        color() {
            return tinycolor(`hsl(${this.hue}, 0.8, ${this.lightness * 100}%)`).toHex()
        },
        hueCircleFill() {
            return tinycolor(`hsl(${this.immediateHue}, 0.8, 50%)`).toHex()
        },
        lightnessCircleFill() {
            return tinycolor(
                `hsl(${this.immediateHue}, 0.8, ${this.immediateLightness * 100}%)`
            ).toHex()
        },
        lightnessBackground() {
            return `linear-gradient(to right, hsl(${this.immediateHue}, 80%, 0%) 0%, hsl(${this.immediateHue}, 80%, 50%) 50%, hsl(${this.immediateHue}, 80%, 100%) 100%)`
        },
    },
}
</script>
