@extends('layouts.admin')


@section('titulo', 'Administración de Ventas')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')


<div id="factura_venta" class="row">

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detalle de la venta</h3>

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
            <div class="card-body table-responsive p-0" style="height: 300px;">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Factura</th>
                            <th>Valor</th>
                            <th>Pagos</th>
                            <th>Devoluciones</th>
                            <th>Saldo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                            <th colspan="9"></th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            {{-- <td>{{ $venta->id }}</td>
                            <td>{{ date('d/m/Y h:i:s A', strtotime($venta->fecha)) }}</td>
                            <td><a href="{{ route('cliente.show', $venta->cliente)}}">{{ $venta->nombres }} {{ $venta->apellidos}}</a></td>
                            <td>{{ ($venta->prefijo) }}{{ ($venta->consecutivo) }}</td>
                            <td>${{ floatval($venta->valor) }}</td>
                            <td>${{ floatval($venta->saldo) }}</td>
                            <td>
                                @if ($venta->estado == 1)
                                {{ "Pagada" }}
                                @endif
                                @if ($venta->estado == 2)
                                {{ "Con saldo" }}
                                @endif
                                @if ($venta->estado == 3)
                                {{ "Anulada" }}
                                @endif
                            </td>
                            @if ($venta->estado == 2 & $venta->saldo > 0)
                            <td>
                                <a class="btn btn-primary" href="" title="registrar pago" data-toggle="modal"
                                    data-target="#modal" id="pay">
                                    <i class="fas fa-money-bill-wave"></i>
                                </a>
                            </td>
                            @endif
                            <td><a href="" class="btn btn-success" title="imprimir factura" 
                                    v-on:click.prevent="facturaVenta({{$venta->id}})">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                            <td><a href="{{ route('admin.pedidos.show', $venta->pedido->id)}}" class="btn btn-info"
                                    title="ver pedido"><i class="fas fa-shopping-cart"></i></a>
                            </td> --}}

                            <td>{{ $venta->id }}</td>
                            <td>{{ date('d/m/Y h:i:s A', strtotime($venta->fecha)) }}</td>
                            <td><a href="{{ route('cliente.show', $venta->cliente->id)}}">{{ $venta->cliente->user->nombres }} {{ $venta->cliente->user->apellidos}}</a></td>
                            <td>{{ ($venta->factura->prefijo) }}{{ ($venta->factura->consecutivo) }}</td>
                            <td>${{ floatval($venta->valor) }}</td>
                            <td>${{ $pagos[0]->total ? floatval($pagos[0]->total) : 0 }}</td>
                            <td>${{ floatval($valor_devolucion)}}</td>
                            <td>${{ floatval($venta->saldo) }}</td>
                            <td>
                                @if ($venta->estado == 1)
                                {{ "Pagada" }}
                                @endif
                                @if ($venta->estado == 2)
                                {{ "Con saldo" }}
                                @endif
                                @if ($venta->estado == 3)
                                {{ "Anulada" }}
                                @endif
                            </td>
                            @if ($venta->estado == 2 & $venta->saldo > 0)
                            <td>
                                <a class="btn btn-primary" href="" title="registrar pago" data-toggle="modal"
                                    data-target="#modal" id="pay">
                                    <i class="fas fa-money-bill-wave"></i>
                                </a>
                            </td>
                            @endif
                            <td><a href="" class="btn btn-success" title="imprimir factura" 
                                    @click.prevent="facturaVenta({{$venta->id}})">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                            <td><a href="{{ route('admin.pedidos.show', $venta->pedido->id)}}" class="btn btn-info"
                                    title="ver pedido"><i class="fas fa-shopping-cart"></i></a>
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

<div class="modal fade" id="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-primary">
				<h5 class="modal-title" id="appModalLabel">Registrar pago</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

                <form id='formPay' class="form-horizontal" action="{{ route('venta.pagar', $venta->id)}}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-4 form-control-label" for="text-input">Valor del pago</label>
                        <div class="col-md-8">
                            <input type="number" name="valor" id="valor" class="form-control" value="{{ $venta->saldo}}">
                            @if($errors->has('valor'))
                            <small class="form-text text-danger">
                                {{ $errors->first('valor') }}
                            </small>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary float-left" id="aceptar">Aceptar <i
                            class="far fa-paper-plane"></i></button>
                    <button type="reset" class="btn btn-danger float-right" id="rechazar">Cancelar</button>
				</form>

			</div>

			<div class="modal-footer">

			</div>
		</div>
	</div>
</div>

@endsection



