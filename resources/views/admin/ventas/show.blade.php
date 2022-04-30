@extends('layouts.admin')


@section('titulo', 'Administración de Ventas')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')


<div id="factura_venta" class="row">

    {{-- <div class="col-12"> --}}
        {{-- <div class="card"> --}}
            {{-- <div class="card-header">
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
            </div> --}}
            <!-- /.card-header -->
            {{-- <div class="card-body table-responsive p-0" style="height: 300px;"> --}}
                {{-- <table class="table table-hover table-striped"> --}}
                    {{-- <thead>
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
                    </thead> --}}
                    {{--<tbody>--}}

                        {{-- <tr> --}}
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

                            {{--<td>{{ $venta->id }}</td>
                            <td>{{ date('d/m/Y h:i:s A', strtotime($venta->fecha)) }}</td>
                            <td><a href="{{ route('cliente.show', $venta->cliente->id)}}">{{ $venta->cliente->user->nombres }} {{ $venta->cliente->user->apellidos}}</a></td>
                            <td>{{ ($venta->factura->prefijo) }}{{ ($venta->factura->consecutivo) }}</td>
                            <td>${{ floatval($venta->valor) }}</td>
                            <td>${{ $pagos[0]->total ? floatval($pagos[0]->total) : 0 }}</td>
                            <td>${{ floatval($valor_devolucion)}}</td>
                            <td>${{ floatval($venta->saldo) }}</td>
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


                        </tr>--}}
                        
                    {{--</tbody>--}}
                {{-- </table> --}}
               
            {{-- </div> --}}
            <!-- /.card-body -->
        {{-- </div> --}}
        <!-- /.card -->
    {{-- </div> --}}

    <div class="col-12">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                            href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home"
                            aria-selected="true">Información de la venta</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                            href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile"
                            aria-selected="false">Pagos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill"
                            href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages"
                            aria-selected="false">Devoluciones</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill"
                            href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings"
                            aria-selected="false">Settings</a>
                    </li> --}}
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel"
                        aria-labelledby="custom-tabs-four-home-tab">
                        
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
                                    
                                    <td>{{ $venta->id }}</td>
                                    <td>{{ date('d/m/Y h:i:s A', strtotime($venta->fecha)) }}</td>
                                    <td><a href="{{ route('cliente.show', $venta->cliente->id)}}">{{ $venta->cliente->user->nombres }} {{ $venta->cliente->user->apellidos}}</a></td>
                                    <td>{{ ($venta->factura->prefijo) }}{{ ($venta->factura->consecutivo) }}</td>
                                    <td>${{ floatval($venta->valor) }}</td>
                                    <td>${{ $pagos[0] ? floatval($pagos[0]->total) : 0 }}</td>
                                    <td>${{ floatval($valor_devolucion)}}</td>
                                    <td>${{ floatval($venta->saldo) }}</td>
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
                                    @if ($venta->estado == 2 & $venta->saldo > 0)
                                    <td>
                                        <a class="btn btn-primary" href="" title="registrar pago" data-toggle="modal"
                                            data-target="#modal" id="pay">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                    </td>
                                    @endif
                                    <td><a href="" class="btn btn-success btn-sm" title="imprimir factura" 
                                            @click.prevent="facturaVenta({{$venta->id}})">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                    <td><a href="{{ route('admin.pedidos.show', $venta->pedido->id)}}" class="btn btn-info btn-sm"
                                            title="ver pedido"><i class="fas fa-shopping-cart"></i></a>
                                    </td>
        
                                </tr>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7" class="text-right">Total venta:</td>
                                    <td colspan="4" class="text-left">${{ floatval($venta->valor) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-right">Total pagos:</td>
                                    <td colspan="4" class="text-left">${{ $pagos[0] ? floatval($pagos[0]->total) : 0 }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-right">Total devoluciones:</td>
                                    <td colspan="4" class="text-left">${{ floatval($valor_devolucion) ? floatval($valor_devolucion) : 0 }}</td>
                                </tr>

                                <tr>
                                    <td colspan="7" class="text-right">Saldo:</td>
                                    <td colspan="4" class="text-left">${{ floatval($venta->saldo) ? floatval($venta->saldo) : 0 }}</td>
                                </tr>


                            </tfoot>
                        </table>
                        
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                        aria-labelledby="custom-tabs-four-profile-tab">
                        
                        <table class="table table-head-fixed">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th># Venta</th>
                                    <th>Ref. epayco</th>
                                    <th>Valor</th>
                                    <th>Estado</th>
                                    <th scope="col" colspan="3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                @foreach ($pagos as $pago)
        
                                <tr>
                                    <td> {{ $pago->id }} </td>
                                    <td> {{ date('d/m/Y h:i:s A', strtotime($pago->fecha)) }}</td>
                                    <td> <a href="{{ route('venta.show', $pago->venta_id)}}" title="ver venta">{{$pago->venta_id}}</a></td>
                                    <td> {{ $pago->ref_epayco ? : "no aplica"}}</td>
                                    <td> ${{ floatval($pago->monto) }}</td>
                                    <td>
                                        @if ($pago->estado == 1)
                                        <span class="badge badge-success">
                                        {{ "Aceptado" }}
                                        </span>
                                        @endif
                                        @if ($pago->estado == 2)
                                        <span class="badge badge-danger">
                                        {{ "Rechazado" }}
                                        </span>
                                        @endif
                                        @if ($pago->estado == 3)
                                        <span class="badge badge-warning">
                                        {{ "Pendiente" }}
                                        </span>
                                        @endif
                                        @if ($pago->estado == 4)
                                        <span class="badge badge-danger">
                                        {{ "Fallido" }}
                                        </span>
                                        @endif
                                        @if ($pago->estado == 5)
                                        <span class="badge badge-danger">
                                        {{ "Anulado" }}
                                        </span>
                                        @endif
                                    </td>
                                    <td> <a class="btn btn-primary" href="{{ route('venta.show', $pago->venta_id)}}" title="ver venta"><i class="fas fa-eye"></i></a></td>
                                    <td><a href="" class="btn btn-success" title="imprimir" @click.prevent="imprimirPago({{ $pago->id }})"><i class="fas fa-print"></i></a></td>
                                    @if ($pago->ref_epayco)
                                        @if ($pago->estado == 3)
                                        <td><a href="" class="btn btn-warning" title="consultar" @click.prevent="getResponse('{{ $pago->ref_epayco }}')"><i class="fas fa-search"></i></a></td>
                                        @endif
                                    @endif
                                    
                                </tr>
                                @endforeach
        
                            </tbody>
                        </table>
                        {{ $pagos->appends($_GET)->links() }}
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel"
                        aria-labelledby="custom-tabs-four-messages-tab">
                       
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Venta</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Estado de la solicitud</th>
                                    <th scope="col" colspan="2">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($devoluciones as $devolucion)

                                <tr>
                                    <td>{{ $devolucion->id}}</td>
                                    <td>{{ date('d/m/Y h:i:s A', strtotime($devolucion->fecha)) }}</td>
                                    <td><a href="{{ route('venta.show', $devolucion->venta->id)}}"
                                            class="">{{ $devolucion->venta->id }}</a>
                                    </td>
                                    <td><a href="{{ route('cliente.show', $devolucion->venta->cliente->id)}}"
                                        class="">{{ $devolucion->venta->cliente->user->nombres}} {{$devolucion->venta->cliente->user->apellidos}}</a>
                                    </td>
                                    <td><span class="badge badge-success">
                                        @if ($devolucion->estado == 1 )
                                        {{ "pendiente" }}
                                        @endif
                                        @if ($devolucion->estado == 2)
                                        {{ "en proceso" }}
                                        @endif
                                        @if ($devolucion->estado == 3)
                                        {{ "rechazada" }}
                                        @endif
                                        @if ($devolucion->estado == 4)
                                        {{ "completada" }}
                                        @endif
                                        </span>
                                    </td>
                                    <td><a href="{{ route('admin.devolucion.show', $devolucion->id) }}"
                                        class="btn btn-primary" title="ver solicitud"><i class="fas fa-eye"></i></a>
                                    </td>
                                    <td><a href="" class="btn btn-success" title="actualizar estado"
                                    data-toggle="modal"
                                    data-target="#modalEstado"
                                    data-id="{{$devolucion['id']}}"
                                    data-status="{{$devolucion['estado']}}"><i class="fas fa-pen"></i></a>
                                    </td>
                                </tr>
                                    
                                @endforeach
                                
                            </tbody>

                        </table>
                        {{ $devoluciones->appends($_GET)->links() }} 
                    </div>
                    {{-- <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel"
                        aria-labelledby="custom-tabs-four-settings-tab">
                        Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis
                        ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate.
                        Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec
                        interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at
                        consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst.
                        Praesent imperdiet accumsan ex sit amet facilisis.
                    </div> --}}
                </div>
            </div>
            <!-- /.card -->
        </div>
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

<div class="modal fade" id="modalEstado" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-primary">
                <h5 class="modal-title" id="appModalLabel">actualizar estado de la solicitud</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                
            </div>

            <div class="modal-body">

                <form id='formEstado' class="form-horizontal"
                    action="{{ route('devolucion.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <label class="col-md-4 form-control-label" for="text-input">Estado de la solicitud</label>
                        <div class="col-md-8">
                            <select name="estado" id="estado" class="form-control">
                                <option value="">Seleccione uno</option>
                                @foreach($estados as $estado)
                                    @if ($estado == 1)
                                        <option value="{{ $estado }}" class="option">
                                            {{ "pendiente" }}
                                        </option>
                                    @endif

                                    @if ($estado == 2)
                                        <option value="{{ $estado }}" class="option">
                                            {{ "en proceso"}}
                                        </option>
                                    @endif

                                    @if ($estado == 3)
                                        <option value="{{ $estado }}" class="option">
                                            {{ "rechazada"}}
                                        </option>
                                    @endif

                                    @if ($estado == 4)
                                        <option value="{{ $estado }}" class="option">
                                           {{ "completada"}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            @if($errors->has('estado'))
                            <small class="form-text text-danger">
                                {{ $errors->first('estado') }}
                            </small>
                            @endif

                            <input type="hidden" name="devolucion_id" id="devolucion_id" value=""/>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary float-left" id="aceptar">Enviar <i
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

@section('scripts')

<script>
$(document).ready(function () {
    $('.btn-success').click(function (e) { 
        e.preventDefault();

        const id = jQuery(this).data('id');
        $('#devolucion_id').val(id);

        const status = jQuery(this).data('status');

        $(".option").each(function() {
            const st = $(this).val();
            if (st == status) {
            $(this).prop('selected',true);
            }
        });
    });
});

</script>
@endsection


