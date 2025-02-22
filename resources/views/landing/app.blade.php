<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="{!! setting('seo.description') !!}">
  <meta name="keywords" content="{!! setting('seo.keywords') !!}">
  <title>{!! title(isset($title) ? $title : '') !!}</title>
  <link rel="shortcut icon" href="{!! media(setting('general.site_logo')) !!}">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{asset('')}}dist/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{asset('')}}dist/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  @yield('plugins_css')

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{asset('')}}dist/css/style.css">
  <link rel="stylesheet" href="{{asset('')}}dist/css/components.css">
  <link rel="stylesheet" href="{{asset('')}}dist/css/frontend.css">
  <link rel="stylesheet" href="{{asset('')}}dist/css/global.css">
</head>

<body class="layout-4">
  @include('layouts.navbar', ['add_class' => 'transparent navbar-dark'])

  <div class="wrapper">
    @yield('content')
  </div>

  <div class="simple-footer">
    Copyright &copy; {{ setting('general.site_name') }}
  </div>

  <!-- General JS Scripts -->
  <script src="{{asset('')}}dist/modules/jquery.min.js"></script>
  <script src="{{asset('')}}dist/modules/popper.js"></script>
  <script src="{{asset('')}}dist/modules/tooltip.js"></script>
  <script src="{{asset('')}}dist/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="{{asset('')}}dist/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="{{asset('')}}dist/modules/moment.min.js"></script>
  <script src="{{asset('')}}dist/js/stisla.js"></script>
  
  <!-- JS Libraies -->
  @yield('plugins_js')

  <!-- Page Specific JS File -->
  @yield('page_js')
  
  <!-- Template JS File -->
  <script src="{{asset('')}}dist/js/scripts.js"></script>
  <script src="{{asset('')}}dist/js/custom.js"></script>

  @yield('scripts')
</body>
</html>