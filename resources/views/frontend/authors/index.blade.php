@extends('layouts.master')

@section('title')
    @if ($title) {{ $title }} | @endif
    {{ trans('authority.authors') }} |
    @parent
@stop

@section('link')
    @include('includes.pagination_links', ['paginator' => $paginator])
@stop

@if ($untranslated)
    @section('untranslated')
        <section>
            <div class="container content-section">
                <div class="row">
                    @include('includes.message_untranslated')
                </div>
            </div>
        </section>
    @stop
@endif

@section('content')

    @formStart($form, ['attr' => ['class' => 'js-filter-form filter-form']])

    @include('frontend.authors.form')

    <section class="authors">
        <div class="container">
            <div class="row content-section">
                <div class="col-xs-6">
                    @if (!empty($search))
                        <h4 class="inline">{{ utrans('authority.authors_found') }} &bdquo;{!! $search !!}&ldquo; (<span data-searchd-total-hits>{{ $total }}</span>) </h4>
                    @else
                        <h4 class="inline">{{ formatNum($total) }} {{ trans('authority.authors_counted') }}</h4>
                    @endif
                    @if ($paginator->count() == 0)
                        <p class="text-center">{{ utrans('authority.authors_none') }}</p>
                    @endif
                    @if ($hasFilters)
                        <a class="btn btn-sm btn-default btn-outline  sans" href="{!! URL::to('autori')!!}">{{ trans('general.clear_filters') }} <i class="icon-cross"></i></a>
                    @endif
                </div>
                <div class="col-xs-6 text-right">
                    @formRow($form['sort_by'], ['attr' => ['class' => 'js-dropdown-select']])
                </div>
            </div>
            <div class="autori">
                @foreach ($paginator as $author)
                    <div class="row author">
                        <div class="col-sm-2 col-xs-4">
                            <a href="{!! $author->getUrl() !!}">
                                <img src="{!! $author->getImagePath() !!}" class="img-responsive img-circle" alt="{!! $author->name !!}">
                            </a>
                        </div>
                        <div class="col-sm-4 col-xs-8">
                            <div class="author-title">
                                <a href="{!! $author->getUrl() !!}" {!! (!empty($search))  ?
                        'data-searchd-result="title/'.$author->id.'" data-searchd-title="'. $author->formatedName.'"'
                        : '' !!}>
                                    <strong>{!! $author->formatedName !!}</strong>
                                </a>
                            </div>
                            <div>
                                {!! $author->birth_year !!} {!! $author->birth_place !!}
                                @if ($author->death_year)
                                &ndash; {!! $author->death_year !!} {!! $author->death_place !!}
                                @endif
                            </div>
                            <div>
                                @foreach ($author->roles as $i => $role)
                                    <a href="{!! url_to('autori', ['role' => $role]) !!}"><strong>{!! $role !!}</strong></a>{!! ($i+1 < count($author->roles) ? ', ' : '') !!}
                                @endforeach

                            </div>
                            <div>
                                {!! trans_choice('authority.artworks', $author->items_count, ['artworks_url' => url_to('katalog', ['author' => $author->name]), 'artworks_count' => $author->items_count]) !!}
                            </div>
                        </div>
                        <div class="clearfix visible-xs bottom-space"></div>
                        <div class="col-sm-6" >
                            @include('components.artwork_carousel', [
                                'slick_target' => "artworks-preview",
                                'items' => $author->previewItems,
                            ])
                        </div>
                    </div>
                @endforeach
                <div class="row">
                    <div class="col-sm-12 text-center">
                        {!! $paginator->appends(@Input::except('page'))->render() !!}
                    </div>
                </div>
            </div>{{-- autori --}}

        </div>
    </section>

    @formEnd($form, ['render_rest' => false])

@stop

@section('javascript')

    {!! Html::script('js/bootstrap-slider.min.js') !!}
    {!! Html::script('js/selectize.min.js') !!}

    {!! Html::script('js/slick.js') !!}
    {!! Html::script('js/components/artwork_carousel.js') !!}
    {!! Html::script('js/jquery.dropdown-select.js') !!}

    <script type="text/javascript">

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
                maxItems: 1,
                placeholder: $(this).attr('data-placeholder'),
                mode: 'multi',
                render: {
                    item: function(data, escape) {
                        return '<div class="item">'  + '<span class="color">'+this.settings.placeholder+': </span>' +  data.text.replace(/\(.*?\)/g, "") + '</div>';
                    }
                }
            });
        });

    </script>
@stop
