@extends('layouts.admin')


@section('titulo', 'Inventario de Productos')

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('content')

    <div id="inventarios" class="content">
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
                                            <a href="" class="btn btn-success mx-1" @click.prevent="pdfInventarios">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <button type="button" id="new" class="m-2 float-right btn btn-primary"
                                data-toggle="modal" data-target="#modalStock" @click.prevent="reset"><i
                                    class="far fa-plus-square"></i>
                                Nuevo</button>
                            <table class="table table-head-fixed">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Imagen</th>
                                        <th scope="col">Talla</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col" colspan="2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <tr v-for="producto in arrayProductos" :key="producto.id">

                                        <td>@{{ producto.color_producto.producto.id }}</td>
                                        <td>@{{ producto.color_producto.producto.nombre }}</td>
                                        <td> <a :href="'../product/' + producto.color_producto.slug">
                                                <img :src="'../storage/' + producto.color_producto.imagenes[0].url"
                                                    alt="" style="height: 50px; width: 50px;" class="rounded-circle">
                                            </a>
                                        </td>
                                        <td>@{{ producto.talla.nombre }}</td>
                                        <td>@{{ producto.color_producto.color.nombre }}</td>
                                        <td>@{{ producto.stock }}</td>
                                        <td>
                                            <a href="" class="btn btn-success" data-toggle="modal" data-target="#modalStock" title="aumentar inventario"
                                                @click.prevent="selectProducto(producto, 1)">
                                                <i class="bi bi-bag-plus-fill"></i>
                                            </a>
                                            
                                            <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modalStock" title="disminuir inventario"
                                                @click.prevent="selectProducto(producto, 2)">
                                                <i class="bi bi-dash-circle-fill"></i>
                                            </a>
                                        </td> 
                                    </tr> 


                                </tbody>
                            </table>

                            {{-- <ul class="d-flex flex-row align-items-start justify-content-center">
                                <li class="" v-if="pagination.current_page > 1">
                                    <a href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li v-if="pagination.current_page < pagination.last_page">
                                    <a class="" href="#"
                                        @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                            </ul> --}}

                            <nav>
                                <ul class="pagination">

                                    <li class="page-item disabled" v-if="pagination.current_page > 1" aria-disabled="true" aria-label="&laquo; Anterior">
                                        <a class="page-link"
                                            href="#"
                                            aria-label="Anterior &lsaquo;" aria-hidden="true" @click.prevent="cambiarPagina(pagination.current_page - 1)">&lsaquo;</a>
                                    
                                    </li>

                                    <li  class="page-item" :class="[page == isActived ? 'active' : '']" v-for="page in pagesNumber" :key="page" aria-current="page">
                                        <a href="#" class="page-link" v-text="page" @click.prevent="cambiarPagina(page)"></a>
                                    </li>

                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link"
                                            href="#"
                                            rel="next" aria-label="Siguiente &raquo;" @click.prevent="cambiarPagina(pagination.current_page + 1)">&rsaquo;</a>
                                    </li>
                                </ul>
                            </nav>

                            {{-- {{ $productos->appends($_GET)->links() }} --}}
                        </div>

                    </div>

                </div>
            </div>

        </div>


        <div class="modal fade" id="modalStock" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-primary">
                        <h5 class="modal-title" id="appModalLabel"
                            v-text="operacion == 1 ? 'Aumentar inventario' : operacion == 2 ? 
                            'Disminuir inventario' : 'Crear inventario nuevo'">
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <form id='formStock' class="form-horizontal" action="{{ route('stock.store') }}" method="POST">
                            @csrf

                            <div class="alert alert-success alert-dismissible fade show" role="alert" v-show="alertShow">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <p>{{ 'Se ha actualizado el inventario del producto' }}</p>
                            </div>

                            <div class="form-group row">

                                <label class="col-md-3 form-control-label" for="text-input">Producto</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="operacion" v-model="operacion">
                                    <select name="producto_id" id="producto_id" class="form-control" v-model="producto"
                                        @change="getTallas">
                                        <option value="">Seleccione uno</option>
                                        @foreach (\App\Producto::pluck('nombre', 'id') as $id => $producto)
                                            <option value="{{ $id }}">
                                                {{ $producto }}
                                            </option>
                                        @endforeach
                                    </select>


                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                    <span class="text-danger">
                                        <strong id="producto_id-error"></strong>
                                    </span>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Talla</label>
                                <div class="col-md-9">

                                    <select name="talla_id" id="talla_id" class="form-control" v-model="talla"
                                        @change="getColores">
                                        <option value="0">Seleccione una talla</option>
                                        <option v-for="talla in arrayTallas" :key="talla.talla_id" :value="talla.talla_id"
                                            v-text="talla.nombre"></option>
                                    </select>

                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                    <span class="text-danger">
                                        <strong id="talla_id-error"></strong>
                                    </span>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Color</label>
                                <div class="col-md-9">

                                    <select name="color_id" id="color_id" class="form-control" v-model="color">
                                        <option value="0">Seleccione un color</option>
                                        <option v-for="color in arrayColores" :key="color.color_id"
                                            :value="color.color_id" v-text="color.nombre"></option>
                                    </select>


                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                    <span class="text-danger">
                                        <strong id="color_id-error"></strong>
                                    </span>


                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Cantidad</label>
                                <div class="col-md-9">
                                    <input type="number" id="cantidad" name="cantidad" class="form-control"
                                        placeholder="Cantidad" v-model="cantidad">
                                </div>

                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                <span class="text-danger">
                                    <strong id="cantidad-error"></strong>
                                </span>

                            </div>

                            <button type="submit" class="btn btn-primary float-left" id="aceptar"
                                @click.prevent="ingresarStock">Guardar <i class="bi bi-hdd"></i></button>
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

@section('scripts')


@endsection



{{-- @foreach ($productos as $producto)
    <tr>
        <td>{{ $producto->colorProducto->producto->id }}</td>
        <td>{{ $producto->colorProducto->producto->nombre }}</td>
        <td> <a href="{{ route('productos.show', $producto->colorProducto->slug) }}">
                <img src="{{ url('storage/' . $producto->colorProducto->imagenes[0]->url) }}"
                    alt="" style="height: 50px; width: 50px;" class="rounded-circle">
            </a>
        </td>
        <td>{{ $producto->talla->nombre }}</td>
        <td>{{ $producto->colorProducto->color->nombre }}</td>
        <td>{{ $producto->stock }}</td>
        <td>
            <a href="" class="btn btn-success" data-toggle="modal" data-target="#modalStock" title="aumentar inventario"
            @click.prevent="selectProducto({{$producto}}, 1)">
                <i class="bi bi-bag-plus-fill"></i></a>
                
            <a href="" class="btn btn-danger" data-toggle="modal" data-target="#modalStock" title="disminuir inventario"
            @click.prevent="selectProducto({{$producto}}, 2)">
                <i class="bi bi-dash-circle-fill"></i></a>
        </td>
    </tr>
@endforeach --}}
