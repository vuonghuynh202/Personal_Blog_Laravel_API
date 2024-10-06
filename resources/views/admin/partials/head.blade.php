<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @yield('title')
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('adminTemplate/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('adminTemplate/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminTemplate/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('adminTemplate/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('adminTemplate/vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('adminTemplate/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- <link rel="stylesheet" href="{{ asset('adminTemplate/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}"> -->
  <link rel="stylesheet" href="{{ asset('adminTemplate/js/select.dataTables.min.css') }}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('adminTemplate/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('adminTemplate/images/favicon.ico') }}"/>
  @yield('css')
</head>