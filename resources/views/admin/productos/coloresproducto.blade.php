@extends('layouts.admin')


@section('titulo', 'Administración de productos')

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')
<style type="text/css">
    .table1 {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        text-align: center;
    }

    .table1 td,
    .table1 th {
        padding: .75rem;
        vertical-align: center;
        border-top: 1px solid #dee2e6;
    }

</style>


<div id="product" class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Colores del producto</h3>

                <div class="card-tools">

                    <form>
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="busqueda" class="form-control float-right" placeholder="Buscar"
                                value="{{ request()->get('busqueda') }}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
            <a class=" m-2 float-right btn btn-primary" href="{{ route('product.color', $productos[0]->producto_id)}}"><i class="fas fa-plus"></i> Crear</a>
                <table class="table1 table-head-fixed">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Color</th>
                            <th>Slider</th>
                            <th colspan="4"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($productos as $producto)
                        <tr>

                            <td> {{$producto->producto->id }} </td>
                            <td>
                                <img src="{{ url('storage/' . $producto->imagenes[0]->url) }}" alt="" style="height: 50px; width: 50px;" class="rounded-circle">
                            </td>
                            <td> {{$producto->producto->nombre }} </td>
                            <td> {{$producto->producto->marca }} </td>
                            <td> {{$producto->color->nombre}}</td>
                            <td> {{$producto->producto->slider_principal }} </td>

                            <td> 
                                <a class="btn btn-default btn-sm btn-icon" href="{{ route('product.showColor', $producto->slug) }}" title="ver producto">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>

                            <td> 
                                <a class="btn btn-info btn-sm btn-icon" href="{{ route('product.editColor', $producto->slug) }}" title="editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </td>

                            <td>@include('admin.productos.delete')</td>

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

@endsection
