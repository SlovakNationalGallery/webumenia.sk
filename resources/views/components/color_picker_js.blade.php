<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="js/touch-recognition.js"></script>
<script src="js/vendor/vue-color.min.js"></script>
<script>
@if ($color)
  var color = {
    hex: '{{"#" . $color}}',
    a: 1
  }
@else 
var color = {};
@endif


  var colorPicker = new Vue({
    el: '#{{$id}}',
    components: {
      'slider-picker': VueColor.Slider,
    },
    data () {
      return {
        color
      }
    },
    methods: {
      colorChanged: function ($col) {
        window.location.href = "{!! URL::to('katalog') !!}" + "?color=" + $col.hex.substr(1);
      }
    }
  })
</script>