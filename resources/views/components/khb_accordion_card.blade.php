<div class="card">
  <div class="card-header" id="heading{{ studly_case($title) }}">
    <h5 class="mb-0">
      <button class="btn btn-link font-weight-bold p-0" type="button" data-toggle="collapse" data-target="#collapse{{ studly_case($title) }}" aria-expanded="true" aria-controls="collapse{{ studly_case($title) }}">
        {{ trans('autor.'.$title) }}
      </button>
    </h5>
  </div>

  <div id="collapse{{ studly_case($title) }}" class="collapse {{ ($show) ? 'show' : ''}}" aria-labelledby="heading{{ studly_case($title) }}" data-parent="#{{ $parrentId }}">
    <div class="card-body pt-0">
      {!!  $content !!}
    </div>
  </div>
</div>