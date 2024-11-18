<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Lorgeliz Tienda - Respuesta de la transacción</title>
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <header id="main-header" style="margin-top:20px">
        <div class="row">
            <div class="col-lg-12 franja">
                <img class="center-block"
                    src="{{ asset('asset/images/lorgeliz2.jpeg') }}"
                    style="width: 120px">
            </div>
        </div>
    </header>
    <div class="container">
        <div class="row" style="margin-top:20px">
            <div class="col-lg-8 col-lg-offset-2 ">
                <h4 style="text-align:left"> Respuesta de la Transacción </h4>
                <hr>
            </div>
            <div class="col-lg-8 col-lg-offset-2 mb-2">
                <a href="{{ route('pedidos.show', $venta->pedido->id)}}" class="btn btn-primary" style="text-align:left">
                    Ver mi pedido
                </a>
            </div>
            <div class="col-lg-8 col-lg-offset-2 ">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Referencia</td>
                                <td id="referencia">
                                    {{ $transaction["x_id_invoice"] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold">Fecha</td>
                                <td id="fecha" class="">
                                    {{ $transaction["x_transaction_date"] }}
                                </td>
                            </tr>
                            <tr>
                                <td>Respuesta</td>
                                <td id="respuesta">
                                    {{ $transaction["x_response"] }}
                                </td>
                            </tr>
                            <tr>
                                <td>Motivo</td>
                                <td id="motivo">
                                    {{ $transaction["x_response_reason_text"] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold">Banco</td>
                                <td class="" id="banco">
                                    {{ $transaction["x_bank_name"] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold">Recibo</td>
                                <td id="recibo">
                                    {{ $transaction["x_transaction_id"] }}
                                </td>
                            </tr>
                            <tr>
                                <td class="bold">Total</td>
                                <td class="" id="total">
                                    {{ $transaction["x_amount"] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="row">
            <div class="container">
                <div class="col-lg-8 col-lg-offset-2">
                    <img src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/btns/epayco/pagos_procesados_por_epayco_260px.png"
                        style="margin-top:10px; float:left"> <img
                        src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/btns/epayco/credibancologo.png"
                        height="40px" style="margin-top:10px; float:right">
                </div>
            </div>
        </div>
    </footer>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>
