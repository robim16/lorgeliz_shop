<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Pagos</title>
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
        <h3>Informe de pagos por mes<span class="derecha">{{now()}}</span></h3>
    </div>
    <div>
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Año</th>
                    <th>Mes</th>
                    <th>Pagos Recibidos</th>
                    <th>Valor Pagos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pagos as $pago)
                <tr>
                    <td>{{$pago->anio}}</td>
                    <td>
                        @if ($pago->mes == 1)
                        {{"Enero"}}
                        @endif
                        @if ($pago->mes == 2)
                        {{"Febrero"}}
                        @endif
                        @if ($pago->mes == 3)
                        {{"Marzo"}}
                        @endif
                        @if ($pago->mes == 4)
                        {{"Abril"}}
                        @endif
                        @if ($pago->mes == 5)
                        {{"Mayo"}}
                        @endif
                        @if ($pago->mes == 6)
                        {{"Junio"}}
                        @endif
                        @if ($pago->mes == 7)
                        {{"Julio"}}
                        @endif
                        @if ($pago->mes == 8)
                        {{"Agosto"}}
                        @endif
                        @if ($pago->mes == 9)
                        {{"Septiembre"}}
                        @endif
                        @if ($pago->mes == 10)
                        {{"Octubre"}}
                        @endif
                        @if ($pago->mes == 11)
                        {{"Noviembre"}}
                        @endif
                        @if ($pago->mes == 12)
                        {{"Diciembre"}}
                        @endif
                    </td>
                    <td>{{$pago->cantidad}}</td>
                    <td>${{floatval($pago->total)}}</td>
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