@extends('layouts.admin')


@section('titulo', 'Administración de productos')

@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="{{ route('product.index') }}">Productos</a></li>
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
            {{-- <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sección de productos</h3>

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

                <div class="card-body table-responsive p-0">
                    <a class="m-2 float-right btn btn-primary" href="{{ route('product.create') }}"> <i
                            class="fas fa-plus"></i> Crear</a>
                    <table class="table1 table-head-fixed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Marca</th>
                                
                                <th>Slider</th>
                                <th colspan="3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($productos as $producto)
                                <tr>
                                    <td> {{ $producto->id }} </td>
                                    <td>
                                        
                                        @if ($producto->colors_count > 0)
                                           
                                            <img src="{{ url('storage/' . $producto->colors[0]->pivot->imagenes[0]->url) }}"
                                                alt="" style="height: 50px; width: 50px;" class="rounded-circle">
                                        @endif
                                    </td>
                                    <td> {{ $producto->nombre }} </td>
                                    <td> {!! Str::limit($producto->descripcion_corta, 30) !!} </td>
                                    <td> {{ $producto->marca }} </td>
                                    
                                    <td> {{ $producto->slider_principal }} </td>

                                    @if ($producto->colors_count > 0)
                                        <td>
                                            <a class="btn btn-default btn-sm btn-icon"
                                                href="{{ route('product.show', $producto->id) }}" title="ver producto">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>

                                        <td>
                                            <a class="btn btn-info btn-sm btn-icon"
                                                href="{{ route('product.edit', $producto->id) }}" title="editar">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        </td>

                                        <td>
                                            <a href="{{ route('product.colors', $producto->id) }}"
                                                class="btn btn-success btn-sm btn-icon" title="ver todos los colores">
                                                <i class="bi bi-palette"></i>
                                            </a>
                                        </td>
                                    @endif

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $productos->appends($_GET)->links() }}
                </div>
               
            </div> --}}

            @livewire('admin.products-component')
           
        </div>
    </div>
    <!-- /.row -->

@endsection
