<template>
    <div>
        <div>
            <div>
                <slider
                    :value="[value.from, value.to]"
                    :min="min || 0"
                    :max="max || 30"
                    tooltip="none"
                    :duration="0"
                    :dotSize="16"
                    lazy
                    @drag-end="handleChange"
                    @dragging="updateValue"
                >
                </slider>
            </div>
        </div>
        <div class="tw-flex tw-justify-between">
            <div>
                <input
                    maxlength="5"
                    pattern="[-]?[0-9]{1,4}"
                    step="5"
                    @change="handleChange"
                    v-model.lazy="value.from"
                />
            </div>
            <div>
                <input
                    maxlength="5"
                    pattern="[-]?[0-9]{1,4}"
                    step="5"
                    @change="handleChange"
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
        handleChange() {
            this.$emit('change', this.value)
        },
        updateValue(eventValue) {
            const value = { from: eventValue[0] || null, to: eventValue[1] || null }
            this.value = value
        },
    },
    data() {
        return {
            value: { from: this.defaultFrom || this.min, to: this.defaultTo || this.max },
            touched: this.from === this.min && this.to === this.max ? false : true,
        }
    },
}
</script>
