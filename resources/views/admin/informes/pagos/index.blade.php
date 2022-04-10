@extends('layouts.admin')


@section('titulo', 'Informe de pagos')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('content')
<div id="">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Pagos mensuales</h3>

                            <div class="card-tools" id="app">
                                <form>
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="busqueda" class="form-control float-right" placeholder="Buscar"
                                        value="{{ request()->get('busqueda') }}">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                        </div>

                                        <pagos></pagos>
                                        
                                        {{-- <div class="input-group-append">
                                            <a href="" class="btn btn-success mx-1" v-on:click.prevent="pdfInformePagos()"><i class="fas fa-print"></i></a>
                                        </div> --}}
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
                                        <th scope="col">Pagos recibidos</th>
                                        <th scope="col">Valor pagos</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($pagos as $pago)
                                    <tr>
                                        <td>
                                            @if ($pago->mes == 1)
                                            {{"Enero"}}
                                            @endif
                                            @if ($pago->mes == 2)
                                            {{"Febrero"}}
                                            @endif
                                            @if ($pago->mes == 3)
                                            {{"Marzo"}}
                                            @endif
                                            @if ($pago->mes == 4)
                                            {{"Abril"}}
                                            @endif
                                            @if ($pago->mes == 5)
                                            {{"Mayo"}}
                                            @endif
                                            @if ($pago->mes == 6)
                                            {{"Junio"}}
                                            @endif
                                            @if ($pago->mes == 7)
                                            {{"Julio"}}
                                            @endif
                                            @if ($pago->mes == 8)
                                            {{"Agosto"}}
                                            @endif
                                            @if ($pago->mes == 9)
                                            {{"Septiembre"}}
                                            @endif
                                            @if ($pago->mes == 10)
                                            {{"Octubre"}}
                                            @endif
                                            @if ($pago->mes == 11)
                                            {{"Noviembre"}}
                                            @endif
                                            @if ($pago->mes == 12)
                                            {{"Diciembre"}}
                                            @endif
                                        </td>
                                        <td>{{ $pago->cantidad }}</td>
                                        <td>${{ floatval($pago->total) }}</td>
                                        <td><a href="{{ route('listado.pagos', $pago->mes)}}" class="btn btn-primary"
                                                title="ver pagos">
                                                <i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            {{ $pagos->appends($_GET)->links() }}
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
