@extends('layouts.admin')


@section('titulo', 'Administración de Tipos de productos')

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('estilos')
    <!-- Select2 -->
    {{-- <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}"> --}}
@endsection


@section('content')


<div id="index" class="row">

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sección de tipos de productos</h3>

                <div class="card-tools">

                    <form>
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="nombre" class="form-control float-right" placeholder="Buscar"
                            value="{{ request()->get('nombre') }}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
            <a class=" m-2 float-right btn btn-primary" href="{{ route('tipo.create') }}">Crear</a>
                <table class="table table-head-fixed">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th colspan="4"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($tipos as $tipo)

                        <tr>
                            <td> {{$tipo->id }} </td>
                            <td> {{$tipo->nombre }} </td>
                            <td> {{$tipo->descripcion }} </td>
                            <td> {{$tipo->subcategoria->categoria->nombre }} </td>
                            <td> {{$tipo->subcategoria->nombre }} </td>
                            
                            <td> 
                                <a class="btn btn-primary btn-sm btn-icon" href="{{ route('tipo.show', $tipo->slug)}}" title="ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>

                            <td> 
                                <a class="btn btn-success btn-sm btn-icon" href="{{ route('tipo.edit', $tipo->slug)}}" title="editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </td>

                            <td>
                                @include('admin.tipo_producto.delete')
                            </td>
                            <td> 
                                <a class="btn btn-default btn-sm btn-icon" href="" title="agregar tallas" data-toggle="modal"
                                    data-target="#modalTallas" data-id="{{$tipo['id']}}"><i class="fas fa-plus"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
                {{ $tipos->appends($_GET)->links() }}
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- /.row -->

<div class="modal fade" id="modalTallas" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-primary">
				<h5 class="modal-title" id="appModalLabel">Seleccionar las tallas</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

                <form id='formEstado' class="form-horizontal" action="{{ route('talla.store')}}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-4 form-control-label" for="text-input">Tallas del Producto</label>
                        <div class="col-md-8">
                            <select name="tallas_id[]" id="tallas_id" class="form-control select2" multiple="multiple">
                                @foreach(\App\Talla::pluck('nombre', 'id') as $id => $nombre)
                                    <option value="{{ $id }}" class="option">
                                        {{$nombre}}

                                    </option>
                                @endforeach
                            
                            </select>

                            @if($errors->has('tallas_id'))
                            <small class="form-text text-danger">
                                {{ $errors->first('tallas_id') }}
                            </small>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="tipo_id" id="tipo_id" value=""/>

                    <button type="submit" class="btn btn-primary float-left" id="aceptar">Aceptar <i
                            class="far fa-paper-plane"></i></button>
                    <button type="reset" class="btn btn-danger float-right" id="rechazar">Cancelar</button>
				</form>

			</div>

			<div class="modal-footer">

			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

    <script>

        $('#tallas_id').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

    </script>


    <script>

    $(document).ready(function () {

        $('#tallas_id').select2()

        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
        
        $('.btn-default').click(function (e) { 
            e.preventDefault();

            var id = jQuery(this).data('id');
            $('#tipo_id').val(id);

            $("#tallas_id").select2().val(null).trigger("change");
            
            // $(".option").each(function() {     
            //     $(this).prop('selected',false);                   
            // });

        
            //obtiene las tallas del tipo de producto
            $.ajax({
                type: "GET",
                url:'http://127.0.0.1:8000/api/admin/tallas/tipos/get',
                data:{id:id},
                dataType: 'json',
                success: function (response) {
                    
                    if (response.length > 0) {
                    
                        $.each(response, function (key, value) {

                            //muestra seleccionadas las tallas que ya existen del tipo de producto
                            $(".option").each(function() {
                                const tipo = parseInt($(this).val());
                                if (tipo == value.talla_id) {
                                    $(this).prop('selected',true);
                                }
                            
                            });

                        });

                    }
                }

            });

        });

    });
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
@endsection