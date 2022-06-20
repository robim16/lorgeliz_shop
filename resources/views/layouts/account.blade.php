
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Id for Channel Notification -->
  <meta name="clienteId" content="{{ Auth::check() ? Auth::user()->cliente->id : '' }}">

  <title>Lorgeliz Tienda</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
  @yield('estilos')
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
      <a href="{{ route('home') }}" class="navbar-brand">
        <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">Lorgeliz Tienda</span>
      </a>
      
      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="{{ route('users.show', auth()->user()->slug)}}" class="nav-link">Mi cuenta</a>
          </li>
          <li class="nav-item">
          <a href="{{ route('pedidos.index')}}" class="nav-link">Mis pedidos</a>
          </li>
          <li class="nav-item">
          <a href="{{ route('devolucion.index') }}" class="nav-link">Devoluciones</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('contact') }}" class="nav-link">Contacto</a>
          </li>
          {{--<li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Dropdown</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="#" class="dropdown-item">Some action </a></li>
              <li><a href="#" class="dropdown-item">Some other action</a></li>

              <li class="dropdown-divider"></li>

              <!-- Level two dropdown-->
              <li class="dropdown-submenu dropdown-hover">
                <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Hover for action</a>
                <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                  <li>
                    <a tabindex="-1" href="#" class="dropdown-item">level 2</a>
                  </li>

                  <!-- Level three dropdown-->
                  <li class="dropdown-submenu">
                    <a id="dropdownSubMenu3" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">level 2</a>
                    <ul aria-labelledby="dropdownSubMenu3" class="dropdown-menu border-0 shadow">
                      <li><a href="#" class="dropdown-item">3rd level</a></li>
                      <li><a href="#" class="dropdown-item">3rd level</a></li>
                    </ul>
                  </li>
                  <!-- End Level three -->

                  <li><a href="#" class="dropdown-item">level 2</a></li>
                  <li><a href="#" class="dropdown-item">level 2</a></li>
                </ul>
              </li>
              <!-- End Level two -->
            </ul>
          </li>--}}
        </ul>

        <!-- SEARCH FORM -->
        <form class="form-inline ml-0 ml-md-3">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <messages :ruta="ruta"></messages>
        <!-- Messages Dropdown Menu -->
        {{-- <li id="message" class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-comments"></i>
            <span class="badge badge-danger navbar-badge">@{{messages.length}}</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item" v-for="item in messages" :key="item.id"  v-on:click.prevent="read_at(item.id)"> --}}
              <!-- Message Start -->
              {{-- <div class="media">
                {{-- <img src="{{ asset('adminlte/dist/img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle"> --}}
                {{-- <img :src="'storage/' + item.url" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    @{{item.nombres}} @{{item.apellidos}}
                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">@{{item.mensaje}}...</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div> --}}
              <!-- Message End -->
            {{-- </a> --}}
            {{-- <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <img src="{{ asset('adminlte/dist/img/user8-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    John Pierce
                    <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">I got your message bro</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <img src="{{ asset('adminlte/dist/img/user3-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Nora Silvester
                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">The subject goes here</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              <!-- Message End -->
            </a> --}}
            {{-- <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">Ver Todos Los Mensajes</a>
          </div>
        </li> --}}
        <!-- Notifications Dropdown Menu -->
        {{-- <div id="clientNotification">
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"></i>
              <span class="badge badge-warning navbar-badge">@{{notifications.length}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-header">@{{notifications.length}}</span>
              <div class="dropdown-divider"></div>
              
              <div v-if="notifications.length">
                <a href="" class="dropdown-item" v-for="item in notifications" :key="item.id"         
                v-on:click.prevent="readNotification(item.id, item.data.datos.notificacion.url)">
                  <i class="fas fa-envelope mr-2" v-show="item.data.datos.notificacion.msj"></i>
                  @{{item.data.datos.notificacion.msj}} --}}
                  {{--<span class="float-right text-muted text-sm">3 mins</span>--}}
                {{-- </a>
              </div>
              <div v-else>
                <a href="" class="ml-5" style="color: black"><span>no tienes notificaciones</span></a>
              </div>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">Todas Las Notificaciones</a>
            </div>
          </li>
        </div> --}}
        <notifications :ruta="ruta"></notifications>
        
        <li class="nav-item dropdown">
          <a id="navbarDropdown"
          class="nav-link dropdown-toggle"
          href="#" role="button"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
          >
          <img src="{{  auth()->user() ? auth()->user()->imagene ? url('storage/' . auth()->user()->imagene->url) : asset('asset/images/user.svg') : asset('asset/images/user.svg') }}" 
            alt="" class="rounded-circle" style="width: 34px">
          {{--<img src="{{  auth()->user() ? auth()->user()->imagene->url : asset('asset/images/user.svg') }}" alt="" class="rounded-circle" style="width: 34px">--}}
          <span class="caret"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('users.show', auth()->user()->slug) }}">
          {{ __("Mi cuenta") }}
        </a>
          @auth
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
              {{ __("Cerrar sesión") }}
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
          @endauth
        </div>
        </li>  

      </ul>
      <chat-alert></chat-alert>
    </div>
    
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-sm-6">
           @yield('title')
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('users.show', auth()->user()->slug)}}">Inicio</a></li>
            @yield('breadcumb')
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  @if(session('message'))
  <div class="col-md-8 mx-auto">
      <div class="alert alert-{{ session('message')[0] }} alert-dismissible fade show" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
          <p>{{ session('message')[1] }}</p>
      </div>
  </div>
  @endif

    @yield('content')

    {{-- <div id="app" class=""> --}}
      <div class="row">
        <chat-store :ruta="ruta"></chat-store>
      </div>
    {{-- </div> --}}

   </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->


<!-- REQUIRED SCRIPTS -->



<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
{{-- <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('asset/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/app_admin.js') }}" defer></script>
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}
@yield('scripts')

</body>
</html>

