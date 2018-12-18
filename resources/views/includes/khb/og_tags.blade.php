<meta name="twitter:card" content="summary" />
@section('og')
<meta property="og:title" content="{{ trans('master.meta-title') }}" />
<meta property="og:description" content="{{ trans('master.meta-description') }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to('/images/khb/artbase-og-image.jpg') !!}" />
<meta property="og:site_name" content="{{ trans('master.meta-title') }}" />
@show