<template>
    <div>
        <div>
            <div>
                <slider
                    v-model="yearRange"
                    :min="min || 0"
                    :max="max || 30"
                    tooltip="none"
                    :duration="0"
                    :dotSize="16"
                    lazy
                    @change="handleChange"
                    @drag-end="handleChange"
                    @dragging="updateYearRange"
                >
                </slider>
                <fieldset :disabled="!touched">
                    <input
                        type="hidden"
                        v-bind:value="yearRange.join(',')"
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
                    v-model.lazy="yearRange[0]"
                />
            </div>
            <div>
                <input
                    maxlength="5"
                    pattern="[-]?[0-9]{1,4}"
                    step="5"
                    @change="touched = true"
                    v-model.lazy="yearRange[1]"
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
            const from = this.yearRange?.[0] || null
            const to = this.yearRange?.[1] || null
            this.$emit('change', {from, to})
        },
        updateYearRange(yearRange) {
            this.yearRange = yearRange
        },
    },
    data() {

        return {
            yearRange: this.defaultFrom && this.defaultTo ? [this.defaultFrom, this.defaultTo] : [this.min, this.max],
            // Marked as touched if the values have already been changed
            touched: this.from === this.min && this.to === this.max ? false : true,
        }
    },
}
</script>
