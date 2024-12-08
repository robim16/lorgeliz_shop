 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin') }}" class="brand-link">
      <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"
          alt="lorgeliz tienda"
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
                <i class="nav-icon fas fa-paste"></i>
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
                <i class="nav-icon fas fa-users"></i>
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
                <i class="nav-icon fas fa-box-open"></i>
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
                <i class="nav-icon fas fa-coins"></i>
                 
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
                <i class="nav-icon fas fa-shopping-cart"></i>
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

        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-shipping-fast"></i>
                <p>
                    Envios
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('envios.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ver envios/guías</p>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Pagos -->
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-donate"></i>
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
                <i class="nav-icon fas fa-recycle"></i>
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
                <i class="nav-icon fas fa-warehouse"></i>
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
                <i class="nav-icon fas fa-dolly-flatbed"></i>
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
                <i class="nav-icon fas fa-file-pdf"></i>
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
                <i class="nav-icon fas fa-comments"></i>
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
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cogs"></i>
                <p>
                    Configuración
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('configuracion.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ver configuración</p>
                    </a>
                </li>
            </ul>
        </li>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>