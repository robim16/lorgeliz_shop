@extends('layouts.admin')


@section('titulo', 'Informe de saldos')

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('content')

    <div id="informeproducto">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title mb-2">Facturas con saldos del cliente</h3>

                                <div class="card-tools">
                                    <form novalidate>
                                        <div class="input-group input-group-sm">

                                            <input type="text" name="busqueda" class="form-control float-right"
                                                placeholder="Buscar" value="{{ request()->get('busqueda') }}">

                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <a href="" class="btn btn-warning mx-1"><i class="fas fa-print"></i></a>
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
                                            <th scope="col">Cliente</th>
                                            <th scope="col">Venta</th>
                                            <th scope="col">Factura</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Valor</th>
                                            <th scope="col">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @php
                                            $deuda = 0;    
                                        @endphp
                                        
                                        @foreach ($saldos_pendientes as $saldo_pendiente)
                                            <tr>

                                                <td>
                                                    <a href="{{ route('cliente.show', $saldo_pendiente->cliente->id) }}">
                                                        {{ $saldo_pendiente->cliente->user->nombres }}
                                                        {{ $saldo_pendiente->cliente->user->apellidos }}
                                                    </a>
                                                </td>
                                                <td><a href="{{ route('venta.show', $saldo_pendiente->id) }}"
                                                        title="ver venta">{{ $saldo_pendiente->id }}</a></td>
                                                <td>{{ $saldo_pendiente->factura->prefijo }}
                                                    {{ $saldo_pendiente->factura->consecutivo }}
                                                </td>
                                                <td>{{ date('d/m/Y h:i:s A', strtotime($saldo_pendiente->fecha)) }}</td>
                                                <td>${{ floatval($saldo_pendiente->valor) }}</td>
                                                <td>${{ floatval($saldo_pendiente->saldo) }}</td>

                                                @php
                                                    $deuda += $saldo_pendiente->saldo;
                                                @endphp
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total deuda:</th>
                                            <th>${{ floatval($deuda) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                {{ $saldos_pendientes->appends($_GET)->links() }}
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
