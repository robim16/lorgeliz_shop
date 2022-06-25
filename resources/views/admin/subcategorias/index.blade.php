@extends('layouts.admin')


@section('titulo', 'Administración de Subcategorías')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')


<div id="index" class="row">

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sección de subcategorías</h3>

                <div class="card-tools">

                    <form>
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="nombre" class="form-control float-right" placeholder="Buscar"
                            value="{{ request()->get('nombre') }}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <a class="m-2 float-right btn btn-primary" href="{{ route('subcategory.create') }}">Crear</a>
                <table class="table table-head-fixed">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th colspan="3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($subcategorias as $subcategoria)

                        <tr>
                            <td> {{$subcategoria->id }} </td>
                            <td> {{$subcategoria->nombre }} </td>
                            <td> {{$subcategoria->descripcion }} </td>
                            <td> {{$subcategoria->categoria->nombre }} </td>
                            <td> <a class="btn btn-primary btn-sm btn-icon" href="{{ route('subcategory.show', $subcategoria->slug) }}"><i class="fas fa-eye"></i></a></td>

                            <td> 
                                <a class="btn btn-success btn-sm btn-icon" href="{{ route('subcategory.edit', $subcategoria->slug) }}"><i class="fas fa-pen"></i></a>
                            </td>

                            <td>
                                @include('admin.subcategorias.delete')
                            </td>

                        </tr>
                        @endforeach


                    </tbody>
                </table>
                {{ $subcategorias->appends($_GET)->links() }}
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- /.row -->

@endsection
