@extends('layouts.master') {{-- TODO figure out layout --}}

@section('title')
  Zdieľanie kolekcie {{-- TODO i18n --}}
  |
  @parent
@stop

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('frontend.shared-user-collections.store') }}">
  @csrf

  <label for="name">Názov</label>
  <input type="text" id="name" name="name" />

  <br />

  <label for="author">Autor</label>
  <input type="text" id="author" name="author" />

  <br />

  <label for="description">Popis</label>
  <textarea id="description" name="description"></textarea>

  <br />

  <ul>
    @foreach($items as $item)
    <li>
      {{ $item->title }}
      <input type="hidden" name="items[][id]" value="{{ $item->id }}" />
    </li>
    @endforeach
  </ul>
  
  <button type="submit" name="shared-user-collection-submit">Uložiť</button>
</form>

@endsection
