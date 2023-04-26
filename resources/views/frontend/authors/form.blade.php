@php FormRenderer::setTheme($form, 'filter') @endphp

<section class="filters">
    <div class="container content-section">
        <div class="row">
            <div  class="col-md-3 col-xs-6 bottom-space">
                @formWidget($form['role'], ['attr' => ['class' => 'js-custom-select']])
            </div>
            <div  class="col-md-3 col-xs-6 bottom-space">
                @formWidget($form['nationality'], ['attr' => ['class' => 'js-custom-select']])
            </div>
            <div  class="col-md-3 col-xs-6 bottom-space">
                @formWidget($form['place'], ['attr' => ['class' => 'js-custom-select']])
            </div>
            <div  class="col-md-3 col-xs-6 bottom-space">
                @formWidget($form['sex'], ['attr' => ['class' => 'js-custom-select']])
            </div>
        </div>
        <div class="row">
            @formWidget($form['years-range'])
        </div>
        <div class="row" style="padding-top: 20px;">
            <div  class="col-sm-12 tw-text-center alphabet sans">
                @formWidget($form['first_letter'])
            </div>
        </div>
    </div>
</section>
