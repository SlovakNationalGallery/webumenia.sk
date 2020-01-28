@php FormRenderer::setTheme($form, 'filter') @endphp

<section class="filters">
    <div class="container content-section">
        <div class="expandable">
            <div class="row">
                <div class="col-md-4 col-xs-6 bottom-space">
                    @formWidget($form['author'], ['attr' => ['class' => 'js-custom-select']])
                </div>
                <div class="col-md-4 col-xs-6 bottom-space">
                    @formWidget($form['work_type'], ['attr' => ['class' => 'js-custom-select']])
                </div>
                <div class="col-md-4 col-xs-6 bottom-space">
                    @formWidget($form['tag'], ['attr' => ['class' => 'js-custom-select']])
                </div>
                <div class="col-md-4 col-xs-6 bottom-space">
                    @formWidget($form['gallery'], ['attr' => ['class' => 'js-custom-select']])
                </div>
                <div class="col-md-4 col-xs-6 bottom-space">
                    @formWidget($form['topic'], ['attr' => ['class' => 'js-custom-select']])
                </div>
                <div class="col-md-4 col-xs-6 bottom-space">
                    @formWidget($form['technique'], ['attr' => ['class' => 'js-custom-select']])
                </div>
                <div class="col-md-4 col-xs-6">
                    <div class="checkbox">
                        @formRow($form['has_image'])
                    </div>
                </div>
                <div class="col-md-4 col-xs-6">
                    <div class="checkbox">
                        @formRow($form['has_iip'])
                    </div>
                </div>
                <div class="col-md-4 col-xs-6">
                    <div class="checkbox">
                        @formRow($form['is_free'])
                    </div>
                </div>
            </div>
            <div class="row">
                @formWidget($form['years-range'])
            </div>

{{--            @if ($color)--}}
{{--                <div class="row">--}}
{{--                    <div class="col-sm-12">--}}
{{--                        <label for="color_filter" class="w-100 mt-3 mb-0 light">--}}
{{--                            {{ utrans('katalog.filters_color') }}:--}}
{{--                            @include('components.color_list', ['colors' => [['hex' => $color, 'amount' => '100%']], 'include_clear' => true, 'id' => 'color-filter', 'class_names' => 'mt-2 mb-0'])--}}
{{--                            {!! Form::hidden('color', @$input['color'], ['id'=>'color']) !!}--}}
{{--                        </label>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}
        </div>
    </div>
</section>