<meta name="twitter:card" content="summary" />
@section('og')
<meta property="og:title" content="ARTBASE" />
<meta property="og:description" content="{{ trans('master.meta_description') }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to('/images/khb/artbase-og-image.jpg') !!}" />
<meta property="og:site_name" content="ARTBASE" />
@show