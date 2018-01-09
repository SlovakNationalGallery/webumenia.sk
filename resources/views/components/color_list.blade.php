<ul class="colorlist">
  @foreach ($colors as $color)
    <li 
      class="colorlist-item" 
      style="background-color: {{$color["hex"]}}; width: {{$color["amount"]}}"
      title="{{$color["hex"]}}">
      <a href="{!! URL::to('katalog?color=' . substr($color["hex"], 1)) !!}"></a>
    </li>
  @endforeach
</ul>