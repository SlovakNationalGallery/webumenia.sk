<div class="color-picker" id="{{$id}}">
   <slider-picker v-model="color"  @changemouseup="$emit('change',$event)" />
</div>