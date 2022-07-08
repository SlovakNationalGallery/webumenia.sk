@extends('layouts.master')

@section('title')
    @if ($title) {{ $title }} | @endif
    {{ trans('katalog.title') }} |
    @parent
@stop

@section('link')
    @include('includes.pagination_links', ['paginator' => $paginator])
@stop

@section('content')

    @formStart($form, ['attr' => ['class' => 'js-filter-form filter-form']])

    @include('frontend.catalog.form')

    @if ($untranslated)
    <section class="catalog">
        <div class="container content-section">
            <div class="row">
                @include('includes.message_untranslated')
            </div>
        </div>
    </section>
    @endif

    <section class="catalog">
        <div class="container content-section">
            <div class="row content-section">
                <div class="col-xs-6">
                    @if (!empty($search))
                        <h4 class="inline">{{ utrans('katalog.catalog_found_artworks') }} &bdquo;{{ $search }}&ldquo; ({{ $total }}) </h4>
                    @else
                        <h4 class="inline">{{ formatNum($total) }} {{ trans_choice('katalog.catalog_artworks', $total) }} </h4>
                    @endif
                    @if ($paginator->count() == 0)
                        <p class="text-center">{{ utrans('katalog.catalog_no_artworks') }}</p>
                    @endif
                    @if ($hasFilters)
                        <a class="btn btn-sm btn-default btn-outline  sans" href="{{ URL::to('katalog') }}">{{ trans('general.clear_filters') }}  <i class="icon-cross"></i></a>
                    @endif
                    @if ($form['color']->vars['value'])
                        <a class="btn btn-sm btn-default btn-outline sans" href="{{ $urlWithoutColor }}" id="clear_color">{{ trans('general.clear_color') }} <span class="picked-color" style="background-color: {{ $form['color']->vars['value'] }};">&nbsp;</span> <i class="icon-cross"></i></a>
                    @endif
                </div>
                <div class="col-xs-6 text-right">
                    @formRow($form['sort_by'], ['attr' => ['class' => 'js-dropdown-select']])
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 isotope-wrapper">
                    <div id="iso">
                        @foreach ($paginator as $item)
                            @include('components.artwork_grid_item', [
                                'item' => $item,
                                'isotope_item_selector_class' => 'item',
                                'class_names' => 'col-md-3 col-sm-4 col-xs-6',
                            ])
                        @endforeach

                    </div>
                    <div class="col-sm-12 text-center">

                        {!! $paginator->appends(@Request::except('page'))->render() !!}
                        @include('components.load_more', ['paginator' => $paginator, 'isotopeContainerSelector' => '#iso'])
                    </div>
                </div>

            </div>
        </div>
    </section>

    @formEnd($form, ['render_rest' => false])


@stop

@section('javascript')

    {!! Html::script('js/bootstrap-slider.min.js') !!}
    {!! Html::script('js/readmore.min.js') !!}
    {!! Html::script('js/jquery.dropdown-select.js') !!}
    <script src="{!! asset_timed('js/scroll-frame.js') !!}"></script>

    <script type="text/javascript">

        // start with isotype even before document is ready
        $('.isotope-wrapper').each(function(){
            var $container = $('#iso', this);
            spravGrid($container);
        });

        $(window).resize(function() {
            spravGrid($container);
        });

        $(document).ready(function(){

            $("form").submit(function()
            {
                $(this).find('input[name], select[name]').each(function(){
                    if (!$(this).val()){
                        $(this).data('name', $(this).attr('name'));
                        $(this).removeAttr('name');
                    }
                });
            });

            $('#years-range').on('change', function(event) {
                $(this).closest('form').submit();
            });

            $('.js-filter-form').each(function () {
                var $filterForm = $(this);
                $filterForm.find('select, input:not([type=hidden])').change(function () {
                    $filterForm.submit();
                });
            });

            $('.js-dropdown-select').dropdownSelect();

            $(".js-custom-select").selectize({
                plugins: ['remove_button'],
                // maxItems: 2,
                maxItems: 1,
                placeholder: $(this).attr('data-placeholder'),
                mode: 'multi',
                render: {
                    item: function(data, escape) {
                        return '<div class="selected-item">'  + '<span class="color">'+this.settings.placeholder+': </span>' +  data.text.replace(/\(.*?\)/g, "") + '</div>';
                    }
                }
            });

            $(".js-color-clear").click(function(e) {
                e.preventDefault();
                $(this).closest('label').find('input').val('');
                $(this).closest('form').submit();
            });

            // fix artwork detail on iOS https://github.com/artsy/scroll-frame/issues/30
            if (!isMobileSafari() && !isIE()) {
                scrollFrame('.item a');
            }
        });

    </script>
@stop
