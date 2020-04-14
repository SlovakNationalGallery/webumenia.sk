<div class="year-range-slider" id="{{$id}}-slider">
   <year-slider 
      v-model="yearRange" 
      :min="yearMin" 
      :max="yearMax" 
      :step="1" 
      :show-min-max="false"
      @change="$emit('slide', $event)"
      @changemouseup="$emit('change', $event)">
   </year-slider>
   <input id="{{$id}}" name="{{$id}}" type="hidden" value="{{ $yearRange }}"/>
</div>