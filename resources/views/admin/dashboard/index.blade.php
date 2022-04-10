@extends('layouts.admin')


@section('titulo', 'Estadísticas de ventas')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')

 <div class="container-fluid">
    <div class="card">
        <div class="card-header">
            
        </div>
        <div class="car-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4>Ventas</h4>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <canvas id="ventas">                                                
                                </canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p>Ventas de los últimos meses.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4>Pagos</h4>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <canvas id="pagoschart">                                                
                                </canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p>Pagos recibidos en los últimos meses.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4>Pedidos</h4>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <canvas id="pedidoschart">                                                
                                </canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p>Pedidos de los últimos meses.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4>Clientes</h4>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <canvas id="clientes">                                                
                                </canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p>Nuevos clientes en los últimos meses.</p>
                        </div>
                    </div>
                </div>
               
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4>Productos</h4>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <canvas id="productoschart">                                                
                                </canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p>Nuevos productos en los últimos meses.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4>Devoluciones</h4>
                        </div>
                        <div class="card-content">
                            <div class="ct-chart">
                                <canvas id="devolucionchart">                                                
                                </canvas>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p>Devoluciones en los últimos meses.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection



@section('scripts')

<script src="{{ asset('asset/plugins/chart.js/Chart.min.js') }}"></script>

<script>
    $(document).ready(function () {


        let varVenta = null;
        let charVenta = null;

        let ventas = [];
        let varTotalVenta = [];
        let varMesVenta = [];

        let varPedido = null;
        let charPedido = null;

        let pedidos = [];
        let varTotalPedido = [];
        let varMesPedido = [];

        let varCliente = null;
        let charCliente = null;

        let clientes = [];
        let varTotalClientes = [];
        let varMesCliente = [];

        let varProducto = null;
        let charProducto = null;

        let productos = [];
        let varTotalProductos = [];
        let varMesProducto = [];

        let varPago = null;
        let charPago = null;

        let pagos = [];
        let varTotalPagos = [];
        let varMesPago = [];

        let varDevolucion = null;
        let charDevolucion = null;

        let devoluciones = [];
        let varTotalDevoluciones = [];
        let varMesDevolucion = [];

        $.ajaxSetup({

        headers: {
            'X-CSRF-TOKEN': $("input[name= _token]").val()
        }

        });

        $.ajax({
            type: "GET",
            url: "{{ route('dashboard.ventas') }}",
            dataType: 'json',
            success: function (response) {

                let ventas = response.ventas;
                let pedidos = response.pedidos;
                let clientes = response.clientes;
                let productos = response.productos;
                let pagos = response.pagos;
                let devoluciones = response.devoluciones;

                let meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

                ventas.map(function(x){
                   varMesVenta.push(meses[x.mes-1]);
                   varTotalVenta.push(x.total);
                });

                pedidos.map(function(x){
                   varMesPedido.push(meses[x.mes-1]);
                   varTotalPedido.push(x.total);
                });

                clientes.map(function(x){
                   varMesCliente.push(meses[x.mes-1]);
                   varTotalClientes.push(x.total);
                });

                productos.map(function(x){
                   varMesProducto.push(meses[x.mes-1]);
                   varTotalProductos.push(x.total);
                });

                pagos.map(function(x){
                   varMesPago.push(meses[x.mes-1]);
                   varTotalPagos.push(x.total);
                });

                devoluciones.map(function(x){
                   varMesDevolucion.push(meses[x.mes-1]);
                   varTotalDevoluciones.push(x.total);
                });


               varVenta=document.getElementById('ventas').getContext('2d');
               varPedido=document.getElementById('pedidoschart').getContext('2d');
               varCliente=document.getElementById('clientes').getContext('2d');
               varProducto=document.getElementById('productoschart').getContext('2d');
               varPago=document.getElementById('pagoschart').getContext('2d');
               varDevolucion=document.getElementById('devolucionchart').getContext('2d');

               charVenta = new Chart(varVenta, {
                    type: 'line',
                    data: {
                        labels: varMesVenta,
                        datasets: [{
                            label: 'Ventas',
                            data: varTotalVenta,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });

                charPedido = new Chart(varPedido, {
                    type: 'line',
                    data: {
                        labels: varMesPedido,
                        datasets: [{
                            label: 'Pedidos',
                            data: varTotalPedido,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });

                charCliente = new Chart(varCliente, {
                    type: 'line',
                    data: {
                        labels: varMesCliente,
                        datasets: [{
                            label: 'Clientes',
                            data: varTotalClientes,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });

                charProducto = new Chart(varProducto, {
                    type: 'line',
                    data: {
                        labels: varMesProducto,
                        datasets: [{
                            label: 'Productos',
                            data: varTotalProductos,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });

                charPago = new Chart(varPago, {
                    type: 'line',
                    data: {
                        labels: varMesPago,
                        datasets: [{
                            label: 'Pagos',
                            data: varTotalPagos,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });

                charDevolucion = new Chart(varDevolucion, {
                    type: 'line',
                    data: {
                        labels: varMesDevolucion,
                        datasets: [{
                            label: 'Devoluciones',
                            data: varTotalDevoluciones,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });


            }

        });
       
    });
    
</script>
    
@endsection


