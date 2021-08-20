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
  <input type="text" id="name" name="name" value="nejaký názov" />

  <br />

  <label for="author">Autor</label>
  <input type="text" id="author" name="author" value="nejaký autor" />

  <br />

  <label for="description">Popis</label>
  <textarea id="description" name="description">nejaky popis</textarea>

  <br />

  <ul>
    @foreach($items as $item)
    <li>
      {{ $item->id }}
      <input type="hidden" name="items[][id]" value="{{ $item->id }}" />
    </li>
    @endforeach
  </ul>
  
  <button type="submit">Uložiť</button>
</form>

@endsection
