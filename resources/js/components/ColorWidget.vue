<template>
  <div class="color-picker" :id="htmlId">
    <input type="hidden" :name="htmlName" :value="color.hex.substring(1)" />
    <color-slider v-model="color" @changemouseup="onChange" />
  </div>
</template>

<script>
// TODO figure out why this is necessary
window.addEventListener('touchstart', function onFirstTouch() {
    document.body.classList.add('user-using-touch');
    window.removeEventListener('touchstart', onFirstTouch, false);
}, false);

export default {
  props: ["htmlId", "htmlName", "selectedColor"],
  methods: {
    onChange() {
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
