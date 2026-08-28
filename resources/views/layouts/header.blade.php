<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ URL::asset('images/m.png')}}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
 
 
        <!-- third party css -->
    <link href="{{asset('inside_login/assets/css/vendor/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css">
    <!-- third party css end -->

    <!-- App css -->
    <link href="{{asset('inside_login/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('inside_login/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="light-style">
    <link href="{{asset('inside_login/assets/css/app-dark.min.css')}}" rel="stylesheet" type="text/css" id="dark-style">

    <link rel="stylesheet" href="{{asset('css/component-chosen.css')}}">
   <style>
      .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('images/loader.gif') }}") 50% 50% no-repeat white;
            opacity: .8;
            background-size: 120px 120px;
        }
      </style>
</head>

<body  class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
  <div id="loader" style="display:none;" class="loader">
  </div>
    <div class="wrapper">
      <!-- ========== Left Sidebar Start ========== -->
      <div class="leftside-menu">

          <!-- LOGO -->
          <a href="{{url('/')}}" class="logo text-center logo-light">
              <span class="logo-lg">
                  <img src="{{asset('/images/m.png')}}" alt="" height="100">
              </span>
              <span class="logo-sm">
                  <img src="{{asset('/images/m.png')}}" alt="" height="16">
              </span>
          </a>

          <!-- LOGO -->
          <a href="{{url('/')}}" class="logo text-center logo-dark">
            <span class="logo-lg">
                <img src="{{asset('/images/m.png')}}" alt="" height="100">
            </span>
            <span class="logo-sm">
                <img src="{{asset('/images/m.png')}}" alt="" height="16">
            </span>
        </a>
        <hr class="bg-white mt-3">
          <div class="h-100" id="leftside-menu-container" data-simplebar="">

              <!--- Sidemenu -->
              <ul class="side-nav">
                  @if(auth()->user()->clearance_admin)
                  <li class="side-nav-title side-nav-item">HR Admin</li>

                  <li class="side-nav-item">
                    <a  href="{{url('/')}}"  class="side-nav-link">
                        <i class="uil-home-alt"></i>
                        <span> Dashboard </span>
                    </a>
                  </li>
                  <li class="side-nav-item">
                    <a  href="{{url('/resigned-employees')}}" class="side-nav-link">
                        <i class="uil-exit"></i>
                        <span class="badge bg-success float-end">{{for_setup()}}</span>
                        <span> Resigned Employees </span>
                    </a>
                  </li>
                  <li class="side-nav-item">
                    <a  href="{{url('/ongoing-clearance')}}" class="side-nav-link">
                        <i class="uil-exchange"></i>
                        <span class="badge bg-warning float-end">{{ongoing_clearance()}}</span>
                        <span> Ongoing Clearances </span>
                    </a>
                  </li>
                  <li class="side-nav-item">
                    <a  href="{{url('/cleared')}}" class="side-nav-link">
                        <i class="uil-file-check"></i>
                        <span class="badge bg-success float-end">{{cleared()}}</span>
                        <span> Cleared </span>
                    </a>
                  </li>
                  <li class="side-nav-item">
                    <a  href="{{url('/for-computation')}}" class="side-nav-link">
                        <i class="uil-calculator-alt"></i>
                        <span class="badge bg-success float-end">{{ongoing_computation()}}</span>
                        <span> For Computation </span>
                    </a>
                  </li>
                  <li class="side-nav-item">
                    <a  href="{{url('/for-release')}}" class="side-nav-link">
                        <i class="uil-envelope-send"></i>
                        <span class="badge bg-success float-end">{{for_release()}}</span>
                        <span> For Release </span>
                    </a>
                  </li>
                  <li class="side-nav-item">
                    <a  href="{{url('/released')}}" class="side-nav-link">
                        <i class="uil-user-check"></i>
                        <span class="badge bg-success float-end">{{released()}}</span>
                        <span> Released </span>
                    </a>
                  </li>
                  <li class="side-nav-item">
                    <a  href="{{url('/signatories')}}" class="side-nav-link">
                        <i class="uil-user-plus"></i>
                        <span> Signatories </span>
                    </a>
                  </li>
                  {{-- <li class="side-nav-title side-nav-item">As Resignee</li>

                  <li class="side-nav-item">
                    <a  href="{{url('/my-clearance')}}"  class="side-nav-link">
                        <i class="uil-list-ui-alt"></i>
                        <span> My Clearance </span>
                    </a>
                  </li>
                  <li class="side-nav-title side-nav-item">As Signatory</li>

                  <li class="side-nav-item">
                    <a href="{{url('for-clearance')}}"  class="side-nav-link">
                        <i class="uil-check"></i>
                        
                        <span class="badge bg-success float-end">{{for_clearance()}}</span>
                        <span> For Approval</span>
                    </a>
                  </li> --}}
                  @endif

              </ul>

            

          </div>
          <!-- Sidebar -left -->

      </div>
      <!-- Left Sidebar End -->

      <!-- ============================================================== -->
      <!-- Start Page Content here -->
      <!-- ============================================================== -->

      <div class="content-page">
          <div class="content">
              <!-- Topbar Start -->
              <div class="navbar-custom">
                  <ul class="list-unstyled topbar-menu float-end mb-0">

                      <li class="dropdown notification-list">
                          <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                              <span class="account-user-avatar"> 
                                  <img src="{{get_avatar(auth()->user()->employee->id)}}"  onerror="this.src='{{ URL::asset('/images/no_image.png') }}';"  alt="user-image" class="rounded-circle">
                              </span>
                              <span>
                                  <span class="account-user-name">{{auth()->user()->employee->first_name}}</span>
                                  <span class="account-position">{{auth()->user()->employee->position}}</span>
                              </span>
                          </a>
                          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                              <!-- item-->
                              <div class=" dropdown-header noti-title">
                                  <h6 class="text-overflow m-0">Welcome !</h6>
                              </div>
{{-- 
                              <!-- item-->
                              <a href="javascript:void(0);" class="dropdown-item notify-item">
                                  <i class="mdi mdi-account-circle me-1"></i>
                                  <span>My Account</span>
                              </a>

                              <!-- item-->
                              <a href="javascript:void(0);" class="dropdown-item notify-item">
                                  <i class="mdi mdi-account-edit me-1"></i>
                                  <span>Settings</span>
                              </a>

                              <!-- item-->
                              <a href="javascript:void(0);" class="dropdown-item notify-item">
                                  <i class="mdi mdi-lifebuoy me-1"></i>
                                  <span>Support</span>
                              </a>

                              <!-- item-->
                              <a href="javascript:void(0);" class="dropdown-item notify-item">
                                  <i class="mdi mdi-lock-outline me-1"></i>
                                  <span>Lock Screen</span>
                              </a> --}}

                              <!-- item-->
                              <a href="#" onclick="logout(); show();" class="dropdown-item notify-item">
                                  <i class="mdi mdi-logout me-1"></i>
                                  <span>Logout</span>
                              </a>
                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                          </div>
                      </li>
                  </ul>
                  <button class="button-menu-mobile open-left">
                      <i class="mdi mdi-menu"></i>
                  </button>
                
              </div>
              <!-- end Topbar -->
              
              <!-- Start Content-->
              <div class="container-fluid">
                @yield('content')
                 
                  <!-- end row -->

              </div>
              <!-- container -->

          </div>
          <!-- content -->

          <!-- Footer Start -->
          <footer class="footer">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-md-6">
                          <script>document.write(new Date().getFullYear())</script> © W Developers
                      </div>
                     
                  </div>
              </div>
          </footer>
          <!-- end Footer -->

      </div>

      <!-- ============================================================== -->
      <!-- End Page content -->
      <!-- ============================================================== -->


    </div>
    <div class="rightbar-overlay"></div>
    <!-- /End-bar -->

    <!-- bundle -->
    <script src="{{asset('inside_login/assets/js/vendor.min.js')}}"></script>
    <script src="{{asset('inside_login/assets/js/app.min.js')}}"></script>

    <!-- third party js -->
    <script src="{{asset('inside_login/assets/js/vendor/apexcharts.min.js')}}"></script>
    <script src="{{asset('inside_login/assets/js/vendor/jquery-jvectormap-1.2.2.min.js')}}"></script>
    <script src="{{asset('inside_login/assets/js/vendor/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{asset('js/chosen.jquery.min.js')}}"></script>
    <!-- third party js ends -->
    <script>
      $('.modal-select').select2({
          dropdownParent: $('.modal')
      });

        $(".chosen-select").chosen({width:"100%"})
  </script>
    <!-- demo app -->
    <script src="{{asset('inside_login/assets/js/pages/demo.dashboard.js')}}"></script>
      <script type="text/javascript">
          function show() {
              document.getElementById("loader").style.display = "block";
          }
        function logout() {
              event.preventDefault();
              document.getElementById('logout-form').submit();
          }
    
      </script>
      
      @include('sweetalert::alert')
</body>
</html>
