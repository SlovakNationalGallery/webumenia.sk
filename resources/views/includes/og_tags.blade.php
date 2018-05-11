<!--  Open Graph protocol -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@narodnigalerie" />
@section('og')
<meta property="og:title" content="Národní galerie v Praze - sbírky" />
<meta property="og:description" content="{{ trans('master.meta_description') }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{!! Request::url() !!}" />
<meta property="og:image" content="{!! URL::to('/images/og-image-'.random_int(1, 2).'.jpg') !!}" />
<meta property="og:site_name" content="Národní galerie v Praze - sbírky" />
@show
