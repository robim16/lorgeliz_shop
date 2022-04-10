@extends('layouts.admin')


@section('titulo', 'Informe de ventas')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('content')
<div id="informeventa">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Ventas mensuales</h3>

                            <div class="card-tools">
                                <form>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="busqueda" class="form-control float-right" placeholder="Buscar"
                                        value="{{ request()->get('busqueda') }}">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                        </div>
                                        <div class="input-group-append">
                                            <a href="" class="btn btn-success mx-1" @click.prevent="pdfInformeVentas"><i class="fas fa-print"></i></a>
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
                                        <th scope="col">Mes</th>
                                        <th scope="col">Ventas hechas</th>
                                        <th scope="col">Valor ventas</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($ventas as $venta)
                                    <tr>
                                        <td>
                                            @if ($venta->mes == 1)
                                            {{"Enero"}}
                                            @endif
                                            @if ($venta->mes == 2)
                                            {{"Febrero"}}
                                            @endif
                                            @if ($venta->mes == 3)
                                            {{"Marzo"}}
                                            @endif
                                            @if ($venta->mes == 4)
                                            {{"Abril"}}
                                            @endif
                                            @if ($venta->mes == 5)
                                            {{"Mayo"}}
                                            @endif
                                            @if ($venta->mes == 6)
                                            {{"Junio"}}
                                            @endif
                                            @if ($venta->mes == 7)
                                            {{"Julio"}}
                                            @endif
                                            @if ($venta->mes == 8)
                                            {{"Agosto"}}
                                            @endif
                                            @if ($venta->mes == 9)
                                            {{"Septiembre"}}
                                            @endif
                                            @if ($venta->mes == 10)
                                            {{"Octubre"}}
                                            @endif
                                            @if ($venta->mes == 11)
                                            {{"Noviembre"}}
                                            @endif
                                            @if ($venta->mes == 12)
                                            {{"Diciembre"}}
                                            @endif
                                        </td>
                                        <td>{{ $venta->cantidad }}</td>
                                        <td>${{ floatval($venta->total) }}</td>
                                        <td><a href="{{ route('listado.ventas', $venta->mes)}}" class="btn btn-primary"
                                                title="ver ventas">
                                                <i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            {{ $ventas->appends($_GET)->links() }}
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>

    </div>
</div>

@endsection
