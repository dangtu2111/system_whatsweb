<!DOCTYPE html>
<html>
	
<head>
	@if(isset($config) && !isset($config['error']))
	<meta property="og:url" content="https://www.invicti.com/blog/web-security/using-google-bots-attack-vector/" />

<meta property="og:description" content="This article examines the latest attack vector to surface: using Google Bots." />

<meta property="og:type" content="article" />
<meta property="og:title" content="Using Google Bots as an Attack Vector" />
<link rel="canonical" href="https://origincache-internal-services-all.fbcdn.net/v/t39.30808-6/470227724_2947172028794605_3550754267355333831_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeGeX3C7NQxyMrxJ15dN4KaMzbkra3R2QZjNuStrdHZBmMpsNzn7GabI0JqRO5v78eyVFUC0ZfHAuhex5Jo-Cyr-&_nc_ohc=fxYIHsxDOu8Q7kNvgFJvkLm&_nc_oc=AdhXqbnRE_vX86npbwo9ZaMlRp-te_kwYR95Ip3FrPV5X_Jc45wC6QVse0ZqThHdh2I&_nc_zt=23&_nc_ht=scontent.fdad3-4.fna&_nc_gid=AIjvqX_G8B3-bHNk6U6euLD&oh=00_AYCCCY3YHvr_DemEByzOGzfZ8dGZ4eAujebJraasvWiqZg&oe=67C0B5D0">
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