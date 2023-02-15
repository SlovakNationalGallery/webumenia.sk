<template>
    <div class="range-slider py-3">
        <div class="col-xs-6 col-sm-1 text-left text-sm-right">
            <input
                class="sans range-slider-from"
                maxlength="5"
                pattern="[-]?[0-9]{1,4}"
                step="5"
                @change="touched = true"
                v-model.lazy="tempYearRange[0]"
            />
        </div>
        <div class="col-xs-6 col-sm-1 col-sm-push-10 text-right text-sm-left">
            <input
                class="sans range-slider-to"
                maxlength="5"
                pattern="[-]?[0-9]{1,4}"
                step="5"
                @change="touched = true"
                v-model.lazy="tempYearRange[1]"
            />
        </div>
        <div class="col-xs-12 col-sm-10 col-sm-pull-1">
            <div class="pt-1" :id="filterName + '-slider'">
                <slider
                    v-model="tempYearRange"
                    :min="getMinMax().min || 0"
                    :max="getMinMax().max || 30"
                    tooltip="none"
                    :duration="0"
                    :dotSize="16"
                    lazy
                    @change="submitForm"
                    @drag-end="submitForm"
                    @dragging="updateYearRange"
                >
                </slider>
                <fieldset :disabled="!touched">
                    <input
                        :id="filterName"
                        :name="filterName"
                        type="hidden"
                        v-bind:value="tempYearRange.join(',')"
                    />
                </fieldset>
            </div>
        </div>
    </div>
</template>

<script>
import VueSlider from 'vue-slider-component'

export default {
    props: {
        yearRange: Array,
        filter: Object,
        filterName: String,
    },
    components: {
        slider: VueSlider,
    },
    methods: {
        submitForm() {
            this.touched = true
            this.$emit('change', this.tempYearRange)
        },
        updateYearRange(tempYearRange) {
            this.tempYearRange = tempYearRange
        },
        getFromTo() {            
            return { from: this.yearRange?.[0] || null, to: this.yearRange?.[1] || null }
        },
        getMinMax() {
            return this.filter || { min: null, max: null }
        },
    },
    data() {
        const { from, to } = this.getFromTo()
        const { min, max } = this.getMinMax()

        return {
            tempYearRange: from && to ? [from, to] : [min, max],
            // Marked as touched if the values have already been changed
            touched: from === min && to === max ? false : true,
        }
    },
}
</script>
