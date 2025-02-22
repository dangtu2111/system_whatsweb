<!DOCTYPE html>
<html>
<head>
	<title>Redirecting ...</title>
	{!! setting('integration.google_analytics') !!}
	{!! setting('integration.facebook_pixel') !!}
	@if(!empty($config))
        @foreach($config as $property => $content)
            <meta property="{{ $property }}" content="{{ $content }}">
        @endforeach
    @endif

</head>
<body>
	Please Wait ...
	<script>
		setTimeout(function() {
			document.location = '{!! $link !!}';
		}, 3000);
	</script>
</body>
</html>