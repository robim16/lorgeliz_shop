@extends('layouts.admin')

@section('titulo', 'Listado de Pagos')

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('content')

    <div id="payments" class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sección de pagos</h3>

                    <div class="card-tools">

                        <form>
                            <div class="input-group input-group-sm" style="width: 170px;">
                                <div class="input-group-append">
                                    <a href="" class="btn btn-success mx-1" @click.prevent="pdfListPagos">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                                <input type="text" name="busqueda" class="form-control float-right" placeholder="Buscar"
                                value="{{ request()->get('busqueda') }}">

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
                                <th># Venta</th>
                                <th>Ref. epayco</th>
                                <th>Valor</th>
                                <th>Estado</th>
                                <th scope="col" colspan="4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            @foreach ($pagos as $pago)

                            <tr>
                                <td> {{ $pago->id }} </td>
                                <td> {{ date('d/m/Y h:i:s A', strtotime($pago->fecha)) }}</td>
                                <td> 
                                    <a href="{{ route('venta.show', $pago->venta_id)}}" title="ver venta">{{$pago->venta_id}}</a>
                                </td>
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
                                <td> 
                                    <a class="btn btn-primary btn-sm btn-icon" href="{{ route('venta.show', $pago->venta_id)}}" title="ver venta">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="" class="btn btn-success btn-sm btn-icon" title="imprimir" @click.prevent="imprimirPago({{ $pago->id }})">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                                @if ($pago->ref_epayco)
                                    @if ($pago->estado == 3)
                                        <td>
                                            <a href="" class="btn btn-warning btn-sm btn-icon" title="consultar" @click.prevent="getResponse('{{ $pago->ref_epayco }}')">
                                                <i class="fas fa-search"></i>
                                            </a>
                                        </td>
                                    @endif
                                @endif

                                <td>  
                                    <form action="{{ route('payments.cancel', $pago->id)}}" method="post">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="anular"><i class="fa fa-ban"></i> </button>
                                    </form>
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

        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg pt-5" role="document">
                <div class="modal-content pt-3">
                    <div class="modal-header">
                        <h4 class="modal-title">Respuesta de epayco</h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Monto</th>
                                        <th>Respuesta</th>
                                        <th>Motivo</th>
                                        <th>Cod. Respuesta</th>
                                        <th>Transacción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td v-text="x_transaction_date"></td>
                                        <td v-text="x_amount"></td>
                                        <td v-text="x_response"></td>
                                        <td v-text="x_response_reason_text"></td>
                                        <td v-text="x_cod_response"></td>
                                        <td v-text="x_transaction_id"></td>
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </div>
    <!-- /.row -->

@endsection

<style>
    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    
</style>


