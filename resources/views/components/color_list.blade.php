<div class="colorlist">
  @foreach ($colors as $color)
    <div 
      class="colorlist-item" 
      style="background-color: {{$color["hex"]}}; width: {{$color["amount"]}}"
      title="{{$color["hex"]}}"></div>
  @endforeach
</div>