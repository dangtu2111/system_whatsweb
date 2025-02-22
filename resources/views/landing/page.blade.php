@extends('landing.app', ['title' => $page->title])

@section('content')
<div class="hero hero-sm">
	<div id="particles-js"></div>
	<div class="container">
		<div class="hero-text">
			<h1>{!! $page->title !!}</h1>
		</div>
	</div>
</div>
<section>
	<div class="container">
		<div class="card card-post">
			<div class="card-body">
				{!! $page->content !!}
			</div>
		</div>
	</div>
</section>
@stop

@section('plugins_js')
<script src="{{ asset('dist/modules/particles.min.js') }}"></script>
@stop

@section('scripts')
<script>
particlesJS("particles-js", {"particles":{"number":{"value":96,"density":{"enable":true,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.3,"random":false,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":2,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":true,"distance":144.30708547789706,"color":"#ffffff","opacity":0.2,"width":0.6413648243462091},"move":{"enable":true,"speed":2,"direction":"none","random":true,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":false,"mode":"repulse"},"onclick":{"enable":true,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true});
</script>
@stop