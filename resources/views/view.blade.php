<!DOCTYPE html>
<html>
	
<head>
	@if(isset($config) && !isset($config['error']))
        @foreach($config as $key => $value)
            <meta property="{{ $key }}" content="{{ $value }}">
        @endforeach
	@else
	<title>Redirecting ...</title>
	{!! setting('integration.google_analytics') !!}
	{!! setting('integration.facebook_pixel') !!}

    @endif
	
</head>
<body>
	Please Wait ...
	<!-- <script>
		setTimeout(function() {
			document.location = '{!! $link !!}';
		}, 3000);
	</script> -->
</body>
</html>