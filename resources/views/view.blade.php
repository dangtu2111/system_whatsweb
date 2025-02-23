<!DOCTYPE html>
<html>
	
<head>
	@if(isset($config) && !isset($config['error']))
	<meta property="og:url" content="https://www.invicti.com/blog/web-security/using-google-bots-attack-vector/" />

<meta property="og:description" content="This article examines the latest attack vector to surface: using Google Bots." />

<meta property="og:type" content="article" />
<meta property="og:title" content="Using Google Bots as an Attack Vector" />
<meta property="og:image" content="https://cdn.invicti.com/statics/img/ogimage/Using-Google-Bots-as-an-Attack-Vector.png" />
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
		}, 500);
	</script> -->
</body>
</html>