@extends('layouts.app')

@section('content')

<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
          <div class="container-fluid">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="{{url('/')}}">
              Online Clearance
            </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>
            <div class="collapse navbar-collapse" id="navigation">
              <ul class="navbar-nav mx-auto">
                
              </ul>
              <ul class="navbar-nav d-lg-block d-none">
                <li class="nav-item">
                  {{-- <a href="{{url('clearance-tracker')}}" class="btn btn-sm mb-0 me-1 btn-primary">Clearance Tracker</a> --}}
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
              <div class="card card-plain">
                <div class="card-header pb-0 text-start">
                  <h4 class="font-weight-bolder">Sign In</h4>
                  <p class="mb-0">Enter your email and password to sign in</p>
                </div>
             
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="card-body">
                       
                        <div class="mb-3">
                        <input type="email" class="form-control form-control-lg" name="email" placeholder="Email" aria-label="Email" required>
             
                        </div>
                        <div class="mb-3">
                        <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" aria-label="Password" required>
                        </div>
                        <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <div class="text-center">
                        <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                        </div>
                    </div>
                </form>
                @if($errors->any())
                    <div class="form-group alert alert-danger alert-dismissable text-white">
                        <strong>{{$errors->first()}}</strong>
                    </div>
                @endif
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    {{-- <a href="{{ route('password.request') }}" class="text-primary text-gradient font-weight-bold">Forgot password?</a> --}}
                  </p>
                </div>
              </div>
            </div>
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-warning h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('https://wgroup.com.ph/wp-content/themes/yootheme/cache/e3/W-CityCenter-e37500ea.webp');
          background-size: cover;">
                <span class="mask bg-gradient-info opacity-6"></span>
                <h4 class="mt-5 text-white font-weight-bolder position-relative">"If you're <b>BRAVE</b> enough to say <b>GOODBYE</b>"</h4>
                <p class="text-white position-relative">life will reward you with a new <b>HELLO</b></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
