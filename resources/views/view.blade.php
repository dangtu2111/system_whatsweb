<!DOCTYPE html>
<html>
	
<head>
	@if (!empty($config) && is_array($config)&& !isset($config['error']))
        @foreach ($config as $key => $content)
            <meta 
                @if (Str::startsWith($key, 'og:'))
                    property="{{ $key }}"
                @else
                    name="{{ $key }}"
                @endif
                content="{{ $content }}">
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