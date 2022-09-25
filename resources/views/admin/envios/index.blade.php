@extends('layouts.admin')


@section('titulo', 'Administración de Envios')

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')


<div id="index" class="row">

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sección de guías y envíos</h3>

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
                <a class=" m-2 float-right btn btn-primary" href="{{ route('envios.create') }}">Crear</a>
                <table class="table table-head-fixed">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th># guía</th>
                            <th>Dirección</th>
                            <th>Departamento</th>
                            <th>Municipio</th>
                            <th>Transportadora</th>
                            <th>Fecha</th>
                            <th>Costo</th>
                            <th>Cliente</th>
                            <th>Venta</th>
                            {{-- <th>Comentarios</th> --}}
                            <th>Estado</th>
                            <th colspan="3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($envios as $envio)

                        <tr>
                            <td> {{ $envio->id }} </td>
                            <td> {{ $envio->guia }} </td>
                            <td> {{ $envio->direccion }} </td>
                            <td> {{ $envio->departamento }} </td>
                            <td> {{ $envio->municipio }} </td>
                            <td> {{ $envio->transportadora }} </td>
                            <td> {{  date('d/m/Y h:i:s A', strtotime($envio->fecha)) }} </td>
                            <td> {{ $envio->costo }} </td>
                            <td> {{ $envio->venta->cliente->user->nombres }} {{ $envio->venta->cliente->user->apellidos }} </td>
                            <td> {{ $envio->venta_id }} </td>
                            {{-- <td> {{ $envio->comentarios }} </td> --}}
                            <td> {{ $envio->estado }} </td>
                            <td>
                                <a href="{{ route('envios.show', $envio->id)}}"
                                    class=" btn btn-primary btn-icon btn-sm" title="ver">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('envios.edit', $envio->id)}}"
                                    class=" btn btn-success btn-icon btn-sm" title="editar">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td>@include('admin.envios.delete')</td>

                        </tr>
                        @endforeach


                    </tbody>
                </table>
                {{-- {{ $envios->appends($_GET)->links() }} --}}
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- /.row -->

@endsection
