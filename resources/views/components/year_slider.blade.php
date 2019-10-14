<div class="year-range-slider" id="{{$id}}">
   <year-slider 
      v-model="yearRange" 
      :min="yearMin" 
      :max="yearMax" 
      :step="5" 
      :show-min-max="true"
      @change="$emit('slide', $event)"
      @changemouseup="$emit('change', $event)">
   </year-slider>
</div>