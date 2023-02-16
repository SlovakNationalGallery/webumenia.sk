<template>
    <div>
        <div>
            <div>
                <slider
                    v-model="value"
                    :min="min || 0"
                    :max="max || 30"
                    tooltip="none"
                    :duration="0"
                    :dotSize="16"
                    lazy
                    @change="handleChange"
                    @drag-end="handleChange"
                    @dragging="updateValue"
                >
                </slider>
                <fieldset :disabled="!touched">
                    <input
                        type="hidden"
                        v-bind:value="value.join(',')"
                    />
                </fieldset>
            </div>
        </div>
        <div class="tw-flex tw-justify-between">
            <div>
                <input
                    maxlength="5"
                    pattern="[-]?[0-9]{1,4}"
                    step="5"
                    @change="touched = true"
                    v-model.lazy="value[0]"
                />
            </div>
            <div>
                <input
                    maxlength="5"
                    pattern="[-]?[0-9]{1,4}"
                    step="5"
                    @change="touched = true"
                    v-model.lazy="value[1]"
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
            this.touched = true
            const from = this.value?.[0] || null
            const to = this.value?.[1] || null
            this.$emit('change', {from, to})
        },
        updateValue(value) {
            this.value = value
        },
    },
    data() {

        return {
            value: [this.defaultFrom || this.min, this.defaultTo || this.max],
            // Marked as touched if the values have already been changed
            touched: this.from === this.min && this.to === this.max ? false : true,
        }
    },
}
</script>
