<template>
    <div>
        <div class="hue">
            <slider
                :pointerColorFn="hueColorFn"
                :value="getHue()"
                :min="0"
                :max="360"
                :step="1"
                @change="hueChange"
            />
        </div>
        <div v-if="color">
            <slider
                :bgColorFn="lightnessBgColorFn"
                :pointerColorFn="lightnessColorFn"
                @change="lightnessChange"
                :value="getLightness()"
                :min="0"
                :step="0.01"
                :max="1"
            />
        </div>
    </div>
</template>

<script>
import tinycolor from 'tinycolor2'

export default {
    props: {
        color: String,
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
        hueColorFn(hue) {
            return 'hsl(' + hue + ', 80%, 50%)'
        },
        lightnessColorFn(lightness) {
            return 'hsl(' + this.getHue() + ',80% ,' + lightness * 100 + '%)'
        },
        hueChange(hue) {
            const color = tinycolor(`hsl(${hue}, 0.8, ${this.getLightness() || 0.5})`).toHex()
            this.$emit('change', color)
        },

        lightnessChange(lightness) {
            const color = tinycolor(`hsl(${this.getHue()}, 80%, ${lightness * 100}%)`).toHex()
            this.$emit('change', color)
        },
        lightnessBgColorFn() {
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

<style>
.hue {
    background: linear-gradient(
        to right,
        #d82626 0%,
        #d8d826 17%,
        #26d826 33%,
        #26d8d8 50%,
        #2626d8 67%,
        #d826d8 83%,
        #d82626 100%
    );
}
</style>
