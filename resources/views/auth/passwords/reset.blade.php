@extends('layouts.app_frontend', ['title' => 'Reset Password'])

@section('content')
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card card-primary">
              <div class="card-header"><h4>Reset Password</h4></div>
              <div class="card-body">
                <form method="POST" action="{{route('password.update')}}">
                  @csrf
                  <input type="hidden" name="token" value="{{ $token }}">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" tabindex="1" required autofocus>
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
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Reset Password
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@stop