<template>
  <div class="slider" v-bind:class="{ active: showButton }">
    <div
      class="slider-wrap"
      role="slider"
      :aria-valuenow="value"
      :aria-valuemin="min"
      :aria-valuemax="max"
      ref="container"
      :style="bgColorFn && bgColorFn(value)"
      @mousedown.left="handleMouseDown"
      @touchmove="handleChange"
      @touchstart="handleChange"
      @touchend="handleMouseUp"
    >
      <template v-for="(val, index) in values">
        <div
          v-if="index > 0"
          class="slider-period"
          :class="`period-${index - 1}_${index}`"
          :style="{
            left: pointerLeft(values[index - 1]),
            right: `calc(100% - ${pointerLeft(val)}`,
          }"
        ></div>

        <div
          class="pointer"
          :class="{ moving: movingIndex === index }"
          :style="{ left: pointerLeft(val) }"
          v-if="showButton"
          role="presentation"
        >
          <template v-if="pointerColorFn">
            <div
              class="picker-touch"
              :style="{ backgroundColor: pointerColorFn(val) }"
              :class="{ active: mouseDown }"
            ></div>
            <div
              class="picker"
              :style="{ backgroundColor: pointerColorFn(val) }"
              @mousedown="setMoving(index)"
            ></div>
          </template>
          <template v-else>
            <div class="picker-touch" :class="{ active: mouseDown }"></div>
            <div class="picker" @mousedown="setMoving(index)"></div>
          </template>
        </div>
      </template>
    </div>
    <div v-if="showMinMax" class="slider-legend">
      <span>{{ min }}</span>
      <span>{{ max }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: "slider",
  props: {
    value: Number | Array,
    min: { type: Number, default: 0 },
    max: { type: Number, default: 100 },
    step: { type: Number, default: 1 },
    startEmpty: { type: Boolean, default: true },
    showMinMax: { type: Boolean, default: false },
    pointerColorFn: { type: Function },
    bgColorFn: { type: Function }
  },
  data() {
    return {
      showButton: false || !this.startEmpty || this.value,
      mouseDown: false,
      range: this.max - this.min,
      values: Array.isArray(this.value) ? this.value : [this.value],
      movingIndex: -1
    };
  },
  watch: {
    value(v) {
      this.values = Array.isArray(v) ? v : [v];
    }
  },
  methods: {
    pointerLeft(val) {
      if (isNaN(val)) return "0%";
      return ((val - this.min) * 100) / this.range + "%";
    },
    handleChange(e, skip) {
      if (!skip) {
      }
      !skip && e.preventDefault();

      var container = this.$refs.container;
      var containerWidth = container.clientWidth;

      var xOffset = container.getBoundingClientRect().left + window.pageXOffset;
      var pageX = e.pageX || (e.touches ? e.touches[0].pageX : 0);
      var left = pageX - xOffset;

      var v;
      var percent;

      if (left < 0) {
        v = this.min;
      } else if (left > containerWidth) {
        v = this.max;
      } else {
        percent = (left * 100) / containerWidth;
        v = (this.range * percent) / 100 + this.min;
      }

      if (this.movingIndex < 0) {
        this.movingIndex = this.getClosest(v);
      }
      let min =
        this.movingIndex > 0
          ? this.values[this.movingIndex - 1] + this.step
          : this.min;
      let max =
        this.movingIndex < this.values.length - 1
          ? this.values[this.movingIndex + 1] - this.step
          : this.max;

      const remainder = v % this.step;
      if (remainder) {
        v = v - remainder;
        if (remainder > this.step * 0.5) {
          v += this.step;
        }
      }

      if (v > max) v = max;
      if (v < min) v = min;

      if (this.values[this.movingIndex] !== v) {
        this.values[this.movingIndex] = v;
        this.values = [...this.values];
        if (typeof this.value === "number") {
          // this.value = v
          this.emitValue("change", v);
        } else {
          // this.value = this.values
          this.emitValue("change", this.values);
        }
      }
    },
    setMoving(index) {
      this.movingIndex = index;
    },
    getClosest(val) {
      let dist = this.range;
      let resIndex = 0;
      this.values.forEach((element, index) => {
        const actDist = Math.abs(element - val);
        if (actDist <= dist) {
          resIndex = index;
          dist = actDist;
        }
      });
      return resIndex;
    },
    handleMouseDown(e) {
      this.showButton = true;
      this.mouseDown = true;
      this.handleChange(e, false);
      window.addEventListener("mousemove", this.handleChange);
      window.addEventListener("mouseup", this.handleMouseUp);
      window.addEventListener("mouseout", this.handleMouseOut);
    },
    handleMouseUp(e) {
      this.mouseDown = false;
      this.unbindEventListeners();
      if (typeof this.value === "number") {
        this.emitValue("changemouseup", this.values[0]);
      } else {
        this.emitValue("changemouseup", this.values);
      }
    },
    handleMouseOut(e) {
      if (e.target.tagName === "HTML") {
        this.unbindEventListeners();
      }
    },
    unbindEventListeners() {
      this.movingIndex = -1;
      window.removeEventListener("mousemove", this.handleChange);
      window.removeEventListener("mouseup", this.handleMouseUp);
      window.addEventListener("mouseout", this.handleMouseOut);
    },
    emitValue(eventName, val) {
      this.$emit(eventName, val);
    }
  }
};
</script>

<style lang="scss">
.slider {
  width: 100%;
  min-width: 100px;
  position: relative;
  display: block;
  overflow: visible;
  .active,
  &:hover {
    opacity: 1;
  }
}

.slider-wrap {
  background: #ccc;
  height: 8px;
  margin: 20px -4px;
  cursor: pointer;
  border-radius: 4px;
}

.pointer {
  z-index: 2;
  position: absolute;
}
.picker,
.picker-touch {
  border-radius: 16px;
  border: 2px solid #5b5d5c;
  position: absolute;
  box-sizing: content-box;
}
.picker {
  width: 12px;
  height: 12px;
  transform: translate(-9px, -4px);
}

.picker-touch {
  pointer-events: none;
  width: 26px;
  height: 26px;
  transform: translate(-15px, -55px);
  opacity: 0;
}

.moving .user-using-touch .moving .picker-touch.active {
  opacity: 1;
}

.moving {
  z-index: 100;
}

.slider-period {
  position: absolute;
  background: #555;
  height: 8px;
  border-radius: 4px;
}

.slider-legend {
  position: relative;
  width: 100%;
  top: -8px;
  font-size: 0.8em;
  height: 1px;
  text-align: center;
  color: #555;
  span:before {
    content: "";
    position: absolute;
    top: -0.3em;
    left: 50%;
    height: 0.3em;
    border-left: solid 1px #555;
  }
  span:first-child {
    position: absolute;
    left: 0;
    transform: translateX(-50%);
  }
  span:last-child {
    position: absolute;
    right: 0;
    transform: translateX(50%);
  }
}
</style>
