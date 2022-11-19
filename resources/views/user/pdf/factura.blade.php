<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Venta</title>
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif; 
        font-size: 14px;
        /*font-family: SourceSansPro;*/
        }

        #logo{
        float: left;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;
        }

        #imagen{
        width: 100px;
        }

        #datos{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
        }

        #encabezado{
        text-align: center;
        margin-left: 10%;
        margin-right: 35%;
        font-size: 15px;
        }

        #fact{
        /*position: relative;*/
        float: right;
        margin-top: 2%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        }

        section{
        clear: left;
        }

        #cliente{
        text-align: left;
        }

        #facliente{
        width: 40%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 15px;
        }

        #facliente thead{
        padding: 20px;
        background: #2183E3;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facvendedor thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facarticulo{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facarticulo thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        tfoot tr{
        float: right;
        margin-left: 20%;
        margin-right: 2%;
        }

        #gracias{
        text-align: center; 
        }

    </style>
    <body>
        {{-- @foreach ($users as $user) --}}
        <header>
            <div id="logo">
                <img src="{{ url('storage/imagenes/logo/lorgeliz2.jpeg') }}" alt="lorgeliz" id="imagen">
            </div>
            <div id="datos">
                <p id="encabezado">
                    <b>Lorgeliz Tienda</b><br>José Gálvez 1368, Montería - Córdoba, Colombia<br>Telefono:(+57)   
                    3138645929<br>Email: lorgeliztienda@gmail.com
                </p>
            </div>
            <div id="fact">
                <p>
                    {{-- {{$user->factura->prefijo}}-{{$user->factura->consecutivo}}</p> --}}
                    {{$productos[0]->venta->factura->prefijo}}-{{$productos[0]->venta->factura->consecutivo}}
                </p>
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr>
                            <th id="fac">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            {{-- <th><p id="cliente">Sr(a). {{ $user->cliente->user->nombres}} {{ $user->cliente->user->apellidos}}<br>
                            {{ "identificacion"}}: {{$user->cliente->user->identificacion}}<br>
                            Departamento: {{$user->cliente->user->departamento}}<br>
                            Municipio: {{$user->cliente->user->municipio}}<br>
                            Dirección: {{$user->cliente->user->direccion}}<br>
                            Teléfono: {{$user->cliente->user->telefono}}<br>
                            Email: {{$user->cliente->user->email}}</</p></th> --}}

                            <th>
                                <p id="cliente">
                                    Sr(a). {{ $productos[0]->venta->cliente->user->nombres}} {{ $productos[0]->venta->cliente->user->apellidos}} <br>
                                    Identificación: {{$productos[0]->venta->cliente->user->identificacion}}<br>
                                    Departamento: {{$productos[0]->venta->cliente->user->departamento}}<br>
                                    Municipio: {{$productos[0]->venta->cliente->user->municipio}}<br>
                                    Dirección: {{$productos[0]->venta->cliente->user->direccion}}<br>
                                    Teléfono: {{$productos[0]->venta->cliente->user->telefono}}<br>
                                    Email: {{$productos[0]->venta->cliente->user->email}}
                                </p>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        {{-- @endforeach --}}
        <br>
        <section>
            <div>
                <table id="facvendedor">
                    <thead>
                        <tr id="fv">
                            <th>VENTA</th>
                            <th>FECHA</th>
                            <th>ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $productos[0]->venta->id}}</td>
                            <td>{{ date('d/m/Y h:i:s A', strtotime($productos[0]->venta->fecha)) }}</td>
                            <td>
                                @if ($productos[0]->venta->saldo == 0)
                                {{"CANCELADA"}}
                                @else
                                {{"PENDIENTE"}}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <section>
            <div>
                <table id="facarticulo">
                    <thead>
                        <tr id="fa">
                            <th>CANT</th>
                            <th>DESCRIPCION</th>
                            <th>TALLA</th>
                            <th>COLOR</th>
                            <th>PRECIO UNIT</th>
                            <th>DESC.</th>
                            <th>PRECIO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $producto->cantidad }}</td>
                            <td>{{ $producto->productoReferencia->colorProducto->producto->nombre }}</td>
                            <td>{{ $producto->productoReferencia->talla->nombre }}</td>
                            <td>{{ $producto->productoReferencia->colorProducto->color->nombre }}</td>
                            {{-- <td>${{ floatval($producto->productoReferencia->colorProducto->producto->precio_actual) }}</td> --}}
                            <td>${{ floatval($producto->precio_venta) }}</td>
                            {{-- <td>{{ $producto->productoReferencia->colorProducto->producto->porcentaje_descuento }}</td> --}}
                            <td>{{ $producto->porcentaje_descuento }}</td>
                            {{-- <td>${{ $producto->cantidad*$producto->productoReferencia->colorProducto->producto->precio_actual
                            -$producto->productoReferencia->colorProducto->producto->porcentaje_descuento }}</td> --}}
                            <td>${{ floatval($producto->cantidad *
                                $producto->precio_venta-$producto->porcentaje_descuento) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        {{--@foreach ($venta as $v)--}}
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>SUBTOTAL</th>
                            <td>${{ floatval($producto->venta->subtotal)}}</td>
                        </tr>
                        {{-- <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Impuesto</th>
                            <td>$0</td>
                        </tr> --}}
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Envío</th>
                            <td>${{ floatval($producto->venta->envio)}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>       
                            <th>TOTAL</th>      
                            <td>${{ floatval($producto->venta->valor)}}</td>
                        </tr>
                       {{-- @endforeach--}}
                    </tfoot>
                </table>
            </div>
        </section>
        <br>
        <br>
        <footer>
            <div id="gracias">
                <p><b>Gracias por su compra!</b></p>
            </div>
        </footer>
    </body>
</html>