<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('titulo')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Id for Channel Notification -->
  <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : '' }}">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- overlayScrollbars -->
  @yield('estilos')
  {{-- <link href="{{ asset('css/styles.css') }}" rel="stylesheet"> --}}
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}"> --}}
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin') }}" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contacto</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown" id="chatNotification">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">@{{notifications.length}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a v-for="item in notifications" :key="item.id" href="#" class="dropdown-item" @click.prevent="initChat(item.user.id, item.id)">
            <!-- Message Start -->
            <div class="media">
              <img :src="location === '/lorgeliz_tienda_copia/public/admin' ? 'storage/' + item.user.imagene.url : '../storage/' + item.user.imagene.url" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                 @{{item.user.nombres}} @{{item.user.apellidos}}
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">@{{item.mensaje}}.</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          {{-- <div class="dropdown-divider"></div> --}}
          {{-- <a href="#" class="dropdown-item">
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
          <div class="dropdown-divider"></div> --}}
          {{-- <a href="#" class="dropdown-item">
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
          {{-- <div class="dropdown-divider"></div> --}}
          <a href="#" class="dropdown-item dropdown-footer">Ver Todos los Mensajes</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <div id="notification">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge" v-text="notifications.length"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">@{{notifications.length}} Notificaciones</span>
            <div class="dropdown-divider"></div>
            <div v-if="notifications.length">
              <a href="" class="dropdown-item" v-for="item in notifications" :key="item.id"         
              v-on:click.prevent="readNotification(item.id, item.data.datos.ventas.url)">
                  <i class="fas fa-cart-arrow-down mr-2"></i>
                  @{{item.data.datos.ventas.numero}} @{{item.data.datos.ventas.msj}}
                {{-- @{{item.ventas.numero}} @{{item.ventas.msj}}--}}
                  {{--<span class="float-right text-muted text-sm">3 mins</span>--}}
              </a>
              {{--<div class="dropdown-divider"></div>

              <a href="#" class="dropdown-item">
                <i class="fas fa-file-invoice-dollar mr-2"></i>
                {{--@{{item.data.datos.pagos.numero}} @{{item.data.datos.pagos.msj--}}
                {{--<span class="float-right text-muted text-sm">3 mins</span>
              </a>--}}
            </div>
            <div v-else>
              <a href="" class="ml-5" style="color: black"><span>no tienes notificaciones</span></a>
            </div>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">Ver Todas las Notificaciones</a>
          </div>
        </li>
      </div>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
    <chat-alert :role_id="{{  auth()->user()->role->id }}"></chat-alert>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin') }}" class="brand-link">
      <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Lorgeliz Tienda</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ auth()->user()->imagene ? url('storage/' . auth()->user()->imagene->url) : ''}}" class="img-circle elevation-2" alt="User Image">
          {{--<img src="{{ auth()->user()->imagene->url }}" class="img-circle elevation-2" alt="User Image">--}}
        </div>
        <div class="info d-flex">
        <a href="{{ 'users.show', auth()->user()->slug}}" class="d-block mr-2">{{ auth()->user()->nombres}}</a>
        <a href="{{ route('logout')}}" title="cerrar sesión" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i></a>
         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2 mb-5">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Categorías -->
          <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-list-alt"></i>
                  <p>
                      Categorías
                      <i class="right fas fa-angle-left"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ route('category.index')}}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Listado de Categorías</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('category.create')}}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Crear categoría</p>
                      </a>
                  </li>
              </ul>
            </li>

            <!-- Subcategorias -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-list-alt"></i>
                  <p>
                      Subcategorias
                      <i class="right fas fa-angle-left"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ route('subcategory.index')}}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Listado de Subcategorias</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('subcategory.create')}}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Crear subcategoria</p>
                      </a>
                  </li>
              </ul>
           </li>

         <!-- Subcategorias -->
         <li class="nav-item has-treeview">
             <a href="#" class="nav-link">
                 <i class="nav-icon fas fa-list-alt"></i>
                 <p>
                     Tipos de productos
                     <i class="right fas fa-angle-left"></i>
                 </p>
             </a>
             <ul class="nav nav-treeview">
                 <li class="nav-item">
                     <a href="{{ route('tipo.index')}}" class="nav-link">
                         <i class="far fa-circle nav-icon"></i>
                         <p>Tipos de productos</p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="{{ route('tipo.create') }}" class="nav-link">
                         <i class="far fa-circle nav-icon"></i>
                         <p>Crear tipo de producto</p>
                     </a>
                 </li>
             </ul>
         </li>

         <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                  Colores
                  <i class="right fas fa-angle-left"></i>
              </p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                  <a href="{{ route('color.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Listado de colores</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('color.create') }}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Crear color</p>
                  </a>
              </li>
          </ul>
      </li>


         <!-- Clientes -->
         <li class="nav-item has-treeview">
             <a href="#" class="nav-link">
                 <i class="nav-icon fas fa-list-alt"></i>
                 <p>
                     Clientes
                     <i class="right fas fa-angle-left"></i>
                 </p>
             </a>
             <ul class="nav nav-treeview">
                 <li class="nav-item">
                     <a href="{{ route('cliente.index')}}" class="nav-link">
                         <i class="far fa-circle nav-icon"></i>
                         <p>Listado de Clientes</p>
                     </a>
                 </li>
             </ul>
         </li>

          <!-- Productos -->
          <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-list-alt"></i>
                  <p>
                      Productos
                      <i class="right fas fa-angle-left"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ route('product.index')}}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Listado de Productos</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('product.create')}}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Crear producto</p>
                      </a>
                  </li>
              </ul>
          </li>

          <!-- Ventas -->
          <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-list-alt"></i>
                  <p>
                      Ventas
                      <i class="right fas fa-angle-left"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ route('venta.index') }}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Listado de Ventas</p>
                      </a>
                  </li>

              </ul>
          </li>

         <!-- Pedidos -->
         <li class="nav-item has-treeview">
             <a href="#" class="nav-link">
                 <i class="nav-icon fas fa-list-alt"></i>
                 <p>
                     Pedidos
                     <i class="right fas fa-angle-left"></i>
                 </p>
             </a>
             <ul class="nav nav-treeview">
                 <li class="nav-item">
                     <a href="{{ route('admin.pedidos.index')}}" class="nav-link">
                         <i class="far fa-circle nav-icon"></i>
                         <p>Listado de Pedidos</p>
                     </a>
                 </li>
             </ul>
         </li>

         <!-- Pagos -->
         <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>
                    Pagos
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                <a href="{{ route('payments.index')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Listado de Pagos</p>
                    </a>
                </li>
            </ul>
          </li>

        <!-- Devoluciones -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                  Devoluciones
                  <i class="right fas fa-angle-left"></i>
              </p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.devolucion.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Listado de devoluciones</p>
                </a>
              </li>
          </ul>
        </li>

          <!-- Proveedores -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>
                    Proveedores
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('proveedor.index')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Listado de Proveedores</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('proveedor.create')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Crear proveedor</p>
                    </a>
                </li>

            </ul>
        </li>

        <!-- Stock -->
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>
                    Stocks
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('stock.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Stock de Productos</p>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Informes -->
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>
                    Informes
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('informes.ventas') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Informe de ventas</p>
                    </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('informes.pagos') }}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Informe de pagos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('informes.saldos')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Informe de saldos</p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('informes.productos') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Productos más vendidos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('informes.clientes') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Clientes que más compran</p>
                    </a>
                </li>
            </ul>
        </li>
         <!-- Informes -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                  Chats
                  <i class="right fas fa-angle-left"></i>
              </p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item">
                  <a href="{{ route('chats.admin') }}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Ver chats</p>
                  </a>
              </li>
          </ul>
        </li>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>@yield('titulo')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin')}}">Inicio</a></li>
              @yield('breadcrumb')
            </ol>
            
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      @if(session('message'))

      <div class="alert alert-{{ session('message')[0] }} alert-dismissible fade show" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
          <p>{{ session('message')[1] }}</p>
      </div>

      @endif

      @yield('content')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.2-pre
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
{{-- <!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('adminlte/dist/js/demo.js') }}"></script>
{{-- <script src="{{ asset('js/bootstrap-select.min.js') }}"></script> --}}
{{-- <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script> --}}

{{-- <script src="{{ asset('js/plugins.js') }}" defer></script> --}}
<script src="{{ asset('js/app_admin.js') }}" defer></script>

{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}

@yield('scripts')

</body>
</html>
