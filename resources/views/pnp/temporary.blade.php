<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="lab.SNG">

    <title>
      @section('title')
      Wenceslaus Hollar Bohemus – Řeč jehly a rydla
      @show
    </title>


    <style type="text/css">

    html, body {
      height: 100%;
      padding: 0;
      margin: 0;
    }

    div.center-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    div.center-container img {
      width: 60%;
      height: auto;
    }

    @media only screen and (max-width: 500px) {
        div.center-container img {
          width: 90%;
        }
    }



    </style>

    <!--  favicons-->
    <!--  /favicons-->

    <!--  Open Graph protocol -->
    <!--  /Open Graph protocol -->

</head>

<body>

  <div class="center-container">
      <img alt="Wenceslaus Hollar Bohemus — řeč jehly a rydla —" src="/images/pnp/splash_screen.jpg" />
  </div>

</body>
</html>
