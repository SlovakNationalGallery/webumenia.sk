<ul class="colorlist {{ $class_names ?? '' }}">
  @foreach ($colors as $hex => $amount)
    <li 
      class="colorlist-item" 
      style="background-color: {{$hex}}; width: {{$amount * 100 / collect($colors)->sum()}}%"
      title="{{$hex}}">
      <a href="{!! URL::to('katalog?color=' . substr($hex, 1)) !!}"></a>
    </li>
  @endforeach
</ul>