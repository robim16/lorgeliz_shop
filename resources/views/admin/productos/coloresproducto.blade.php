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

<div id="product_color" class="container">
    <div class="row">
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
                    {{-- <a class=" m-2 float-right btn btn-primary" href="{{ route('product.color', $productos[0]->producto_id)}}"><i class="fas fa-plus"></i> Crear</a> --}}
                    <button type="button" id="new" class="m-2 float-right btn btn-primary"
                        data-toggle="modal" data-target="#modalColor">
                        <i class="far fa-plus-square mx-1"></i>Crear
                    </button>
                    <table class="table1 table-head-fixed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Precio</th>
                                <th>Color</th>
                                <th>Slider</th>
                                <th colspan="5"></th>
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
                                <td> ${{$producto->producto->precio_actual }} </td>
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

                                <td>
                                    <a class="btn btn-primary btn-sm btn-icon" href="{{ route('stock.index', $producto->id) }}" title="inventario">
                                        <i class="fas fa-dolly"></i>
                                    </a>
                                <td>
    
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
    
    <div class="modal fade" id="modalColor" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-primary">
                    <h5 class="modal-title" id="appModalLabel">
                       Nuevo color del producto
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
    
                    <form id='formColor' class="form-horizontal" action="{{ route('product.store-color') }}" method="POST">
                        @csrf
    
                        <div class="alert alert-success alert-dismissible fade show" role="alert" v-show="alertShow">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <p>{{ 'Se ha creado el producto' }}</p>
                        </div>
    
                        <div class="form-group row">
    
                            <label class="col-md-3 form-control-label" for="text-input">Color</label>
                            <div class="col-md-9">
                                <select name="color_id" id="color_id" class="form-control" v-model="color">
                                    <option value="">Seleccione uno</option>
                                    @foreach (\App\Color::pluck('nombre', 'id') as $id => $color)
                                        <option value="{{ $id }}">
                                            {{ $color }}
                                        </option>
                                    @endforeach
                                </select>
    
                                {{-- <input type="hidden" name="producto" v-model="producto" value="{{$producto->producto->id}}"> --}}
    
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                <span class="text-danger">
                                    <strong id="color_id-error"></strong>
                                </span>
    
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="imagenes">Añadir imágenes</label>
                            <div class="col-md-9">
    
                                <input type="file" class="form-control-file" name="imagenes[]" id="imagenes" multiple
                                    accept="image/*" @change="selectFiles">
    
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                <span class="text-danger">
                                    <strong id="imagenes-error"></strong>
                                </span>
    
                            </div>
                        </div>
    
    
                        <button type="submit" class="btn btn-primary float-left" id="aceptar"
                            @click.prevent="create_color({{$producto->producto->id}})">Guardar <i class="bi bi-hdd"></i></button>
                        <button type="reset" class="btn btn-danger float-right" id="rechazar">Cancelar</button>
    
                    </form>
    
                </div>
    
                <div class="modal-footer">
    
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
