@extends('layouts.admin')

@section('content')

@php FormRenderer::setTheme($form, 'item') @endphp
@formStart($form)

@formErrors($form)

<div class="row">
	@if (isset($form['id']))
	<div class="col-md-12">
		@formRow($form['id'])
	</div>
	@endif

	<div class="col-md-6">

		<ul class="nav nav-tabs top-space" role="tablist">
			@foreach (config('translatable.locales') as $locale)
				<li role="presentation" class="{{ ($locale == app()->getLocale()) ? 'active' : '' }}">
					<a href="#{{ $locale }}" aria-controls="{{ $locale }}" role="tab" data-toggle="tab">{{ strtoupper($locale) }}</a>
				</li>
			@endforeach
		</ul>

		<div class="tab-content top-space">
		@php FormRenderer::setTheme($form['translations'], 'item_translation') @endphp
		@foreach ($form['translations'] as $translation)
				<div role="tabpanel" class="tab-pane {{ ($translation->vars['value']->locale == app()->getLocale()) ? 'active' : '' }}" id="{{ $translation->vars['value']->locale }}">
					@formWidget($translation)
				</div>
			@endforeach
		</div>
	</div>
	<div class="col-md-6">
		<ul class="nav nav-tabs top-space" role="tablist">
			<li role="presentation" class="active"><a>non-translatable attributes</a></li>
		</ul>

		<div class="tab-content top-space">
			<div role="tabpanel" class="tab-pane active">
				<div class="row">
					<div class="col-md-12">
						@formRow($form['description_user_id'])
					</div>
					<div class="col-md-12">
						@formRow($form['identifier'])
					</div>
					<div class="col-md-12">
						@formRow($form['author'])
					</div>
					<div class="col-md-12">
						@formRow($form['tags'])
					</div>
					<div class="col-md-12">
						@formRow($form['date_earliest'])
					</div>
					<div class="col-md-12">
						@formRow($form['date_latest'])
					</div>
					<div class="col-md-6">
						@formRow($form['lat'])
					</div>
					<div class="col-md-6">
						@formRow($form['lng'])
					</div>
					<div class="col-md-6">
						@formRow($form['related_work_order'])
					</div>
					<div class="col-md-6">
						@formRow($form['related_work_total'])
					</div>
					<div class="col-md-6">
						@formRow($form['acquisition_date'])
					</div>
					<div class="col-md-12">
						@if ($item->getImagePath())
							<div class="primary-image">
								aktuálny:<br>
								<img src="{{ $item->getImagePath() }}" class="img-responsive">
							</div>
						@endif

						@formRow($form['primary_image'])
					</div>
					<div class="col-md-12">
						@formRow($form['images'], ['attr' => ['class' => 'js-form-collection']])
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-md-12 text-center">
	@formWidget($form['save'], ['attr' => ['class' => 'btn btn-default']])
	@if(isset($item) && $item->record)
		<a href="{!!URL::to('harvests/'.$item->record->id.'/refreshRecord')!!}" class="btn btn-warning">Obnoviť z OAI</a>
		&nbsp;
	@endif
	{!! link_to_route('item.index', 'Zrušiť', null, array('class' => 'btn btn-default')) !!}
</div>

@formEnd($form)

@stop

@section('script')

{!! Html::script('js/selectize.min.js') !!}

<script>
$(document).ready(function(){

	$("#item_tags").selectize({
		plugins: ['remove_button'],
		persist: false,
		create: true,
		createOnBlur: true
	});

});

</script>
@stop
