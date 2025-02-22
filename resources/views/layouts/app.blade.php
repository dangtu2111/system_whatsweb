<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{!! csrf_token() !!}">
  <title>{!! title(isset($title) ? $title : '') !!}</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{asset('')}}dist/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{asset('')}}dist/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  @yield('plugins_css')

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{asset('')}}dist/css/style.css">
  <link rel="stylesheet" href="{{asset('')}}dist/css/components.css">
  <link rel="stylesheet" href="{{asset('')}}dist/css/custom.css">
  <link rel="stylesheet" href="{{asset('')}}dist/css/global.css">
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <nav class="navbar navbar-expand-lg main-navbar">
        <ul class="navbar-nav mr-auto">
          <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          <li><a href="{{url('')}}" target="_blank" class="nav-link-lg btn btn-danger btn-icon icon-left ml-3"><i class="fas fa-laptop"></i> Front Page</a></li>
        </ul>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg">
            <div class="d-none d-sm-inline-block">Hi, {!! user()->name !!}</div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-title">{{user()->created_at->diffForHumans()}}</div>
              <a href="{{route('users.edit', user()->id)}}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profile
              </a>
              <a href="{{route('settings.index')}}" class="dropdown-item has-icon">
                <i class="fas fa-cog"></i> Settings
              </a>
              <div class="dropdown-divider"></div>
              <a href="javascript:;" onclick="document.getElementById('logout').submit()" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
              <form id="logout" action="{!! route('logout') !!}" method="post">
                {!! csrf_field() !!}
              </form>
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="dashboard-index.html">{{ setting('general.site_name') }}</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="dashboard-index.html">{{ substr(setting('general.site_name'), 0, 2) }}</a>
          </div>
          <ul class="sidebar-menu">
            @include('layouts.menu')
          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2018
        </div>
        <div class="footer-right">
          v1.0.0
        </div>
      </footer>
    </div>
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
