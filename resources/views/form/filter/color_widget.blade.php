<div class="color-picker" id="{{ $id }}">
    <input type="hidden" name="{{ $name }}" v-bind:value="colorSubstring" />
    <slider-picker v-model="color" @changemouseup="onChange" />
</div>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="/js/touch-recognition.js"></script>
<script src="/js/vue/vue-color.min.js"></script>
<script>
@if ($color)
  var color = {
    hex: '{{ $color }}',
    a: 1
  }
@else
  var color = {};
@endif

new Vue({
  el: '#{{ $id }}',
  components: {
    'slider-picker': VueColor.ColorSlider,
  },
  computed: {
    colorSubstring() {
      if (this.color && this.color.hex) return this.color.hex.substring(1)
    }
  },
  methods: {
    onChange(color) {
      $('#{{ $id }}').parents('form').submit();
    }
  },
  data() {
    return {
      color
    }
  }
})
</script>
