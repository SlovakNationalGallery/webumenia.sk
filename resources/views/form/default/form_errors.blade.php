@if (count($errors) > 0)
<ul>
    @foreach ($errors as $error)
    <li>{{ $error->getMessage() }}</li>
    @endforeach
</ul>
@endif