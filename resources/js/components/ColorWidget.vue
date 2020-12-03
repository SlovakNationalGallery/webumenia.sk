<template>
  <div class="color-picker" :id="htmlId">
    <input type="hidden" :name="htmlName" :value="color.hex.substring(1)" />
    <slider-picker v-model="color" @changemouseup="onChange" />
  </div>
</template>

<script>
// TODO use npm package instead
const VueColor = require("./vendor-customized/vue-color.min")

// TODO figure out why this is necessary
window.addEventListener('touchstart', function onFirstTouch() {
    document.body.classList.add('user-using-touch');
    window.removeEventListener('touchstart', onFirstTouch, false);
}, false);

export default {
  components: {
    "slider-picker": VueColor.ColorSlider,
  },
  props: ["htmlId", "htmlName", "selectedColor"],
  methods: {
    onChange() {
      console.log($("#" + this.id).parents("form"))
      $("#" + this.htmlId).parents("form").submit()
    },
  },
  data() {
    return {
      color: {
        hex: this.selectedColor,
      },
    }
  },
}
</script>
