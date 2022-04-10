@extends('layouts.account')

@section('title')
<h1 class="m-0 text-dark"> Detalle del pedido </h1>
@endsection

@section('breadcumb')
<li class="breadcrumb-item">Mis pedidos</li>
<li class="breadcrumb-item">Detalle del pedido</li>
@endsection


@section('content')
{{-- <div id="pedidos"> --}}
{{-- <div>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-2">Productos adquiridos con mi pedido</h3>

                            <div class="card-tools">
                                <form>
                                    <div class="input-group input-group-sm" style="width: 180px;">

                                        <div class="input-group-append">
                                            <a href="" class="btn btn-info mx-1"
                                                v-on:click.prevent="imprimir({{ $productos[0]->venta->pedido->id }})"
                                                title="imprimir pedido"><i class="fas fa-print"></i></a>
                                        </div>

                                        <input type="text" name="busqueda" class="form-control float-right"
                                            placeholder="buscar" value="{{ request()->get('busqueda') }}">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                       
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Imagen</th>
                                        <th scope="col">Talla</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Precio unitario</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($productos as $producto)

                                    <tr>
                                        <td>
                                            
                                            <a href="{{ route('productos.show', $producto->productoReferencia->colorProducto->slug)}}" style="color: black">{{ $producto->productoReferencia->colorProducto->producto->nombre }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('productos.show',$producto->productoReferencia->colorProducto->slug)}}">
                                                <img src="{{ url('storage/' . $producto->productoReferencia->colorProducto->imagenes[0]->url) }}" alt=""
                                                    style="height: 50px; width: 50px;" class="rounded-circle">
                                            </a>
                                        </td>
                                        <td>{{ $producto->productoReferencia->talla->nombre }}</td>
                                        <td>{{ $producto->productoReferencia->colorProducto->color->nombre }}</td>
                                        <td>{{ $producto->cantidad }}</td>
                                        <td>${{ floatval($producto->productoReferencia->colorProducto->producto->precio_actual) }}</td>
                                        <td>${{ floatval($producto->productoReferencia->colorProducto->producto->precio_actual * $producto->cantidad) }}</td>
                                        <td>
                                            @if (!\App\Devolucione::activarDevolucion($producto->venta->id,$producto->productoReferencia->id))
                                            <a href="" class="btn btn-success" title="solicitar cambio"
                                                v-on:click.prevent="store({{ $producto->productoReferencia->id }},
                                                {{ $producto->venta->id}},
                                                {{ $producto->cantidad }}
                                                )">
                                                <i class="fas fa-recycle"></i>
                                            </a>
                                            @else
                                            {{"cambio solicitado"}}
                                            @endif
                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-right">Total pedido:</td>
                                        <td colspan="2" class="text-left">${{ floatval($producto->venta->valor) }}</td>
                                    </tr>

                                </tfoot>
                            </table>
                        </div>
                       
                    </div>
                   
                </div>
            </div>
            
        </div>

    </div>
</div> --}}

<order-detail :id="{{ $id }}"></order-detail>


@endsection

@section('scripts')

{{-- <script>
    $(document).ready(function () {

        // $.ajaxSetup({

        //     headers: {
        //         'X-CSRF-TOKEN': $("input[name= _token]").val()
        //     }
        // });

        // $(".btn-success").click(function (e) {
        //     e.preventDefault();

        //     let ref = parseInt($(this).attr('id'));
        //     let venta = parseInt($('#venta' + ref).val());
        //     let cantidad = parseInt($('#cant' + ref).html());

        //     $.ajax({
        //         type: "POST",
        //         url: "{{ route('devolucion.store') }}",
        //         data: {
        //             ref: ref,
        //             venta: venta,
        //             cantidad: cantidad
        //         },
        //         dataType: 'json',
        //         success: function (response) {

        //             let devolucion = response.data;

        //             if (devolucion > 0) {
        //                 swal(
        //                     'Solicitud rechazada!',
        //                     'Solicitaste el cambio de este producto antes!',
        //                     'error'
        //                 )
        //             } else {
        //                 swal(
        //                     'Producto enviado para cambio!',
        //                     'Haz solicitado el cambio de este producto!',
        //                     'success'
        //                 )
        //             }

        //         }

        //     });

        // });

    });

</script> --}}

@endsection
