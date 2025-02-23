@extends('layouts.' . layout(), ['title' => $title])

@section('plugins_css')
<link rel="stylesheet" href="{{ asset('dist/modules/select2/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{asset('')}}dist/modules/bootstrap-social/bootstrap-social.css">
@stop

@section('content')
	<section class="section">
		@if(is_backend())
		<div class="section-header">
			<div class="section-header-back">
				<a href="{{ route_type('destination_url.index') }}" class="btn"><i class="fas fa-chevron-left"></i></a>
			</div>
			<h1>{!! $title !!}</h1>
		</div>
		@endif
		@if(!is_backend())
		<div class="container">
		@endif
			<div class="section-body">
				<div class="card card-primary">
					<div class="card-header">
						<h4>{!! $title !!}</h4>
					</div>
					<div class="card-body">
						@include('parts.destinationURL.fields')
					</div>
				</div>
			</div>
		@if(!is_backend())
		</div>
		@endif
	</section>

	@include('parts.destinationURL.modal')
@stop

@section('plugins_js')
<script src="{{ asset('dist/modules/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('dist/modules/axios.min.js') }}"></script>
<script src="{{ asset('dist/modules/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('vendor/midia/clipboard.js') }}"></script>
@stop

@section('scripts')
<script>
@include('parts.destinationURL.fields_js')
@include('parts.destinationURL.modal_js')
</script>
@stop