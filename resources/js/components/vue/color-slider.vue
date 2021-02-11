<template>
  <div role="application" aria-label="Slider color picker" class="vc-slider">
    <div class="hue">
      <slider
        :min="0"
        :max="360"
        :value="colors.hsl.h"
        :startEmpty="!value || !value.a"
        @change="hueChange"
        @changemouseup="changeMouseUp"
        :pointerColorFn="hueColorFn"
      />
    </div>
    <transition name="fade">
      <div v-show="!!value.hex">
        <slider
          v-show="!!value.hex"
          :min="0"
          :max="1"
          :step="0.01"
          :value="colors.hsl.l"
          @change="lightnessChange"
          @changemouseup="changeMouseUp"
          :pointerColorFn="lightColorFn"
          :bgColorFn="lightBgColorFn"
          :startEmpty="false"
        />
      </div>
    </transition>
  </div>
</template>

<script>
import color from "./mixins/color.js";

export default {
  name: "color-slider",
  mixins: [color],
  props: ["value"],
  data() {
    return {
      hueColorFn: val => {
        return "hsl(" + val + ", 80%, 50%)";
      },
      lightColorFn: val => {
        return "hsl(" + this.colors.hsl.h + ",80% ," + val * 100 + "%)";
      },
      lightBgColorFn: val => {
        return {
          background:
            "linear-gradient(to right, hsl(" +
            this.colors.hsl.h +
            ", 80%, 0%) 0%, hsl(" +
            this.colors.hsl.h +
            ", 80%, 50%) 50%, hsl(" +
            this.colors.hsl.h +
            ", 80%, 100%) 100%)"
        };
      }
    };
  },
  methods: {
    changeMouseUp(v) {
      this.$emit("changemouseup", this.value);
    }
  }
};
</script>

<style lang="scss">
.hue .slider-wrap {
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

.fade-enter-active,
.fade-leave-active {
  transition: all 2s step-end;
}
.fade-enter,
.fade-leave-to {
  height: 0;
  opacity: 0;
}
</style>
