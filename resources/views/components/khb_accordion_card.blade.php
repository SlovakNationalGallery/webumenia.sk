<div class="card">
<div class="card-header" id="heading{{ studly_case($name) }}">
    <h5 class="mb-0">
      <button class="btn btn-link font-weight-bold p-0" type="button" data-toggle="collapse" data-target="#collapse{{ studly_case($name) }}" aria-expanded="true" aria-controls="collapse{{ studly_case($name) }}">
        {{ trans('autor.'.$name) }}
      </button>
    </h5>
  </div>

<div id="collapse{{ studly_case($name) }}" class="collapse {{ ($show) ? 'show' : ''}}" aria-labelledby="heading{{ studly_case($name) }}" data-parent="#{{ $parrentId }}">
    <div class="card-body pt-0">
      {!!  $content !!}
    </div>
  </div>
</div>