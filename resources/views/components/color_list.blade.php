<div class="colorlist">
  @foreach ($colors as $color)
    <div 
      class="colorlist-item" 
      style="background-color: {{$color["hex"]}}; width: calc({{$color["amount"]}} - 4px)"
      title="{{$color["hex"]}}"></div>
  @endforeach
</div>