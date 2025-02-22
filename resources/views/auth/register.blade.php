@extends('layouts.app_frontend', ['title' => 'Register'])

@section('plugins_css')
  <link rel="stylesheet" href="{{asset('')}}dist/modules/bootstrap-social/bootstrap-social.css">
@stop

@section('content')
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
              <div class="card-header"><h4>Register</h4></div>

              <div class="card-body">
                <form method="POST" action="{{route('register')}}">
                  @csrf
                  <div class="form-group">
                    <label for="email">Name</label>
                    <input id="name" type="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" tabindex="1" required autofocus value="{{ old('name') }}">
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                          {{ $errors->first('name') }}
                        </span>
                    @endif
                  </div>

                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" tabindex="1" required value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                          {{ $errors->first('email') }}
                        </span>
                    @endif
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                      <label for="password" class="control-label">Password</label>
                    </div>
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" tabindex="2" required>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                          {{ $errors->first('password') }}
                        </span>
                    @endif
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                      <label for="password-confirmation" class="control-label">Confirm Password</label>
                    </div>
                    <input id="password-confirmation" type="password" class="form-control{{ $errors->has('password-confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" tabindex="2" required>
                  </div>

                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="agree" class="custom-control-input{{ $errors->has('agree') ? ' is-invalid' : '' }}" tabindex="3" id="agree">
                      <label class="custom-control-label" for="agree">Agree with <a href="{{route('page.show', 'terms-and-conditions')}}">Terms and Conditions</a></label>
                      <div class="invalid-feedback">You must agree with our Terms and Conditions</div>
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Register
                    </button>
                  </div>
                </form>
                @if(setting('features.login_with_facebook') || setting('features.login_with_google'))
                <div class="text-center mt-4 mb-3">
                  <div class="text-job text-muted">Login With Social</div>
                </div>
                @endif
                <div class="row sm-gutters">
                  @if(setting('features.login_with_facebook'))
                  <div class="col-6">
                    <a class="btn btn-block btn-social btn-facebook" href="{{ route('loginwith', 'facebook') }}">
                      <span class="fab fa-facebook"></span> Facebook
                    </a>
                  </div>
                  @endif
                  @if(setting('features.login_with_google'))
                  <div class="col-6">
                    <a class="btn btn-block btn-social btn-google" href="{{ route('loginwith', 'google') }}">
                      <span class="fab fa-google"></span> Google
                    </a>                                
                  </div>
                  @endif
                </div>
              </div>
            </div>
            <div class="mt-5 text-muted text-center">
              Already have an account? <a href="{{route('login')}}">Login</a>
            </div>
          </div>
        </div>
      </div>
    </section>
@stop