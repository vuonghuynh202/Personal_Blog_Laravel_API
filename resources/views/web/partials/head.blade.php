<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @yield('title')
  <link rel="icon" type="image/x-icon" href="{{ asset('webTemplate/img/favicon.ico') }}">

  <link rel="stylesheet" href="{{ asset('webTemplate/vendors/bootstrap/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('webTemplate/vendors/fontawesome/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('webTemplate/vendors/themify-icons/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('webTemplate/vendors/linericon/style.css') }}">
  <link rel="stylesheet" href="{{ asset('webTemplate/vendors/owl-carousel/owl.theme.default.min.css') }}">
  <link rel="stylesheet" href="{{ asset('webTemplate/vendors/owl-carousel/owl.carousel.min.css') }}">

  <link rel="stylesheet" href="{{ asset('webTemplate/css/style.css') }}">

  <link rel="stylesheet" href="{{ asset('vendors/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css') }}">
  @yield('css')
</head>