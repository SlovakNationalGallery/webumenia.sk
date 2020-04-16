@php FormRenderer::setTheme($form, 'filter') @endphp

<section class="filters">
    <div class="container content-section">
        <div class="expandable">
            <div class="row">
                <div class="col-md-push-2 col-md-4 col-xs-6 bottom-space">
                    @formWidget($form['author'], ['attr' => ['class' => 'js-custom-select']])
                </div>
                <div class="col-md-push-2 col-md-4 col-xs-6 bottom-space">
                    @formWidget($form['type'], ['attr' => ['class' => 'js-custom-select']])
                </div>
            </div>
        </div>
    </div>
</section>
