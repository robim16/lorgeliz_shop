
@extends('layouts.admin')


@section('titulo', 'Administración de Pedidos')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('content')
<div id="imprimir_pedidos">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Información de pedidos de clientes</h3>
    
                            <div class="card-tools">
                                <form>
                                    <div class="input-group input-group-sm">
                                        <select name="tipo" class="form-control mr-sm-2" id="tipo">
                                            <option>Buscar por...</option>
                                            <option>id</option>
                                            <option>fecha</option>
                                        </select>
                                        <input type="text" name="keyword" class="form-control float-right" placeholder="Buscar"
                                        value="{{ request()->get('keyword') }}">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                        </div>
                                        <div class="input-group-append">
                                            <a href="" class="btn btn-success mx-1" @click.prevent="pdfInformePedidos"><i class="fas fa-print"></i></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Venta</th>
                                        <th scope="col" colspan="3">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
    
                                    @foreach ($pedidos as $pedido)
    
                                    {{-- <tr>
                                        <td>{{ $pedido->id }}</td>
                                        <td>{{ date('d/m/Y h:i:s A', strtotime($pedido->fecha)) }}</td>
                                        <td><a href="{{ route('cliente.show', $pedido->cliente)}}"
                                            title="ver cliente">{{ $pedido->nombres }} {{ $pedido->apellidos }}</a>
                                        </td>
                                        <td><span class="badge badge-success">
                                            @if ($pedido->estado == 1 )
                                            {{ "pendiente" }}
                                            @endif
                                            @if ($pedido->estado == 2)
                                            {{ "en proceso"}}
                                            @endif
                                            @if ($pedido->estado == 3)
                                            {{ "enviado"}}
                                            @endif
                                            @if ($pedido->estado == 4)
                                            {{ "entregado"}}
                                            @endif
                                            </span>
                                        </td>
                                        <td>${{ floatval($pedido->valor) }}</td>
                                        <td><a href="{{ route('venta.show', $pedido->venta)}}"
                                           title="ver venta">{{ $pedido->venta}}</a></td>
                                        <td><a href="{{ route('admin.pedidos.show', $pedido->id)}}"
                                        class="btn btn-primary" title="ver pedido">
                                         <i class="fas fa-eye"></i></a>
                                        </td>
                                        <td><a href=""
                                            class="btn btn-warning" title="cambiar estado"
                                            data-toggle="modal"
                                            data-target="#modalEstado"
                                            data-id="{{$pedido['id']}}"
                                            data-status="{{$pedido['estado']}}">
                                            <i class="fas fa-pen"></i></a>
                                        </td>
                                        <td><a class="btn btn-success" href="" v-on:click.prevent="imprimir({{ $pedido->id}})" title="imprimir"><i class="fa fa-print"></i></a>
                                        </td>
                                    </tr> --}}

                                    <tr>
                                        <td>{{ $pedido->id }}</td>
                                        <td>{{ date('d/m/Y h:i:s A', strtotime($pedido->fecha)) }}</td>
                                        <td><a href="{{ route('cliente.show', $pedido->venta->cliente->id)}}"
                                            title="ver cliente">{{ $pedido->venta->cliente->user->nombres }} {{ $pedido->venta->cliente->user->apellidos }}</a>
                                        </td>
                                        <td><span class="badge badge-success">
                                            @if ($pedido->estado == 1 )
                                            {{ "pendiente" }}
                                            @endif
                                            @if ($pedido->estado == 2)
                                            {{ "en proceso"}}
                                            @endif
                                            @if ($pedido->estado == 3)
                                            {{ "enviado"}}
                                            @endif
                                            @if ($pedido->estado == 4)
                                            {{ "entregado"}}
                                            @endif
                                            @if ($pedido->estado == 5)
                                            {{ "anulado"}}
                                            @endif
                                            </span>
                                        </td>
                                        <td>${{ floatval($pedido->venta->valor) }}</td>
                                        <td><a href="{{ route('venta.show', $pedido->venta->id)}}"
                                           title="ver venta">{{ $pedido->venta->id}}</a></td>
                                        <td><a href="{{ route('admin.pedidos.show', $pedido->id)}}"
                                        class="btn btn-primary btn-sm btn-icon" title="ver pedido">
                                         <i class="fas fa-eye"></i></a>
                                        </td>

                                        @if ($pedido->estado != 5)
                                            
                                            <td><a href=""
                                                class="btn btn-warning btn-sm btn-icon" title="cambiar estado"
                                                data-toggle="modal"
                                                data-target="#modalEstado"
                                                data-id="{{$pedido['id']}}"
                                                data-status="{{$pedido['estado']}}">
                                                <i class="fas fa-pen"></i></a>
                                            </td>
                                        @endif

                                        <td><a class="btn btn-success btn-sm btn-icon" href="" @click.prevent="imprimir({{ $pedido->id}})" title="imprimir"><i class="fa fa-print"></i></a>
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            {{ $pedidos->appends($_GET)->links() }}
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


<div class="modal fade" id="modalEstado" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-primary">
				<h5 class="modal-title" id="appModalLabel">Cambiar estado del Pedido</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

                <form id='formEstado' class="form-horizontal" action="{{ route('admin.pedidos.update')}}" method="POST">
                @csrf
                @method('PUT')
                    <div class="form-group row">
                        <label class="col-md-4 form-control-label" for="text-input">Estado del Pedido</label>
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
                                            {{ "enviado"}}
                                        </option>
                                    @endif

                                    @if ($estado == 4)
                                        <option value="{{ $estado }}" class="option">
                                        {{ "entregado"}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            @if($errors->has('estado'))
                            <small class="form-text text-danger">
                                {{ $errors->first('estado') }}
                            </small>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="pedido_id" id="pedido_id" value=""/>

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
        $('.btn-warning').click(function (e) { 
            e.preventDefault();
    
            const id = jQuery(this).data('id');
            $('#pedido_id').val(id);

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




