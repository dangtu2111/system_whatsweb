@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
  @component('parts.dashboard.content')
  @slot('header', true)
  @endcomponent
@endsection