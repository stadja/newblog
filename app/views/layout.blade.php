<?php $withPersona = 1; ?>
<!doctype html>
<html lang="fr">
<head>
	<meta name="google-site-verification" content="A_Kgxs0C5FENQ7zC0mSEoyDBsbW-s2agCbhpsOSJJ0c" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	@foreach ($resources['css_before'] as $resource)
	<link href="/css/{{ $resource }}" rel="stylesheet">
	@endforeach
	<link rel="alternate" type="application/rss+xml" href="{{ URL::to('rss.xml') }}" title="Votre titre">
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="/css/style.css" rel="stylesheet">
	@foreach ($resources['css_after'] as $resource)
	<link href="/css/{{ $resource }}" rel="stylesheet">
	@endforeach
	<style type="text/css">
		@foreach ($resources['css_fx'] as $resource)
		{{ $resource }}
		@endforeach
	</style>
	<title>
		@section('title')
			Sérendipité (et épectase)
		@show
	</title>
</head>
<body>
	<div class="container">
		<div class="row-fluid show-grid">
			<div class="span9 offset2">
				<h1 style=''><a href='/'>Sérendipité (et épectase)</a></h1>
			</div>
			@if ($withPersona)
			<div class="span1" id='connectionOrAdd' style='display:block;'>
				@if (Auth::check())
				<a href="{{ URL::route('posts.create')  }}">
					A
				</a> &nbsp;
				<a href='#' id='signout'>
					-
				</a>
				@else
				<a href='#' id='signin'>
					+
				</a>
				@endif
			</div>
			@else
			<div class="span1" id='connectionOrAdd' style='display:block;'>
				@if (Auth::check())
				<a href="{{ URL::route('posts.create')  }}">
					A
				</a> &nbsp;
				<a href='#' id='logout'>
					-
				</a>
				@else
				<a href='#' id='login'>+</a>
				<div id='loginForm' style="display: none;">
					{{ Form::model(array(), array('id' => 'loginSubmit', 'url' => 'auth/login', 'method' => 'post'))}}
					{{ Form::label('email', 'email') }}
					{{ Form::email('email', null, array('placeholder' => 'email')) }}
					{{ Form::label('password', 'password') }}
					{{ Form::password('password', null, array('placeholder' => 'password')) }}
					{{ Form::label('', '') }}
					{{ Form::submit('Connecter') }}
					{{ Form::close() }}
				</div>
				@endif
			</div>
			@endif
			@yield('content')
		</div>
	</div>
	<script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.min.js"></script>
	@if ($withPersona)
	<script src="https://login.persona.org/include.js"></script>
	@endif
	<script type="text/javascript">
		var currentUser = <?php echo (Auth::check()) ? "'".Auth::user()->email."'" : "null"; ?>;
	</script>
	@if ($withPersona)
	<script src="/js/login-with-persona.js"></script>
	@else
	<script src="/js/login.js"></script>
	@endif
	@foreach ($resources['js'] as $resource)
	<script src="/js/{{ $resource }}"></script>
	@endforeach
	<script type="text/javascript">
		(function() {
			@foreach ($resources['js_fx'] as $resource)
			{{ $resource }}
			@endforeach
		})();
	</script>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-48510360-1', '.stadja.net');
		ga('send', 'pageview');
	</script>
</body>
</html>
