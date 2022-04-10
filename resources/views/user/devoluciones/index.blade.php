
@extends('layouts.account')

@section('title')
<h1 class="m-0 text-dark"> Devoluciones </h1>
@endsection

@section('breadcumb')
<li class="breadcrumb-item">Mis Devoluciones</li>
@endsection


@section('content')
    <div class="content">
        <div class="container">
            @if (count($productos) > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Productos que enviaste para cambio</h3>

                            <div class="card-tools">
                                <form>
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="busqueda" class="form-control float-right"
                                            placeholder="buscar pedido" value="{{ request()->get('busqueda') }}">

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
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Pedido</th>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Imagen</th>
                                        <th scope="col">Talla</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    {{-- @foreach ($productos as $producto)

                                    <tr>
                                        <td>{{ date('d/m/Y h:i A', strtotime($producto->fecha)) }}</td>
                                        <td><a href="{{ route('pedidos.show', $producto->pedido)}}"
                                                class="" title="ver pedido">{{ $producto->pedido }}</a>
                                        </td>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>
                                            <a href="{{ route('producto.show', $producto->slug) }}">
                                            <img src="{{ url('storage/' . $producto->imagen) }}" alt="" style="height: 50px; width: 50px;" class="rounded-circle">
                                            </a>
                                        </td>
                                        <td>{{ $producto->talla }}</td>
                                        <td>{{ $producto->color  }}</td>
                                        <td>{{ $producto->cantidad }}</td>
                                       
                                        <td><a href="{{ route('devolucion.show', $producto->id) }}" class="btn btn-primary" title="ver solicitud"> <i class="fas fa-eye"></i></a></td>
                                    </tr>
                                        
                                    @endforeach --}}

                                    @foreach ($productos as $producto)

                                    <tr>
                                        <td>{{ date('d/m/Y h:i A', strtotime($producto->fecha)) }}</td>
                                        <td><a href="{{ route('pedidos.show', $producto->venta->pedido->id)}}"
                                                class="" title="ver pedido">{{ $producto->venta->pedido->id }}</a>
                                        </td>
                                        <td>{{ $producto->productoReferencia->colorProducto->producto->nombre }}</td>
                                        <td>
                                            <a href="{{ route('productos.show', $producto->productoReferencia->colorProducto->slug) }}">
                                            <img src="{{ url('storage/' . $producto->productoReferencia->colorProducto->imagenes[0]->url) }}" alt="" style="height: 50px; width: 50px;" class="rounded-circle">
                                            </a>
                                        </td>
                                        <td>{{ $producto->productoReferencia->talla->nombre }}</td>
                                        <td>{{ $producto->productoReferencia->colorProducto->color->nombre  }}</td>
                                        <td>{{ $producto->cantidad }}</td>
                                       
                                        <td><a href="{{ route('devolucion.show', $producto->id) }}" class="btn btn-primary" title="ver solicitud"> <i class="fas fa-eye"></i></a></td>
                                    </tr>
                                        
                                    @endforeach
                                </tbody>
                            </table>
                           {{ $productos->appends($_GET)->links() }} 
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
            @else
            <div class="alert alert-info pt-5 col-md-7 text-center m-auto">
                <h4 class="alert-heading">{{ __("No se encontraron resultados") }}</h4>
            </div>
            @endif
        </div>

    </div>

@endsection


