<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listado de pagos</title>
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
        <h3>Reporte de Pagos<span class="derecha">{{now()}}</span></h3>
    </div>
    <div>
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Fecha</th>
                    <th># Venta</th>
                    <th>Ref. Epayco</th>
                    <th>Valor</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pagos as $pago)
                <tr>
                    <td> {{ $pago->id }} </td>
                    <td> {{ date('d/m/Y h:i:s A', strtotime($pago->fecha)) }}</td>
                    <td> {{ $pago->venta_id }}</td>
                    <td> {{ $pago->ref_epayco ? : "no aplica"}}</td>
                    <td> ${{ floatval($pago->monto) }}</td>
                    <td>
                        @if ($pago->estado == "Aceptado")
                        {{ "Aceptado" }}
                        @endif
                        @if ($pago->estado == "Rechazado")
                        {{ "Rechazado" }}
                        @endif
                        @if ($pago->estado == "Pendiente")
                        {{ "Pendiente" }}
                        @endif
                        @if ($pago->estado == "Fallido")
                        {{ "Fallido" }}
                        @endif
                        @if ($pago->estado == "Anulado")
                        {{ "Anulado" }}
                        @endif
                    </td>
                </tr>
                @endforeach                                
            </tbody>
        </table>
    </div>
    <div class="izquierda">
        <p><strong>Total de registros: </strong>{{$count}}</p>
    </div>    
</body>
</html>