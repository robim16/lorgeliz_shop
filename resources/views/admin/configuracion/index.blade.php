@extends('layouts.admin')


@section('titulo', 'Configuración')

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')


    <div id="index" class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sección de Configuración</h3>

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
                    {{-- <a class=" m-2 float-right btn btn-primary" href="{{ route('color.create') }}">Crear</a> --}}
                    <table class="table table-head-fixed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Costo de envío</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>

                                <td>{{ $configuracion->id }}</td>
                                <td>{{ $configuracion->nombre }}</td>
                                <td>{{ $configuracion->direccion }}</td>
                                <td>{{ $configuracion->telefono }}</td>
                                <td>{{ $configuracion->email }}</td>
                                <td>${{ floatval($configuracion->costo_envio) }}</td>
                                <td>
                                    <a href="{{ route('configuracion.edit', $configuracion->id)}}" class="btn btn-success btn-icon btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->

@endsection
