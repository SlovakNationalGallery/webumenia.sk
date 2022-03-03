<!DOCTYPE html>
<html lang="sk">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="author" content="lab.SNG">
  <meta name="robots" content="noindex, nofollow">

  <title>
      {!! $item->title !!} | {{ trans('zoom.title') }}
  </title>

  <!--  favicons-->
  @include('includes.favicons')
  <!--  /favicons-->
  <!--  Open Graph protocol -->
  @include('includes.og_tags')
  <!--  Open Graph protocol -->

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="{{ mix('/css/style.css') }}" />
</head>
<body class="template-zoom">
  @include('components.zoomviewer', [
    'item' => $item,
    'index' => $index,
    'fullIIPImgURLs' => $fullIIPImgURLs,
  ])

  <script type="text/javascript" src="{{ mix('/js/manifest.js') }}"></script>
  <script type="text/javascript" src="{{ mix('/js/vendor.js') }}"></script>
  <script type="text/javascript" src="{{ mix('/js/zoom.js') }}"></script>

  @yield('javascript')
</body>
</html>
