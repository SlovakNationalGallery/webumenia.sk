<div class="container-fluid {!! isSet($class)? $class:'' !!} share-buttons">
    <a href='https://www.facebook.com/sharer/sharer.php?u={{ $url }}'
       target='_blank' class="no-border" data-toggle="tooltip" title="{{ trans('general.share_facebook') }}">
        <i class='fa fa-facebook fa-lg'></i>
    </a>

    <a href='https://twitter.com/intent/tweet?text={{ $title }}&url={{ $url }}'
       target='_blank' class="no-border" data-toggle="tooltip" title='{{ trans('general.share_twitter') }}'>
        <i class='fa fa-twitter fa-lg'></i>
    </a>

    <a href='//www.pinterest.com/pin/create/button/?url={{ $url }}&media={{ $img }}' class='pin-it-button no-border'
       count-layout='none' target='_blank' data-toggle="tooltip" title="{{ trans('general.share_pinterest') }}">
        <i class='fa fa-pinterest fa-lg'></i>
    </a>
    <a href='mailto:?subject={{ $title }}, {{ trans('informacie.galleries.SNG') }}&body={{ $url }}'
       style="font-size:0.9em" target='_blank' class="no-border" data-toggle="tooltip"
       title="{{ trans('general.share_mail') }}">
        <i class='fa fa-envelope fa-lg'></i>
    </a>
    <span data-toggle="tooltip" title="{{ trans('general.copy_url') }}">
    <a href="#shareLink" style='cursor:pointer' data-toggle="modal" class="no-border" data-target="#shareLink">
        <i class='fa fa-link fa-lg'></i>
    </a>
    </span>

    @isset($citation)
    <button
        class="btn btn-outline no-border"
        data-toggle="tooltip"
        data-trigger="hover"
        title="{{ trans('general.copy_citation') }}"
        data-success-title="{{ trans('general.citation_copied') }}"
        data-clipboard-text="{{ $citation }}"
    >
        <i class="fa fa-quote-left"></i>
    </button>
    @endisset

</div>

<!-- Modal -->
<div tabindex="-1" class="modal fade" id="shareLink" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                {{ trans('general.share_link') }}
            </div>
            <div class="modal-body text-left">
                <code>{{ $url }}</code>
                <div class="tw-float-right">
                @include('components.copy_button', ['text' => $url])
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center"><button type="button" data-dismiss="modal"
                        class="btn btn-default btn-outline tw-uppercase sans">{{ trans('general.close') }}</button></div>
            </div>
        </div>
    </div>
</div>
<!-- /Modal -->
