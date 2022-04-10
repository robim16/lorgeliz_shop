
@extends('layouts.admin')


@section('titulo', 'Informe de pagos')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('content')
<div id="infpagoshow">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Pagos en este periodo</h3>

                            <div class="card-tools">
                                <form>
                                    <div class="input-group input-group-sm">
                                        <input type="date" name="fecha_de" id="fecha_de" required class="form-control mx-1">
                                        <input type="date" name="fecha_a" id="fecha_a" required class="form-control mx-1">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-success"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            {{-- <a class="m-2 float-right btn btn-warning" href="" v-on:click.prevent="pdfInformePagos()">
                                <i class="fa fa-print"></i>
                            </a> --}}
                            <div id="app">
                                <pagos-mes></pagos-mes>
                            </div>
                            
                            <table class="table table-head-fixed">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Ref. epayco</th>
                                        <th scope="col">Venta</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pagos as $pago)
                                    <tr>
                                        <td>{{ $pago->id }}</td>
                                        <td>{{ date('d/m/Y h:i:s A', strtotime($pago->fecha)) }}</td>
                                        <td>{{ $pago->ref_epayco }}</td>
                                        <td><a href="{{ route('venta.show',$pago->venta_id)}}">
                                            {{ $pago->venta_id }}</a>
                                        </td>
                                        <td>${{ floatval($pago->monto) }}</td>
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
                                        
                                        <td><a href="" class="btn btn-info"
                                            title="imprimir pago">
                                            <i class="fas fa-print"></i></a>
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

@section('scripts')
    <script>
        window.data = {
        datos: {
            "mes": "{{$pago->fecha}}"
        }
    }
    </script>
@endsection




