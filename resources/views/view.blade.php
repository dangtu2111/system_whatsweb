<!DOCTYPE html>
<html>
	
<head>
	<!-- @if(isset($config) && !isset($config['error']))
        @foreach($config as $key => $value)
            <meta property="{{ $key }}" content="{{ $value }}">
        @endforeach
	@else
	<title>Redirecting ...</title>
	{!! setting('integration.google_analytics') !!}
	{!! setting('integration.facebook_pixel') !!}

    @endif -->
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Open Graph Meta Tags -->
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
<meta name="robots" content="max-image-preview:large">
<meta property="og:url" content="{{route('index')}}">
<meta property="og:type" content="website">
<meta property="og:title" content="SunShine Tour">
<meta property="og:description" content="Travel Blog">
<meta property="og:image" content="{{asset('logo04.png')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Sunshine Tours</title>

    <title>Khải Azcam</title>
	
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