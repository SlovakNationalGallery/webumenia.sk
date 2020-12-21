<div class="range-slider">
    <div class="col-xs-6 col-sm-1 text-left text-sm-right">
        <input class="sans range-slider-from" id="{{ $name }}-from" maxlength="5" pattern="[-]?[0-9]{1,4}" step="5"
               value="{{ $from }}" />
    </div>
    <div class="col-xs-6 col-sm-1 col-sm-push-10 text-right text-sm-left">
        <input class="sans range-slider-to" id="{{ $name }}-to" maxlength="5" pattern="[-]?[0-9]{1,4}" step="5"
               value="{{ $to }}" />
    </div>
    <div class="col-xs-12 col-sm-10 col-sm-pull-1">
        <div class="year-range-slider" id="{{$name}}-slider">
            <slider
               v-model="yearRange"
               :min="yearMin"
               :max="yearMax"
               :step="1"
               :show-min-max="false"
               @change="$emit('slide', $event)"
               @changemouseup="$emit('change', $event)">
            </slider>
            <input id="{{$name}}" name="{{$name}}" type="hidden" value="{{ $from . ', ' . $to }}"/>
         </div>
    </div>
</div>

@section('javascript')
@parent

@php
    $jsId = Str::camel($name);
    $yearRange = $from . ', ' . $to;
@endphp

<script>
  var {{ $jsId }} = new Vue({
    el: '#{{$name}}-slider',
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
      $('#{{$name}}').prop( "disabled", val === "{{ $min }},{{ $max }}?");
      $('#{{$name}}').val(val);
      $('#{{$name}}').trigger('change');
  });
  {{ $jsId }}.$on('slide', function(range) {
      $('#{{$name}}-from').val(range[0]);
      $('#{{$name}}-to').val(range[1]);
  });

  $('#{{$name}}-from,#{{$name}}-to').on('change', function(event){
      const min = {{ $min }};
      const max = {{ $max }};
      const fy = +$('#{{$name}}-from').val().replace(/[^-0-9]/g, '')
      const uy = +$('#{{$name}}-to').val().replace(/[^-0-9]/g, '');
      const from = Math.min(Math.max(min, fy), max);
      const until = Math.max(Math.min(max, uy), min);
      const range = [from,until].sort();

      $('#{{$name}}').val(range.join(','));
      $('#{{$name}}').trigger('change');
      $('#{{$name}}-from').val(from);
      $('#{{$name}}-to').val(until) ;
      {{ $jsId }}.yearRange = range;
  });
</script>
@endsection
