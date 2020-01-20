<div class="year-range-slider" id="{{$id}}">
   <year-slider 
      v-model="yearRange" 
      :min="yearMin" 
      :max="yearMax" 
      :step="1" 
      :show-min-max="false"
      @change="$emit('slide', $event)"
      @changemouseup="$emit('change', $event)">
   </year-slider>
</div>