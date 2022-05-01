
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
                            <h3 class="card-title mb-2">Facturas con saldos de los clientes</h3>

                            <div class="card-tools">
                                <form novalidate>
                                    <div class="input-group input-group-sm">
                                       
                                        <input type="text" name="busqueda" class="form-control float-right" placeholder="Buscar"
                                        value="{{ request()->get('busqueda') }}">

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
                                        <th scope="col">Facturas pendientes</th>
                                        <th scope="col">Total deuda</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach ($saldos_pendientes as $saldo_pendiente)
                                        <tr>

                                            <td>
                                                <a href="{{ route('cliente.show', $saldo_pendiente->cliente->id)}}">
                                                    {{ $saldo_pendiente->cliente->user->nombres }} 
                                                    {{ $saldo_pendiente->cliente->user->apellidos}}
                                                </a>
                                            </td>
                                            <td>{{ $saldo_pendiente->facturas }}</td>
                                            <td>${{ floatval($saldo_pendiente->saldos) }}</td>
                                            <td><a href="{{ route('informes.saldos.cliente', $saldo_pendiente->cliente->id)}}" class="btn btn-primary" title="ver"><i class="fa fa-eye"></i></a></td>
                                        </tr>
                                    @endforeach
                                        
                                </tbody>
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




