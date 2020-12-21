<div class="color-picker" id="{{ $id }}">
    <input type="hidden" name="{{ $name }}" v-bind:value="colorSubstring" />
    <color-slider v-model="color" @changemouseup="onChange" />
</div>

@section('javascript')
@parent
<script src="{{ asset('/js/touch-recognition.js') }}"></script>
<script >
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
@stop