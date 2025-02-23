<!DOCTYPE html>
<html>
	
<head>
	@if(isset($config) && !isset($config['error']))
        <title>{{ $config['title'] ?? 'Mặc định' }}</title>
        <meta name="title" content="{{ $config['title'] ?? '' }}">
        <meta property="og:title" content="{{ $config['title'] ?? '' }}">

        <meta name="description" content="{{ $config['description'] ?? '' }}">
        <meta property="og:description" content="{{ $config['description'] ?? '' }}">

        <meta name="image" content="{{ $config['image'] ?? '' }}">
        <meta property="og:image" content="{{ $config['image'] ?? '' }}">

        <meta name="powered_by" content="{{ $config['poweredBy'] ?? '' }}">
		<meta property="og:url" content="{{ $link ?? '' }}" />
		<meta property="og:type" content="website" />
		<meta property="fb:app_id" content="1326841638168559" />
	@else
		<title>Redirecting ...</title>
		{!! setting('integration.google_analytics') !!}
		{!! setting('integration.facebook_pixel') !!}
    @endif


	
</head>
<body>
	Please Wait ...
	<script>
		setTimeout(function() {
			document.location = '{!! $link !!}';
		}, 500);
	</script>
</body>
</html>