<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="js/touch-recognition.js"></script>
<script src="js/vue/vue-color.min.js"></script>
<script>
@if ($color)
  var color = {
    hex: '{{"#" . $color}}',
    a: 1
  }
@else 
  var color = {};
@endif


  var {{$id}} = new Vue({
    el: '#{{$id}}',
    components: {
      'slider-picker': VueColor.ColorSlider,
    },
    data () {
      return {
        color
      }
    },
  })
</script>