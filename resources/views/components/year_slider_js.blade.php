<?php $jsId = Illuminate\Support\Str::camel($id) ?>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="{{ asset('js/vue/vue-color.min.js') }}"></script>
<script>
  var {{ $jsId }} = new Vue({
    el: '#{{$id}}-slider',
    components: {
      'year-slider': VueColor.Slider,
    },
    data: function() {
      return {
        yearRange: [{{ $yearRange ? $yearRange : $min . ',' . $max }}],
        yearMin: {{$min ? $min : 0}}, 
        yearMax: {{$max ? $max :  30}}
      }
    }
  });

  {{ $jsId }}.$on('change', function(range) {
      const val = range.join(',');
      $('#{{$id}}').prop( "disabled", val === "{{ $min }},{{ $max }}?");
      $('#{{$id}}').val(val);
      $('#{{$id}}').trigger('change');
  });
  {{ $jsId }}.$on('slide', function(range) {
      $('#{{$id}}-from').val(range[0]);
      $('#{{$id}}-to').val(range[1]);
  });

  $('#{{$id}}-from,#{{$id}}-to').on('change', function(event){
      const min = {{ $min }};
      const max = {{ $max }};
      const fy = +$('#{{$id}}-from').val().replace(/\D/g, '')
      const uy = +$('#{{$id}}-to').val().replace(/\D/g, '');
      const from = Math.min(Math.max(min, fy), max);
      const until = Math.max(Math.min(max, uy), min);
      const range = [from,until].sort();
      
      $('#{{$id}}').val(range.join(','));
      $('#{{$id}}').trigger('change');
      $('#{{$id}}-from').val(from);
      $('#{{$id}}-to').val(until) ;
      {{ $jsId }}.yearRange = range;
  });
</script>