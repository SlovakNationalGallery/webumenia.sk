<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="js/vendor/vue-color.js"></script>
<script>
  var color = {
    hex: '#40BFBF',
    hsl: {
      h: 180,
      s: 0.5,
      l: 0.5,
      a: 1
    },
    a: 1
  }

  var colorPicker = new Vue({
    el: '#{{$id}}',
    components: {
      'slider-picker': VueColor.Slider,
    },
    data () {
      return {
        color
      }
    }
  })
</script>