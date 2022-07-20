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