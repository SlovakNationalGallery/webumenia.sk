<template>
    <div class="range-slider py-3">
        <div class="col-xs-4 col-sm-2 col-md-1 text-left text-sm-right">
            <input
                class="sans range-slider-from"
                maxlength="5"
                pattern="[-]?[0-9]{1,4}"
                step="5"
                @change="touched = true"
                v-model.lazy="yearRange[0]"
            />
        </div>
        <div
            class="col-xs-4 col-xs-offset-4 col-sm-2 col-sm-offset-0 col-sm-push-8 col-md-1 col-md-push-10 text-right text-sm-left"
        >
            <input
                class="sans range-slider-to"
                maxlength="5"
                pattern="[-]?[0-9]{1,4}"
                step="5"
                @change="touched = true"
                v-model.lazy="yearRange[1]"
            />
        </div>

        <div class="col-xs-12 col-sm-8 col-sm-pull-2 col-md-10 col-md-pull-1">
            <div class="pt-1" :id="name + '-slider'">
                <slider
                    :model-value="yearRange"
                    :min="min || 0"
                    :max="max || 30"
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
                        :id="name"
                        :name="name"
                        type="hidden"
                        v-bind:value="yearRange.join(',')"
                    />
                </fieldset>
            </div>
        </div>
    </div>
</template>

<script>
import VueSlider from 'vue-slider-component'

export default {
    components: {
        slider: VueSlider,
    },
    props: ['name', 'from', 'to', 'min', 'max'],
    methods: {
        submitForm() {
            this.touched = true
            this.$nextTick(function () {
                $('#' + this.name)
                    .parents('form')
                    .submit()
            })
        },
        updateYearRange(yearRange) {
            this.yearRange = yearRange
        },
    },
    data() {
        return {
            yearRange: this.from && this.to ? [this.from, this.to] : [this.min, this.max],

            // Marked as touched if the values have already been changed
            touched: this.from === this.min && this.to === this.max ? false : true,
        }
    },
}
</script>
