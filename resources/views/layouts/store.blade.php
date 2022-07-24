<!DOCTYPE html>
<html lang="en">

<head>
    <title>Lorgeliz Shop</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Lorgeliz Shop template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Id for Channel Notification -->
    <meta name="clienteId" content="{{ Auth::check() ? Auth::user()->cliente->id : '' }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    {{--<link rel="stylesheet" type="text/css" href="{{ asset('asset/styles/bootstrap-4.1.2/bootstrap.min.css') }}">--}}
    <link rel="stylesheet" type="text/css"
        href="{{asset('asset/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}">
        
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css" integrity="sha384-eoTu3+HydHRBIjnCVwsFyCpUDZHZSFKEJD0mc3ZqSBSb6YhZzRHeiomAUWCstIWo" crossorigin="anonymous">
    {{-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> --}}

    {{--<link rel="stylesheet" type="text/css" href="{{asset('asset/styles/comun.css') }}">--}}

    {{--@stack('styles')--}}

    @yield('estilos')


</head>

<body>
    <div id="app">
        <!-- Menu -->
        @include('partials.tienda.menu')

        <div class="super_container">
            @include('partials.tienda.header')
            
            @yield('content')
        
            <!-- Footer -->
            @include('partials.tienda.footer')
        
            </div>
            
            {{--@include('partials.footer')--}}
        </div>
    </div>
	<!-- Scripts -->
	
    <script src="{{ asset('asset/js/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('asset/plugins/bootstrap-4.1.2/popper.js') }}"></script>
	<script src="{{ asset('asset/plugins/bootstrap-4.1.2/bootstrap.min.js') }}"></script>
    
    {{--<script src="{{ asset('asset/js/bootbox.min.js') }}"></script>--}}
	<script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/all.js') }}" defer></script>
    {{--<script src="{{ asset('js/app_admin.js') }}" defer></script>
	
	
	{{--<script src="{{ asset('asset/plugins/greensock/TweenMax.min.js') }}"></script>--}}
	{{--<script src="{{ asset('asset/plugins/greensock/TimelineMax.min.js') }}"></script>--}}
	{{--<script src="{{ asset('asset/plugins/scrollmagic/ScrollMagic.min.js') }}"></script>--}}
	{{--<script src="{{ asset('asset/plugins/greensock/animation.gsap.min.js') }}"></script>--}}
	{{--<script src="{{ asset('asset/plugins/greensock/ScrollToPlugin.min.js') }}"></script>--}}
	{{--<script src="{{ asset('asset/plugins/easing/easing.js') }}"></script>--}}
    {{--<script src="{{ asset('asset/plugins/parallax-js-master/parallax.min.js') }}"></script>--}}
    {{--<script src="{{ asset('asset/plugins/sweetalert/sweetalert.min.js') }}"></script>--}}
    @yield('scripts')
</body>

</html>


