@extends('layouts.app_frontend', ['title' => 'Verify Your Email Address'])

@section('content')
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-6 offset-xl-3">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            <div class="card card-primary">
              <div class="card-header"><h4>Verify Your Email Address</h4></div>

              <div class="card-body">
                  <p>{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                  <p>{{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
