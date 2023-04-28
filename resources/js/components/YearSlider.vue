<template>
    <div class="range-slider py-3">
        <div class="col-xs-6 col-sm-1 tw-text-left sm:tw-text-right">
            <input
                class="tw-font-tw-font-sans range-slider-from"
                maxlength="5"
                pattern="[-]?[0-9]{1,4}"
                step="5"
                @change="touched = true"
                v-model.lazy="yearRange[0]"
            />
        </div>
        <div class="col-xs-6 col-sm-1 col-sm-push-10 tw-text-right sm:tw-text-left">
            <input
                class="tw-font-sans range-slider-to"
                maxlength="5"
                pattern="[-]?[0-9]{1,4}"
                step="5"
                @change="touched = true"
                v-model.lazy="yearRange[1]"
            />
        </div>

        <div class="col-xs-12 col-sm-10 col-sm-pull-1">
            <div class="pt-1" :id="name + '-slider'">
                <slider
                    v-model="yearRange"
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
    computed: {},
    data() {
        return {
            yearRange: this.from && this.to ? [this.from, this.to] : [this.min, this.max],

            // Marked as touched if the values have already been changed
            touched: this.from === this.min && this.to === this.max ? false : true,
        }
    },
}
</script>
