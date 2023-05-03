@extends('layouts.admin')

@section('content')

{!! Form::model($notice, ['route' => ['notices.update', $notice->id], 'method' => 'patch']) !!}

<div class="col-md-12">
    <h1 class="page-header">Oznamy</h1>

    @if (Session::has('message'))
        <div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{!! Session::get('message') !!}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
        </div>
    @endif

    <label>Text</label>
    <ul class="nav nav-tabs" role="tablist">
        @foreach (config('translatable.locales') as $i => $locale)
            <li role="presentation" class="{{ ($i == 0) ? 'active' : '' }}">
                <a href="#{{ $locale }}" aria-controls="{{ $locale }}" role="tab" data-toggle="tab">{{ strtoupper($locale) }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach (config('translatable.locales') as $i => $locale)
            <div role="tabpanel" class="tab-pane  {{ ($i == 0) ? 'active' : '' }}" id="{{ $locale }}">
                <div class="form-group">
                    <textarea class="form-control wysiwyg" name="{{ $locale }}[content]" rows="8" >
                        {{ old($locale. "[content]", $notice->translateOrNew($locale)->content) }}
                    </textarea>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="col-md-5">
    <div class="form-group">
        <label for="alert_class">Typ</label>
        @foreach (['info', 'warning', 'danger'] as $class)
        <div class="radio">
            <label>
                <input type="radio" name="alert_class" value="{{ $class }}" @if(old('alert_class', $notice->alert_class) == $class)checked="checked"@endif>
                {{ $class }}
            </label>
        </div>
        @endforeach
    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <label>Ostatné</label>
        <input name="publish" type="hidden" value="0">
        <div class="checkbox">
            <label>
                <input name="publish" type="checkbox" value="1" @if(old('publish', $notice->publish))checked="checked"@endif> Publikovať
            </label>
        </div>
    </div>
</div>

<div class="col-md-12 tw-mt-5">
    <input class="btn btn-primary" type="submit" value="Uložiť">
    <p class="tw-mt-5">Posledná zmena: {{ $notice->updated_at }} ({{ $notice->updated_by }})</p>
</div>

{!! Form::close() !!}

@stop
