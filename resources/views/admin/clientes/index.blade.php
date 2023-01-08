@extends('layouts.admin')


@section('titulo', 'Información de Clientes')

@section('breadcrumb')
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')

<div id="listclientes" class="row">

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sección de clientes</h3>
                <div class="card-tools">
                    <form>
                        <div class="input-group input-group-sm" style="width: 160px;">
                            <div class="input-group-append">
                                <a href="" class="btn btn-success mx-1" @click.prevent="pdfListadoClientes">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                            <input type="text" name="keyword" class="form-control float-right" placeholder="Buscar"
                            value="{{ request()->get('keyword') }}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Cédula</th>
                            <th>Departamento</th>
                            <th>municipio</th>
                            <th>Dirección</th>
                            <th>Telefóno</th>
                            <th>Email</th>
                            <th colspan="2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($clientes as $cliente)

                        <tr>
                            <td> {{$cliente->cliente->id }} </td>
                            <td> {{$cliente->nombres }} </td> 
                            <td> {{$cliente->apellidos }} </td>
                            <td> {{$cliente->identificacion }} </td>
                            <td> {{$cliente->departamento }} </td>
                            <td> {{$cliente->municipio }} </td>
                            <td> {{$cliente->direccion }}</td>
                            <td> {{$cliente->telefono }} </td>
                            <td> {{$cliente->email }} </td>

                            <td> <a class="btn btn-primary btn-sm btn-icon" href="{{ route('cliente.show', $cliente->cliente->id)}}" title="ver cliente"><i class="fas fa-eye"></i></a></td>
                            <td><a href="" class="btn btn-success btn-sm btn-icon" title="enviar mensaje" data-toggle="modal"
                                data-target="#modalMensaje" data-id="{{$cliente->cliente['id']}}"><i class="fa fa-envelope-square"></i></a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                {{ $clientes->appends($_GET)->links() }}
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- /.row -->

<div class="modal fade" id="modalMensaje" tabindex="-1" role="dialog" aria-labelledby="appModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appModalLabel">Enviar mensaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="formCliente">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Mensaje:</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="mensaje" id="mensaje"></textarea>
                            <input type="hidden" name="cliente_id" id="cliente_id" value="">
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Cerrar") }}</button>
            <button type="button" class="btn btn-primary" id="modalAction">{{"Aceptar"}}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            
            $('.btn-success').click(function (e) { 
                e.preventDefault();

                const id = jQuery(this).data('id');
                $('#cliente_id').val(id);
            });

            $('#modalAction').click(function (e) { 
                e.preventDefault();
               
                let modal = jQuery("#modalMensaje");

                $.ajax({
                    type: "POST",
                    url: "{{ route('cliente.message')}}",
                    data: {
                        info: $('#formCliente').serialize(),
                    },
                    headers: {
                    'x-csrf-token': $("meta[name=csrf-token]").attr('content')
                    },
                    success: function (response) {

                        console.log(response)
                        
                        if (response.response) {
                            modal.find('#modalAction').hide();
                            modal.find('.modal-body').html('<div class="alert alert-success">Mensaje enviado</div>')
                        }
                        else{
                            modal.find('.modal-body').html('<div class="alert alert-danger">Ha ocurrido un error</div>')
                        }
                    }
                });
             
            });
        });
    </script>
@endsection