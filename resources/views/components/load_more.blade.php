@if ($paginator->hasMorePages() )
<a id="next" href="{{ $paginator->nextPageUrl() }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
        <path d="M0.492,8.459v83.427c4.124,0.212,7.409,3.497,7.622,7.622h83.357c0.22-4.265,3.719-7.664,8.036-7.664V8.571c-4.46,0-8.079-3.617-8.079-8.079H8.157C8.157,4.774,4.755,8.239,0.492,8.459z"/>
        <text text-anchor="middle" alignment-baseline="middle" x="50" y="50">
        {{ trans('katalog.catalog_show_more') }}
        </text>
    </svg>
</a>
<div class="page-load-status" style="display: none">
    <p class="infinite-scroll-request" id="infscr-loading"><i class="fa fa-refresh fa-spin fa-lg"></i></p>
    <p class="infinite-scroll-last text-muted">{{ utrans('katalog.catalog_finished') }}</p>
    <p class="infinite-scroll-error">{{ utrans('katalog.catalog_finished') }}</p>
</div>
@endif

@section('javascript')
@parent
<script>
    $(document).ready(function(){
        var $container = $('{{ $isotopeContainerSelector }}');

        $container.infiniteScroll({
            path: '#next',
            append: '.item',
            outlayer: $container.data('isotope'),
            loadOnScroll: false, // Start with scroll disabled
            status: '.page-load-status'
        });

        $container.on('request.infiniteScroll', function( event, path ) {
            $('.infinite-scroll-request').addClass('animated fadeInUp faster');
        });

        $('#next').on('click', function(event) {
            $container.infiniteScroll('loadNextPage');
            $container.infiniteScroll('option', {
                loadOnScroll: true,
            });

            $(this).fadeOut();
            event.preventDefault();
        });
    });
</script>
@endsection
