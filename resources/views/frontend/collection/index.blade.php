@extends('layouts.master')

@section('title')
{{ trans('kolekcie.title') }} |
@parent
@stop

@section('link')
@include('includes.pagination_links', ['paginator' => $collections])
@stop

@foreach ($collections as $i=>$collection)
@if ( ! $collection->hasTranslation(App::getLocale()) )
<section>
    <div class="container top-section">
        <div class="row">
            @include('includes.message_untranslated')
            @break
        </div>
    </div>
</section>
@endif
@endforeach

@section('content')

@formStart($form, ['attr' => ['class' => 'js-filter-form filter-form']])

@include('frontend.collection.form')

<section class="collections">
    <div class="container">
        <div class="row content-section">
            <div class="col-xs-6">
                @if (!empty($search))
                <h4 class="inline">{{ utrans('kolekcie.collections_found_collections') }} &bdquo;{!! $search !!}&ldquo;
                    (<span>{!! $collections->total() !!}</span>) </h4>
                @else
                <h4 class="inline">{!! $collections->total() !!} {{ trans('kolekcie.collections_collections') }}</h4>
                @endif
                @if ($collections->count() == 0)
                <p class="text-center">{{ utrans('kolekcie.collections_no_collections') }}</p>
                @endif
                {{--  @if (count(Input::all()) > 0)
                    <a class="btn btn-sm btn-default btn-outline  sans" href="{!! URL::to('kolekcie')!!}">zrušiť filtre <i class="icon-cross"></i></a>
                @endif --}}
            </div>
            {{-- <div class="col-xs-6 text-right">
                <div class="dropdown">
                  <a class="dropdown-toggle" type="button" id="dropdownSortBy" data-toggle="dropdown" aria-expanded="true">
                    {{ trans('general.sort_by') }} {!! trans(App\Collection::$sortable[$sort_by]) !!}
            <span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right dropdown-menu-sort" role="menu"
                aria-labelledby="dropdownSortBy">
                @foreach (App\Collection::$sortable as $sort=>$labelKey)
                @if ($sort != $sort_by)
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" rel="{!! $sort !!}">{!!
                        trans($labelKey) !!}</a></li>
                @endif
                @endforeach
            </ul>
        </div>
    </div> --}}
    <div class="col-xs-6 text-right">
        @formRow($form['sort_by'], ['attr' => ['class' => 'js-dropdown-select']])
    </div>
    </div>
    <div class="kolekcie">
        @foreach ($collections as $i=>$collection)
        <div class="row collection">
            {{-- <div class="col-sm-2 col-xs-4">
            	<a href="{!! $collection->getUrl() !!}">
            		<img src="{!! $collection->getHeaderImage() !!}" class="img-responsive pentagon" alt="{{ $collection->name }}">
            	</a>
            </div> --}}
            <div class="col-sm-6 col-xs-12">
                <div class="collection-title">
                    <a href="{!! $collection->getUrl() !!}" class="underline">
                        <strong>{!! $collection->name !!}</strong>
                    </a>
                </div>
                <div class="collection-meta grey">
                    {{--  {!! $collection->author !!} &nbsp;&middot;&nbsp; --}}
                    {!! $collection->created_at->format('d. m. Y') !!} &nbsp;&middot;&nbsp;
                    {!! $collection->user->name !!} &nbsp;&middot;&nbsp;
                    {!! $collection->items->count() !!} {{ trans('kolekcie.collections_artworks') }}
                </div>
                <div>
                    {!! $collection->getShortTextAttribute($collection->text, 350) !!}
                </div>
            </div>
            <div class="clearfix visible-xs bottom-space"></div>
            <div class="col-sm-6">
                @include('components.artwork_carousel', [
                'slick_target' => "artworks-preview",
                'slick_variant' => "small",
                'items' => $collection->getPreviewItems(),
                ])
            </div>
        </div>
        @endforeach
        <div class="row">
            <div class="col-sm-12 text-center">
                {!! $collections->appends(@Request::except('page'))->render() !!}
            </div>
        </div>
    </div>{{-- kolekcie --}}

    </div>
    </div>
</section>
@formEnd($form, ['render_rest' => false])


@stop

@section('javascript')

{!! Html::script('js/components/artwork_carousel.js') !!}
{!! Html::script('js/jquery.dropdown-select.js') !!}

<script type="text/javascript">
    $(document).ready(function(){

        $("form").submit(function(){
            $(this).find('input[name], select[name]').each(function(){
                if (!$(this).val()){
                    $(this).data('name', $(this).attr('name'));
                    $(this).removeAttr('name');
                }
            });
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
    });

</script>
@stop
