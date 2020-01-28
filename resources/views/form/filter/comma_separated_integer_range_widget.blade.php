<div class="js-range-slider range-slider">
    <div class="col-xs-6 col-sm-1 text-left text-sm-right">
        <span class="sans js-range-slider-from">{{ $from }}</span>
    </div>
    <div class="col-xs-6 col-sm-1 col-sm-push-10 text-right text-sm-left">
        <span class="sans js-range-slider-to">{{ $to }}</span>
    </div>
    <div class="col-sm-10 col-sm-pull-1">
        <input name="{{ $name }}"
               type="hidden"
               data-slider-step="5"
               data-slider-min="{{ $min }}"
               data-slider-max="{{ $max }}"
               data-slider-value="[{{ $from }},{{ $to }}]"
        />
    </div>
</div>