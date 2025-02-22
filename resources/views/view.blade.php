<!DOCTYPE html>
<html>
<head>
	<title>Redirecting ...</title>
	{!! setting('integration.google_analytics') !!}
	{!! setting('integration.facebook_pixel') !!}
	@if (!isset($config['error']))
		<meta property="og:title" content="{{ $config['og:title'] }}">
		<meta property="og:description" content="{{ $config['og:description'] }}">
		<meta property="og:image" content="{{ $config['og:image'] }}">
		<meta property="og:url" content="{{ $config['og:url'] }}">
		<meta property="og:type" content="{{ $config['og:type'] }}">
	@else
		<meta name="robots" content="noindex, nofollow">
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