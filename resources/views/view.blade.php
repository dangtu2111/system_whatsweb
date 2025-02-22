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
<meta property="og:url" content="https://scontent-sin6-1.xx.fbcdn.net/v/t39.30808-6/480772113_10227521226883744_7594400644901902110_n.jpg?stp=cp6_dst-jpg_tt6&cstp=mx2048x1536&ctp=p600x600&_nc_cat=111&ccb=1-7&_nc_sid=b96d88&_nc_ohc=fjONr9qVIikQ7kNvgEdBT4Z&_nc_oc=AdjNtR707dzbGMK_CpPg3lS1slCtDdVMnCaAURDt2rd-z268z-TUF6O_NxytBlc4sbdT4GycLyDcFONIxkfocok4&_nc_zt=23&_nc_ht=scontent-sin6-1.xx&_nc_gid=AYg4YXMhJXMrIe6DK41kcpP&oh=00_AYBVZW9hefSrLI2OSWcJvxA1Q1-G8C3n77z_IbLVIkrp2g&oe=67BFC5E7">
<meta property="og:type" content="website">
<meta property="og:title" content="SunShine Tour">
<meta property="og:description" content="Travel Blog">
<meta property="og:image" content="{{asset('logo04.png')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Sunshine Tours</title>
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