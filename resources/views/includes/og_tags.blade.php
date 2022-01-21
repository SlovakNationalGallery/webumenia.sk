<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@webumeniaSK" />
<meta property="og:site_name" content="Web umenia" />
@section('og')
<meta property="og:title" content="Web umenia" />
<meta property="og:description" content="{{ trans('master.meta_description') }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! asset('/images/og-image-'.random_int(1, 2).'.jpg') !!}" />
@show