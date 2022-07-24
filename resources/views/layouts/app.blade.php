<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
	<link rel="stylesheet" type="text/css" href="{{ asset('asset/plugins/bootstrap-4.1.2/bootstrap.min.css') }}">
	@yield('estilos')
	
</head>
<body>
	{{--@include('partials.navigation')--}}
	<div id="app">

		<main class="cuerpo">
			@yield('content')
		</main>
	</div>

	{{--@include('partials.footer')--}}

	
    <!-- Scripts -->
    
    <script src="{{ asset('asset/js/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('asset/plugins/bootstrap-4.1.2/popper.js') }}"></script>
	<script src="{{ asset('asset/plugins/bootstrap-4.1.2/bootstrap.min.js') }}"></script>

	@yield('scripts')
	
</body>
</html>
