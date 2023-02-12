@extends('layouts.admin')


@section('titulo', 'Información del Pedido')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')

<div class="content">
    <div id="imprimir_pedidos">
            <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Datos básicos</h3>

                            <div class="card-tools">
                                <div class="input-group-append">
                                    {{-- <a class="btn btn-success" href="" v-on:click.prevent="imprimir({{ $users[0]->pedido}})" title="imprimir"><i class="fa fa-print"></i></a> --}}
                                    <a class="btn btn-success" href="" @click.prevent="imprimir({{ $productos[0]->venta->pedido->id}})" title="imprimir"><i class="fa fa-print"></i></a>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body table-responsive p-0">

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col"># Pedido</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Departamento</th>
                                        <th scope="col">Municipio</th>
                                        <th scope="col">Dirección</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col">Email</th>
                                    </tr>

                                </thead>

                                <tbody>
                                    <tr>
                                        {{-- @foreach ($users as $user)
                                            <td>{{ $user->pedido }}</td>
                                            <td><a href="{{ route('cliente.show', $user->cliente)}}"
                                                title="ver cliente">{{ $user->nombres }} {{ $user->apellidos }}</a></td>
                                            <td>{{ date('d/m/Y h:i:s A', strtotime($user->fecha)) }}</td>
                                            <td>{{ $user->departamento }}</td>
                                            <td>{{ $user->municipio }}</td>
                                            <td>{{ $user->direccion }}</td>
                                            <td>{{ $user->telefono }}</td>
                                            <td>{{ $user->email }}</td>
                                        @endforeach --}}

                                       
                                        <td>{{ $productos[0]->venta->pedido->id }}</td>
                                        <td>
                                            @if ($productos[0]->venta->pedido->estado == 1)
                                                {{ "pendiente" }}
                                            @endif
        
                                            @if ($productos[0]->venta->pedido->estado == 2)
                                                {{ "en proceso"}}
                                            @endif
        
                                            @if ($productos[0]->venta->pedido->estado == 3)
                                                {{ "enviado"}}
                                            @endif
        
                                            @if ($productos[0]->venta->pedido->estado == 4)
                                                {{ "entregado"}}
                                            @endif

                                            @if ($productos[0]->venta->pedido->estado == 5)
                                                {{ "anulado"}}
                                            @endif

                                        </td>
                                        <td>
                                            <a href="{{ route('cliente.show', $productos[0]->venta->cliente->id)}}"
                                            title="ver cliente">{{$productos[0]->venta->cliente->user->nombres }} 
                                            {{ $productos[0]->venta->cliente->user->apellidos }}</a>
                                        </td>
                                        <td>{{ date('d/m/Y h:i:s A', strtotime($productos[0]->venta->pedido->fecha)) }}</td>
                                        <td>{{$productos[0]->venta->cliente->user->departamento }}</td>
                                        <td>{{ $productos[0]->venta->cliente->user->municipio }}</td>
                                        <td>{{ $productos[0]->venta->cliente->user->direccion }}</td>
                                        <td>{{ $productos[0]->venta->cliente->user->telefono }}</td>
                                        <td>{{ $productos[0]->venta->cliente->user->email }}</td>
                                    
                                        
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Productos del pedido</h3>

                            <div class="card-tools">
                                <form>
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="keyword" class="form-control float-right"
                                            placeholder="buscar" value="{{ request()->get('keyword') }}">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Referencia</th>
                                        <th scope="col">Imagen</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Talla</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Precio unitario</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($productos as $producto)

                                    {{-- <tr>
                                        <td>{{ $producto->id }}</td>

                                        <td> 
                                           
                                            <a href="{{ route('producto.show', $producto->slug) }}" title="ver producto">
                                                <img src="{{ url('storage/' . $producto->imagen) }}" alt="" style="height: 50px; width: 50px;" class="rounded-circle">
                                            </a>
                                        </td>

                                        <td><a href="{{ route('producto.show', $producto->slug) }}"
                                             title="ver producto" style="color: black">{{ $producto->nombre }}
                                            </a>
                                        </td>
                                        <td>{{ $producto->talla }}</td>
                                        <td>{{ $producto->color }}</td>
                                        <td>{{ $producto->cantidad }}</td>
                                        <td>${{ floatval($producto->precio_actual) }}</td>
                                        <td>${{ floatval($producto->precio_actual * $producto->cantidad) }}</td>
                                        <td><a href="{{ route('producto.show', $producto->slug)}}"
                                                class="btn btn-primary" title="ver producto">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>

                                    </tr> --}}

                                    <tr>
                                        <td>{{ $producto->id }}</td>

                                        <td> 
                                           
                                            <a href="{{ route('productos.show', $producto->productoReferencia->colorProducto->slug) }}" title="ver producto">
                                                <img src="{{ url('storage/' . $producto->productoReferencia->colorProducto->imagenes[0]->url) }}" alt="" style="height: 50px; width: 50px;" class="rounded-circle">
                                            </a>
                                        </td>

                                        <td><a href="{{ route('productos.show', $producto->productoReferencia->colorProducto->slug) }}"
                                             title="ver producto" style="color: black">{{ $producto->productoReferencia->colorProducto->producto->nombre }}
                                            </a>
                                        </td>
                                        <td>{{ $producto->productoReferencia->talla->nombre }}</td>
                                        <td>{{ $producto->productoReferencia->colorProducto->color->nombre }}</td>
                                        <td>{{ $producto->cantidad }}</td>
                                        {{-- <td>${{ floatval($producto->productoReferencia->colorProducto->producto->precio_actual) }}</td> --}}
                                        <td>${{ floatval($producto->precio_venta) }}</td>
                                        {{-- <td>${{ floatval($producto->productoReferencia->colorProducto->producto->precio_actual * $producto->cantidad) }}</td> --}}
                                        <td>${{ floatval($producto->precio_venta * $producto->cantidad) }}</td>
                                        <td><a href="{{ route('productos.show', $producto->productoReferencia->colorProducto->slug)}}"
                                                class="btn btn-primary btn-sm btn-icon" title="ver producto">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="7" class="text-right">Subtotal:</td>
                                        <td colspan="2" class="text-left">${{ floatval($producto->venta->subtotal) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right">Envío:</td>
                                        <td colspan="2" class="text-left">${{ floatval($producto->venta->envio) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right">Total pedido:</td>
                                        {{-- <td colspan="2" class="text-left">${{ floatval($producto->valor) }}</td> --}}
                                        <td colspan="2" class="text-left">${{ floatval($producto->venta->valor) }}</td>
                                    </tr>

                                </tfoot>
                            </table>
                            {{-- {{ $productos->appends($_GET)->links() }} --}}
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
    </div>

</div>

@endsection
