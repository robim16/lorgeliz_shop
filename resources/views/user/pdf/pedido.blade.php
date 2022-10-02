<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle del pedido</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 0.875rem;
            font-weight: normal;
            line-height: 1.5;
            color: #151b1e;           
        }
        .table {
            display: table;
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }
        .table-bordered {
            border: 1px solid #c2cfd6;
        }
        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }
        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }
        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #c2cfd6;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #c2cfd6;
        }
        .table-bordered thead th, .table-bordered thead td {
            border-bottom-width: 2px;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #c2cfd6;
        }
        th, td {
            display: table-cell;
            vertical-align: inherit;
        }
        th {
            font-weight: bold;
            text-align: -internal-center;
            text-align: left;
        }
        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }
        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .izquierda{
            float:left;
        }
        .derecha{
            float:right;
        }
    </style>
</head>
<body>

    <div>
        {{-- <p>Id del pedido: {{ $users[0]->pedido}}. Fecha del pedido: {{ date('d/m/Y h:i:s A', strtotime($users[0]->fecha)) }}</p> --}}
        <p>Id del pedido: {{ $productos[0]->venta->pedido->id}}. Fecha del pedido: {{ date('d/m/Y h:i:s A', strtotime($productos[0]->venta->pedido->fecha)) }}</p>
    </div>

    <div>
        <h3>Información del cliente</h3>
    </div>
    <div>
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Cédula</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach ($users as $user) --}}
                <tr>
                    {{-- <td>{{ $user->nombres }} {{ $user->apellidos }}</td>
                    <td>{{ $user->identificacion }}</td>
                    <td>{{ $user->direccion }}</td>
                    <td>{{ $user->telefono }}</td>
                    <td>{{ $user->email }}</td> --}}

                    <td>{{ $productos[0]->venta->cliente->user->nombres }} {{ $productos[0]->venta->cliente->user->apellidos }}</td>
                    <td>{{ $productos[0]->venta->cliente->user->identificacion }}</td>
                    <td>{{ $productos[0]->venta->cliente->user->direccion }}</td>
                    <td>{{ $productos[0]->venta->cliente->user->telefono }}</td>
                    <td>{{ $productos[0]->venta->cliente->user->email }}</td>
                </tr>
                {{-- @endforeach --}}
            </tbody>
        </table>
    </div>
    <div>
        <h3>Productos</h3>
    </div>
    <div>
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Talla</th>
                    <th>Color</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->productoReferencia->colorProducto->producto->id }}</td>
                    <td>{{ $producto->productoReferencia->colorProducto->producto->nombre }}</td>
                    <td> 
                        <img src="{{ url('storage/' . $producto->productoReferencia->colorProducto->imagenes[0]->url) }}" alt="" style="height: 50px; width: 50px;" class="rounded-circle">
                    </td>
                    <td>{{ $producto->productoReferencia->talla->nombre }}</td>
                    <td>{{ $producto->productoReferencia->colorProducto->color->nombre }}</td>
                    <td>{{ $producto->cantidad }}</td>
                    {{-- <td>${{ floatval($producto->productoReferencia->colorProducto->producto->precio_actual)}}</td>
                    <td>${{ floatval($producto->productoReferencia->colorProducto->producto->precio_actual * $producto->cantidad)}}</td> --}}
                    <td>${{ floatval($producto->precio_venta)}}</td>
                    <td>${{ floatval($producto->precio_venta * $producto->cantidad)}}</td>
                </tr>
                @endforeach                                
            </tbody>
        </table>
    </div> 
    <div class="izquierda">
        <p><strong>Total pedido: </strong>${{ floatval($producto->venta->valor)}}</p>
    </div> 
</body>
</html>