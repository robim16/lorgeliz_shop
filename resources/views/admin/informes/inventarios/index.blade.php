@extends('layouts.admin')


@section('titulo', 'Inventario de Productos')

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
                            <h3 class="card-title mb-2">Información de inventario de productos</h3>

                            <div class="card-tools">
                                <form>
                                    <div class="input-group input-group-sm" style="width: 190px">

                                        <input type="text" name="busqueda" class="form-control float-right"
                                            placeholder="buscar" value="{{ request()->get('busqueda') }}">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-success"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                        <div class="input-group-append">
                                            <a href="" class="btn btn-success mx-1" @click.prevent="">
                                                <i class="fas fa-print"></i>
                                            </a>
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
                                        <th scope="col">Talla</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($productos as $producto)
                                        <tr>
                                            <td>{{ $producto->colorProducto->producto->id }}</td>
                                            <td>{{ $producto->colorProducto->producto->nombre }}</td>
                                            <td> 
                                                <a href="{{ route('productos.show', $producto->colorProducto->slug) }}">
                                                    <img src="{{ url('storage/' . $producto->colorProducto->imagenes[0]->url) }}"
                                                        alt="" style="height: 50px; width: 50px;"
                                                        class="rounded-circle">
                                                </a>
                                            </td>
                                            <td>{{ $producto->talla->nombre }}</td>
                                            <td>{{ $producto->colorProducto->color->nombre }}</td>
                                            <td>{{ $producto->stock }}</td>
                                            <td>
                                                <a href="" class="btn btn-primary btn-icon btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                            </td>
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

@endsection



@section('scripts')

   
@endsection
