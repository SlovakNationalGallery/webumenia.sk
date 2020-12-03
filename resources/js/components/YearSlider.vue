<template>
  <div class="range-slider py-2">
    <div class="col-xs-6 col-sm-1 text-left text-sm-right">
      <input
        class="sans range-slider-from"
        maxlength="5"
        pattern="[-]?[0-9]{1,4}"
        step="5"
        v-model.lazy="yearRange[0]"
      />
    </div>
    <div class="col-xs-6 col-sm-1 col-sm-push-10 text-right text-sm-left">
      <input
        class="sans range-slider-to"
        maxlength="5"
        pattern="[-]?[0-9]{1,4}"
        step="5"
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
        <input
          :id="name"
          :name="name"
          type="hidden"
          v-bind:value="yearRange.join(',')"
        />
      </div>
    </div>
  </div>
</template>

<script>
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/default.css'

export default {
  components: {
    'slider': VueSlider,
  },
  props: ['name', 'from', 'to', 'min', 'max'],
  methods: {
    submitForm() {
      this.$nextTick(function () {
        $("#" + this.name).parents("form").submit()
      })
    },
    updateYearRange(yearRange) {
      this.yearRange = yearRange
    }
  },
  data() {
    return {
      yearRange: this.from && this.to ? [this.from, this.to] : [this.min, this.max],
    }
  },
}
</script>
