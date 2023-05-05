<template>
    <div>
        <slider
            tooltip="none"
            :model-value="getHue()"
            :min="0"
            :max="360"
            :duration="0"
            :dotSize="24"
            :height="12"
            :process="false"
            :railStyle="{'background': 'linear-gradient(to right, #d82626 0%, #d8d826 17%, #26d826 33%, #26d8d8 50%, #2626d8 67%, #d826d8 83%, #d82626 100%)'}"
            lazy
            class="tw-cursor-pointer"
            @dragging="hueChange"
            @change="$emit('change', color)"
        >
            <template #dot>
                <svg
                    width="24"
                    height="25"
                    viewBox="0 0 24 25"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <circle cx="12" cy="12.6421" r="12" :fill="hueColor(getHue())" />
                    <path opacity="0.4" d="M9 6.64209V18.6421" stroke="black" />
                    <path opacity="0.4" d="M15 6.64209V18.6421" stroke="black" />
                </svg>
            </template>
        </slider>
        <slider
            v-if="color"
            tooltip="none"
            :model-value="getLightness()"
            :min="0"
            :max="1"
            :interval="0.01"
            :duration="0"
            :dotSize="24"
            :height="12"
            :process="false"
            :railStyle="lightnessBgColor()"
            lazy
            class="tw-cursor-pointer tw-mt-3"
            @dragging="lightnessChange"
            @change="$emit('change', color)"
        >
            <template #dot>
                <svg
                    width="24"
                    height="25"
                    viewBox="0 0 24 25"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <circle cx="12" cy="12.6421" r="12" :fill="`#${color}`" />
                    <path opacity="0.4" d="M9 6.64209V18.6421" stroke="black" />
                    <path opacity="0.4" d="M15 6.64209V18.6421" stroke="black" />
                </svg>
            </template>
        </slider>
    </div>
</template>
<script>
import tinycolor from 'tinycolor2'
import VueSlider from 'vue-slider-component'

export default {
    props: {
        defaultColor: String,
    },
    data() {
        return {
            color: this.defaultColor,
        }
    },
    components: {
        slider: VueSlider,
    },
    watch: {
        defaultColor(newDefaultColor) {
            this.color = newDefaultColor
        },
    },
    methods: {
        getHue() {
            const hue = tinycolor(this.color || null).toHsl()?.h
            return hue || 180
        },
        getLightness() {
            const lightness = tinycolor(this.color)?.toHsl()?.l
            return lightness
        },
        hueColor(hue) {
            return 'hsl(' + hue + ', 80%, 50%)'
        },
        hueChange(hue) {
            this.color = tinycolor(`hsl(${hue}, 0.8, ${this.getLightness() || 0.5})`).toHex()
        },
        lightnessChange(lightness) {
            this.color = tinycolor(`hsl(${this.getHue()}, 80%, ${lightness * 100}%)`).toHex()
        },
        lightnessBgColor() {
            return {
                background:
                    'linear-gradient(to right, hsl(' +
                    tinycolor(this.color).toHsl()?.h +
                    ', 80%, 0%) 0%, hsl(' +
                    tinycolor(this.color).toHsl()?.h +
                    ', 80%, 50%) 50%, hsl(' +
                    tinycolor(this.color).toHsl()?.h +
                    ', 80%, 100%) 100%)',
            }
        },
    },
}
</script>
