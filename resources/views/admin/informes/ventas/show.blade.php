
@extends('layouts.admin')


@section('titulo', 'Informe de ventas')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('content')
<div id="infventashow">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Ventas en este periodo</h3>

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
                            <a class=" m-2 float-right btn btn-warning" href="" @click.prevent="pdfInformeVentas"> <i class="fa fa-print"></i></a>
                            <table class="table table-head-fixed">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Factura</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">N° Productos</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($ventas as $venta)
                                    <tr>
                                        <td>{{ $venta->id }}</td>
                                        <td>{{ date('d/m/Y h:i:s A', strtotime($venta->fecha)) }}</td>
                                        <td>{{ $venta->prefijo }}{{ $venta->consecutivo }}</td>
                                        <td> <a href="{{ route('cliente.show', $venta->cliente)}}">
                                            {{ $venta->nombres }}</a>
                                        </td>
                                        <td>${{ floatval($venta->valor) }}</td>
                                        <td>{{ $venta->cantidad }}</td>
                                        <td><a href="{{ route('venta.show',$venta->id)}}" class="btn btn-primary"
                                                title="ver venta">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach --}}

                                    @foreach ($ventas as $item)
                                    <tr>
                                        <td>{{ $item->venta->id }}</td>
                                        <td>{{ date('d/m/Y h:i:s A', strtotime($item->venta->fecha)) }}</td>
                                        <td>{{ $item->venta->factura->prefijo }}{{ $item->venta->factura->consecutivo }}</td>
                                        <td> <a href="{{ route('cliente.show', $item->venta->cliente->id)}}">
                                            {{ $item->venta->cliente->user->nombres }} {{ $item->venta->cliente->user->apellidos }}</a>
                                        </td>
                                        <td>${{ floatval($item->venta->valor) }}</td>
                                        <td>{{ $item->cantidad }}</td>
                                        <td>
                                            <a href="{{ route('venta.show',$item->venta->id)}}" class="btn btn-primary btn-sm btn-icon"
                                                title="ver venta">
                                                <i class="fas fa-eye"></i>
                                            </a>
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

@section('scripts')
    <script>
        window.data = {
        datos: {
            "ventames": "{{$item->venta->fecha}}"
        }
    }
    </script>
@endsection




