@extends('layouts.admin')


@section('titulo', 'Administración de Categorías')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')


<div id="index" class="row">

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sección de categorías</h3>

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
                <a class=" m-2 float-right btn btn-primary" href="{{ route('category.create') }}">Crear</a>
                <table class="table table-head-fixed">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th colspan="3"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($categorias as $categoria)

                        <tr>
                            <td> {{$categoria->id }} </td>
                            <td> {{$categoria->nombre }} </td>
                            <td> {{$categoria->descripcion }} </td>


                            <td> <a class="btn btn-primary" href="{{ route('category.show', $categoria->slug) }}"><i class="fas fa-eye"></i></a></td>

                            <td> <a class="btn btn-success" href="{{ route('category.edit', $categoria->slug) }}"><i class="fas fa-pen"></i></a>
                            </td>

                            <td>@include('admin.categorias.delete')</td>

                        </tr>
                        @endforeach

                    </tbody>
                </table>
                {{ $categorias->appends($_GET)->links() }}
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- /.row -->

@endsection
