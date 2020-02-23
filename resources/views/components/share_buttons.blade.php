<div class="container-fluid {!! isSet($class)? $class:'' !!} share-buttons">
    <a href='https://www.facebook.com/dialog/share?&appId=1429726730641216&version=v2.0&display=popup&href=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2F&redirect_uri=https%3A%2F%2Fdevelopers.facebook.com%2Ftools%2Fexplorer'
       target='_blank' class="no-border" data-toggle="tooltip" title="{{ trans('general.share_facebook') }}">
        <i class='fa fa-facebook fa-lg'></i>
    </a>

    <a href='https://twitter.com/intent/tweet?text={!! $title !!}&url={!! $url !!}'
       target='_blank' class="no-border" data-toggle="tooltip" title='{{ trans('general.share_twitter') }}'>
        <i class='fa fa-twitter fa-lg'></i>
    </a>

    <a href='//www.pinterest.com/pin/create/button/?url={!! $url !!}' class='pin-it-button no-border'
       count-layout='none' target='_blank' data-toggle="tooltip" title="{{ trans('general.share_pinterest') }}">
        <i class='fa fa-pinterest fa-lg'></i>
    </a>
    <a href='mailto:?subject={!! $title !!}, {{trans('informacie.info_gallery_SNG')}}&body={!!$url!!}'
       style="font-size:0.9em" target='_blank' class="no-border" data-toggle="tooltip"
       title="{{ trans('general.share_mail') }}">
        <i class='fa fa-envelope fa-lg'></i>
    </a>
    <a onclick='copyLinkToClipboard("{!!$url!!}")' style='cursor:pointer' data-toggle="tooltip" class="no-border"
       title="{{ trans('general.copy_url') }}">
        <i class='fa fa-link fa-lg'></i>
    </a>
</div>