<div class="color-picker" id="{{$id}}" style="padding-top:25px;">
   <slider-picker v-model="color"  v-on:input="colorChanged($event)" v-bind:use-closest="true"/>
</div>