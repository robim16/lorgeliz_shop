
@extends('layouts.admin')


@section('titulo', 'Informe de productos más vendidos')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('content')

<div id="informeproducto">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Productos más vendidos</h3>

                            <div class="card-tools">
                                <form novalidate>
                                    <div class="input-group input-group-sm">
                                        {{--<input type="date" name="fecha_de" id="fecha_de" required class="form-control mx-1">
                                        <input type="date" name="fecha_a" id="fecha_a" required class="form-control mx-1">--}}
                                        <input type="text" name="busqueda" class="form-control float-right" placeholder="Buscar"
                                        value="{{ request()->get('busqueda') }}">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="" class="btn btn-warning mx-1" @click.prevent="pdfInformeProductos"><i class="fas fa-print"></i></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-head-fixed">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Imagen</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Talla</th>
                                        <th scope="col">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    {{-- @foreach ($productos as $producto)
                                    <tr>

                                    <td>{{ $producto->codigo }}</td>
                                        <td>{{ $producto->nombre }}</td>
                                        <td> <a href="">
                                                @foreach(\App\Imagene::where('imageable_type', 'App\ColorProducto')
                                                ->where('imageable_id', $producto->cop)->pluck('url', 'id')->take(1) as $id => $imagen)    
                                                <img src="{{ url('storage/' . $imagen) }}" alt="" style="height: 50px; width: 50px;" class="rounded-circle">
                                                @endforeach
                                            </a>
                                        </td>
                                        <td>{{ $producto->color }}</td>
                                        <td>{{ $producto->talla }}</td>
                                        <td>{{ $producto->cantidad }}</td>
                                    </tr>
                                    @endforeach --}}

                                    @foreach ($productos as $producto)
                                    <tr>

                                    <td>{{ $producto->productoReferencia->colorProducto->producto->id }}</td>
                                        <td>{{ $producto->productoReferencia->colorProducto->producto->nombre }}</td>
                                        <td> <a href="">
                                                <img src="{{ url('storage/' .$producto->productoReferencia->colorProducto->imagenes[0]->url) }}" alt="" style="height: 50px; width: 50px;" class="rounded-circle">
                                            </a>
                                        </td>
                                        <td>{{ $producto->productoReferencia->colorProducto->color->nombre }}</td>
                                        <td>{{ $producto->productoReferencia->talla->nombre }}</td>
                                        <td>{{ $producto->cantidad }}</td>
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
        </div>

    </div>
</div>


@endsection




