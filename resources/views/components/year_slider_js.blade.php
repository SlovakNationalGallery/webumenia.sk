<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="{{ asset('js/vue/vue-color.min.js') }}"></script>
<script>
  var {{$id}} = new Vue({
    el: '#{{$id}}',
    components: {
      'year-slider': VueColor.Slider,
    },
    data () {
      return {
        yearRange: [{{ $yearRange ? $yearRange : $min . ',' . $max }}],
        yearMin: {{$min ? $min : 0}}, 
        yearMax: {{$max ? $max :  30}}
      }
    }
  })
</script>