@extends('layouts.master')

@section('title')
{{ utrans('master.spaces') }} |
@parent
@stop

@section('content')
<section class="spaces py-5">
    @foreach ($spaces as $i=>$space)
    <div class="space">
        <a href="{!! $space->getUrl() !!}" class="space-title">
            {!! $space->name !!}
        </a>
    </div>
    @endforeach
    {{--
    <div class="row">
        <div class="col-sm-12 text-center">
            {!! $paginator->appends(@Input::except('page'))->render() !!}
        </div>
    </div>
        --}}
</section>
{{--
<section class="purpose py-5">
    <p>{{ trans('priestory.intention') }}</p>
    <p>
        {{ trans('priestory.plan', ['type' => (Request::is('teoretici')) ? 'teoretikov a teoretičky' : 'umelcov a umelkyne']) }}<br>
        {{ trans('priestory.subscribe') }}
    </p>

    <div class="col-12 col-md-6 border-0 px-0">
        @include('components.khb_newsletter_signup_form')
    </div>

</section>
 --}}
<div class="row border-top">
    @foreach (range('A', 'Z') as $char)
        <div class="col-3 col-sm-2 alphabet text-sans grid-cell-link {!! (Input::get('first-letter')==$char) ? 'active' : '' !!}">
            <a href="{!! url_to('vystavne-priestory', ['first-letter' => $char]) !!}" rel="{!! $char !!}"></a>
            <span>{!! $char !!}</span>
        </div>
    @endforeach
</div>


@stop

@section('javascript')


<script type="text/javascript">

</script>
@stop
