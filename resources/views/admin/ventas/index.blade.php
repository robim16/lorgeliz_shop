@extends('layouts.admin')


@section('titulo', 'Administración de Ventas')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')

<div id="listventas" class="row">

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sección de ventas</h3>

                <div class="card-tools">

                    <form>
                        
                        <div class="input-group input-group-sm" style="width: 290px;">
                            <div class="input-group-append">
                                <a href="" class="btn btn-success mx-2" @click.prevent="pdfListadoVentas">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>

                            <select name="estado" id="estado" class="form-control float-right" style="width: 44px;">
                                <option value="">estado</option>
                                <option value="1">pagadas</option>
                                <option value="2">con saldo</option>
                            </select>

                            <div class="input-group-append pr-1">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>

                            <input type="text" name="busqueda" class="form-control float-right" placeholder="Buscar"
                            value="{{ request()->get('busqueda') }}" style="width: 60px;">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
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
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Valor</th>
                            <th>Estado</th>
                            <th>Tiene devoluciones?</th>
                            <th>Acciones</th>
                            <th colspan="3"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($ventas as $venta)

                        <tr>
                            <td> {{ $venta->id }} </td>
                            <td> {{ date('d/m/Y h:i:s A', strtotime($venta->fecha)) }} </td>
                            <td> <a href="{{ route('cliente.show', $venta->cliente)}}" title="ver cliente" style="color: black">{{ $venta->cliente->user->nombres }} {{ $venta->cliente->user->apellidos }}</a></td>
                            <td> ${{ floatval($venta->valor) }}</td>
                            <td>
                                @if ($venta->estado == 1)
                                <span class="badge badge-success">
                                {{ "Pagada" }}
                                </span>
                                @endif
                                @if ($venta->estado == 2)
                                <span class="badge badge-warning">
                                {{ "Con saldo" }} 
                                </span>
                                @endif
                                @if ($venta->estado == 3)
                                <span class="badge badge-danger">
                                {{ "Anulada" }}
                                </span>
                                @endif
                            </td>
                            <td>
                                @if ($venta->devoluciones)
                                    {{"Sí"}}
                                @else
                                    {{"No"}}
                                @endif
                            </td>
                            <td> <a class="btn btn-primary" href="{{ route('venta.show', $venta->id)}}" title="ver venta"><i class="fas fa-eye"></i></a></td>
                            <td><a href="{{ route('admin.pedidos.show', $venta->pedido->id)}}" class="btn btn-success" title="ver pedido"><i class="fas fa-shopping-cart"></i></a></td>
                            <td><form action="{{ route('venta.anular', $venta->id)}}" method="post">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger" title="anular venta"><i class="fas fa-trash"></i></button>
                                </form>
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

@endsection
