<div class="range-slider">
    <div class="col-xs-6 col-sm-1 text-left text-sm-right">
        <input class="sans range-slider-from" id="{{ $name }}-from" maxlength="5" pattern="[-]?[0-9]{1,4}" step="5"
               value="{{ $from }}" />
    </div>
    <div class="col-xs-6 col-sm-1 col-sm-push-10 text-right text-sm-left">
        <input class="sans range-slider-to" id="{{ $name }}-to" maxlength="5" pattern="[-]?[0-9]{1,4}" step="5"
               value="{{ $to }}" />
    </div>
    <div class="col-xs-12 col-sm-10 col-sm-pull-1">

        @include('components.year_slider', ['id' => $name])
        @include('components.year_slider_js', [
        'yearRange' => $from . ', ' . $to,
        'min' => $min,
        'max' => $max,
        'id' => ($name)
        ])
    </div>
</div>