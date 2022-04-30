
@extends('layouts.admin')


@section('titulo', 'Devoluciones de productos')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Información de la solicitud</h3>
    
                            <div class="card-tools">
    
                            </div>
                        </div>
                        <!-- /.card-header -->
    
                        <div class="card-body table-responsive p-0">
    
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Venta</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Estado de la solicitud</th>
                                    </tr>
    
                                </thead>
    
                                <tbody>
                                    <tr>
                                        {{-- <td>{{ date('d/m/Y h:i:s A', strtotime($producto_devolucion[0]->fecha)) }}</td>
                                        <td><a href="{{ route('venta.show', $producto_devolucion[0]->venta)}}"
                                                class="">{{ $producto_devolucion[0]->venta }}</a>
                                        </td>
                                        <td><a href="{{ route('cliente.show', $producto_devolucion[0]->cliente)}}"
                                            class="">{{ $producto_devolucion[0]->nombres}} {{$producto_devolucion[0]->apellidos}}</a>
                                        </td>
                                        <td><span class="badge badge-success">
                                            @if ($producto_devolucion[0]->estado == 1 )
                                            {{ "pendiente" }}
                                            @endif
                                            @if ($producto_devolucion[0]->estado == 2)
                                            {{ "en proceso" }}
                                            @endif
                                            @if ($producto_devolucion[0]->estado == 3)
                                            {{ "rechazada" }}
                                            @endif
                                            @if ($producto_devolucion[0]->estado == 4)
                                            {{ "completada" }}
                                            @endif
                                            </span>
                                        </td> --}}

                                        <td>{{ date('d/m/Y h:i:s A', strtotime($producto_devolucion[0]->fecha)) }}</td>
                                        <td><a href="{{ route('venta.show', $producto_devolucion[0]->venta_id)}}"
                                                class="">{{ $producto_devolucion[0]->venta_id }}</a>
                                        </td>
                                        <td><a href="{{ route('cliente.show', $producto_devolucion[0]->venta->cliente->id)}}"
                                            class="">{{ $producto_devolucion[0]->venta->cliente->user->nombres}} {{$producto_devolucion[0]->venta->cliente->user->apellidos}}</a>
                                        </td>
                                        <td><span class="badge badge-success">
                                            @if ($producto_devolucion[0]->estado == 1 )
                                            {{ "pendiente" }}
                                            @endif
                                            @if ($producto_devolucion[0]->estado == 2)
                                            {{ "en proceso" }}
                                            @endif
                                            @if ($producto_devolucion[0]->estado == 3)
                                            {{ "rechazada" }}
                                            @endif
                                            @if ($producto_devolucion[0]->estado == 4)
                                            {{ "completada" }}
                                            @endif
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
    
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Productos que se enviaron para cambio</h3>

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
                                        <th scope="col">Id</th>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Imagen</th>
                                        <th scope="col">Talla</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Precio de venta</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($producto_devolucion as $producto)

                                    <tr>
                                        <td>{{ $producto->productoReferencia->colorProducto->producto->id }}</td>
                                        <td>{{ $producto->productoReferencia->colorProducto->producto->nombre }}</td>
                                        <td>
                                            <a href="{{ route('productos.show', $producto->productoReferencia->colorProducto->slug) }}">
                                                <img src="{{ url('storage/' . $producto->productoReferencia->colorProducto->imagenes[0]->url) }}" alt="" style="height: 50px; width: 50px;" class="rounded-circle">
                                            </a>
                                        </td>
                                        <td>{{ $producto->productoReferencia->talla->nombre }}</td>
                                        <td>{{ $producto->productoReferencia->colorProducto->color->nombre }}</td>
                                        <td>{{ $producto->cantidad }}</td>

                                        @php
                                           $pventa = $producto->venta->productoVentas;
                                            $precio = 0;
                                            
                                            foreach ($pventa as $key => $pv) {
                                                if ($pv) {
                                                    $pv->producto_referencia_id == $producto->productoReferencia->id;
                                                    $precio = $pv->precio_venta;
                                                }
                                            } 
                                            
                                        @endphp
                                        <td>${{floatval($precio)}}</td>
                                        <td>${{floatval($precio * $producto->cantidad)}}</td>
                                    </tr>
                                        
                                    @endforeach
                                    
                                </tbody>

                            </table>
                           {{-- $productos->appends($_GET)->links() --}} 
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>

    </div>

@endsection


