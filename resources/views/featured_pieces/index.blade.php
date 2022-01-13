@extends('layouts.admin')

@section('title')
@parent
- Odporúčaný obsah
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="page-header">Odporúčaný obsah</h1>

        @if (session('message'))
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('message') }}
            </div>
        @endif

    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="{{ route('featured-pieces.create') }}" class="btn btn-primary btn-outline">
                    <i class="fa fa-plus"></i> Vytvoriť
                </a>
            </div>
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Náhľad</th>
                            <th>Nadpis</th>
                            <th>Vytvorený</th>
                            <th>Publikovaný</th>
                            <th class="text-right">Počet kliknutí</th>
                            <th>Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($featuredPieces as $piece)
                        <tr>
                            <td>{{ $piece->id }}</td>
                            <td style="max-width: 50px">
                                @if($piece->hasMedia('image'))
                                {{ $piece->getFirstMedia('image')->img()->attributes(['width' => '100%', 'height' => 'auto']) }}
                                @endif
                            <td>
                                <strong>{{ $piece->title }}</strong><br>
                                {{ $piece->excerpt }}
                            </td>
                            <td>
                                @datetime($piece->created_at)
                            </td>
                            <td>@if ($piece->publish) <i class="fa fa-check text-success"></i> @endif</td>
                            <td class="text-right">
                                {{ $piece->click_count }}
                            </td>
                            <td>
                                <a href="{{ route('featured-pieces.edit', $piece) }}" class="btn btn-primary btn-xs btn-outline">Upraviť</a>
                                <x-admin.link-with-confirmation
                                    action="{{ route('featured-pieces.destroy', $piece->id) }}"
                                    method="DELETE"
                                    class="btn btn-danger btn-xs btn-outline"
                                    message="Naozaj to chceš zmazať?"
                                >
                                    Zmazať
                                </x-admin.link-with-confirmation>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection
