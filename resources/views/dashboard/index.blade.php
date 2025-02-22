@extends('layouts.app_frontend', ['title' => 'Dashboard'])

@section('content')
  @component('parts.dashboard.content')
  @slot('header', false)
  @endcomponent
@stop