@extends('layouts.admin')


@section('titulo', 'Editar Configuración')

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-body row">
                <div class="col-5 text-center d-flex align-items-center justify-content-center">
                    <div class="">

                        <h2>{{ $configuracion->nombre }}<strong>Tienda virtual</strong></h2>
                        <p class="lead mb-5">{{ $configuracion->direccion }}<br>
                            Teléfono: {{ $configuracion->telefono }}
                        </p>
                    </div>
                </div>

            </div>
            <div class="col-7">
                <form action="{{ route('configuracion.update', $configuracion->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nit">Nit</label>
                        <input type="text" id="nit" name="nit" class="form-control"
                            value="{{ $configuracion->nit }}" />
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control"
                            value="{{ $configuracion->nombre }}" />
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" class="form-control"
                            value="{{ $configuracion->direccion }}" />
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" class="form-control"
                            value="{{ $configuracion->telefono }}" />
                    </div>
                    <div class="form-group">
                        <label for="email">E-Mail</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $configuracion->email }}" />
                    </div>
                    <div class="form-group">
                        <label for="costo_envio">Costo de envío</label>
                        <input type="costo_envio" id="costo_envio" name="costo_envio" class="form-control"
                            value="{{ $configuracion->costo_envio }}" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
        </div>

    </section>

@endsection
