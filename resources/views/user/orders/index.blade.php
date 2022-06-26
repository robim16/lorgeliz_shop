
@extends('layouts.account')

@section('title')
<h1 class="m-0 text-dark"> Mis pedidos </h1>
@endsection

@section('breadcumb')
<li class="breadcrumb-item"><a href="{{ route('pedidos.index')}}">Mis pedidos</a></li>
@endsection


@section('content')

<div id="">
    <div class="content">
        <div class="container">
            @if (count($pedidos) > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Información de mis pedidos</h3>

                            <div class="card-tools">
                                <form>
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="busqueda" class="form-control float-right"
                                            placeholder="buscar por id" value="{{ request()->get('keyword') }}">

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
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Factura</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col" colspan="2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($pedidos as $pedido)

                                    <tr>
                                        <td>{{ $pedido->pedido->id }}</td>
                                        <td>{{ date('d/m/Y h:i:s A', strtotime($pedido->pedido->fecha)) }}</td>
                                        <td>{{ $pedido->factura->prefijo }}{{ $pedido->factura->consecutivo }}</td>
                                        <td><span class="badge badge-success">
                                            @if ($pedido->pedido->estado == 1 )
                                            {{ "pendiente" }}
                                            @endif
                                            @if ($pedido->pedido->estado == 2)
                                            {{ "en proceso"}}
                                            @endif
                                            @if ($pedido->pedido->estado == 3)
                                            {{ "enviado"}}
                                            @endif
                                            @if ($pedido->pedido->estado == 4)
                                            {{ "entregado"}}
                                            @endif
                                            </span>
                                        </td>
                                        <td>${{ floatval($pedido->valor) }}</td>
                                        <td>
                                            <a href="{{ route('pedidos.show', $pedido->pedido->id)}}"
                                                class="btn btn-primary btn-sm btn-icon" title="ver pedido">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <factura :id="{{$pedido->pedido->id}}" :ruta="ruta"></factura>
                                            {{-- <a href=""
                                            class="btn btn-success" title="descargar factura" v-on:click.prevent="pdfVenta({{$pedido->id}})">
                                            <i class="fas fa-download"></i></a> --}}
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
        @else
        <div class="alert alert-info pt-5 col-md-7 text-center m-auto">
            <h4 class="alert-heading">{{ __("No se encontraron resultados") }}</h4>
        </div>
        @endif

    </div>
</div>


@endsection




