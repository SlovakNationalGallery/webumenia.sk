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

            <div class="row">
                <div class="col-sm-12 col-md-10 col-md-push-1">
                    @formWidget($form['color'])
                </div>
            </div>
        </div>
    </div>
</section>
