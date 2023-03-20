@extends('layouts.admin')

@section('titulo', 'Registrar guía de envío')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('envios.index') }}">Envíos</a></li>
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')

<div>
    <form action="{{ route('envios.store')}}" method="POST">
        @csrf

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Administrar envíos</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                    title="Collapse"><i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip"
                        title="Remove"><i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
    
                        <div class="form-group">
                            <label for="cliente">Cliente</label>
                            <select name="cliente_id" id="cliente_id" class="form-control"
                                style="width: 100%;">
                                <option value="">Seleccione</option>
                                @foreach($clientes as $cliente)

                                    <option value="{{ $cliente->id }}">
                                        {{ $cliente->user->nombres }} {{ $cliente->user->apellidos }}
                                    </option>

                                @endforeach

                            </select>
                            
                            @if($errors->has('cliente_id'))
                                <small class="form-text text-danger">
                                    {{ $errors->first('cliente_id') }}
                                </small>
                            @endif
        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Venta</label>
                            <select name="venta_id" id="venta_id" class="form-control"
                            style="width: 100%;">
                            <option value="">Seleccione</option>

                        </select>
                            
                            @if($errors->has('venta_id'))
                                <small class="form-text text-danger">
                                    {{ $errors->first('venta_id') }}
                                </small>
                            @endif
        
                        </div>
                       
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
    
                        <div class="form-group">
                            <label for="departamento">Departamento</label>
                            <select name="departamento" id="departamento" class="form-control @error('departamento') is-invalid @enderror" 
                                required autocomplete="departamento">
                                <option value="0">Seleccione uno</option>
                            </select>
                            
                            @error('departamento')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre">Municipio</label>
                            <select name="municipio" id="municipio" class="form-control @error('municipio') is-invalid @enderror" required autocomplete="municipio">
                                <option value="0">Seleccione uno</option>
                            </select>
                            
                            @error('municipio')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
        
                        </div>
                       
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
    
                        <div class="form-group">
                            <label for="nombre">Dirección</label>
                            <input type="text" name="direccion" id="direccion" class="form-control"
                                value="{{ old('direccion') }}" autofocus/>
                            
                            @if($errors->has('direccion'))
                                <small class="form-text text-danger">
                                    {{ $errors->first('direccion') }}
                                </small>
                            @endif
        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="guia">Número de guía</label>
                            <input type="text" name="guia" id="guia" class="form-control"
                                value="{{ old('guia') }}" autofocus/>
                            
                            @if($errors->has('guia'))
                                <small class="form-text text-danger">
                                    {{ $errors->first('guia') }}
                                </small>
                            @endif
        
                        </div>
                       
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
    
                        <div class="form-group">
                            <label for="nombre">Transportadora</label>
                            <select name="transportadora" id="transportadora" class="form-control @error('transportadora') is-invalid @enderror" required autocomplete="transportadora">
                                @foreach ($transportadoras as $transportadora)
                                    <option value="{{ $transportadora }}">{{ $transportadora }}</option>
                                @endforeach
                            </select>
                            
                            @if($errors->has('transportadora'))
                                <small class="form-text text-danger">
                                    {{ $errors->first('transportadora') }}
                                </small>
                            @endif
        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="costo">Costo del envío</label>
                            <input type="number" name="costo" id="costo" class="form-control"
                                value="{{ old('costo') }}" autofocus step="0"/>
                            
                            @if($errors->has('costo'))
                                <small class="form-text text-danger">
                                    {{ $errors->first('costo') }}
                                </small>
                            @endif
        
                        </div>
                       
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
    
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="datetime-local" name="fecha" id="fecha" class="form-control"
                                value="{{ old('fecha') }}" autofocus/>
                            
                            @if($errors->has('fecha'))
                                <small class="form-text text-danger">
                                    {{ $errors->first('fecha') }}
                                </small>
                            @endif
        
                        </div>
                    </div>
                    <div class="col-md-6">
    
                        <div class="form-group">
                           
                            <label for="comentarios">Comentarios</label>
                            <textarea class="form-control" name="comentarios" id="comentarios" cols="30" rows="5">
                                {{ old('comentarios') }}
                            </textarea>
        
                            @if($errors->has('comentarios'))
                                <small class="form-text text-danger">
                                    {{ $errors->first('comentarios') }}
                                </small>
                            @endif
        
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a class="btn btn-danger" href="{{ route('cancelar','category.index') }}">Cancelar</a>
                <input type="submit" value="Guardar" class="btn btn-primary float-right">
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->

    </form>
</div>

@endsection



@section('scripts')
	<script>
		function loadJSON(callback) {
			let ruta = "/lorgeliz_tienda_copia/public";
			let url = `${ruta}/colombia-json-master/colombia-json-master/colombia.json`
			var xobj = new XMLHttpRequest();
			xobj.overrideMimeType("application/json");
			xobj.open("GET", url, true);
			xobj.onreadystatechange = function () {
				if (xobj.readyState == 4 && xobj.status == "200") {
					callback(xobj.responseText);
				}
			};
			xobj.send(null);
		}

		var JSONFinal = '';

		function init() {
			loadJSON(function (response) {

				// Parse JSON string into object
				JSONFinal =  JSON.parse(response);
				const departamentos = JSONFinal.map(d => d.departamento);
				
				$.each(departamentos, function (key, value) {
					$('#departamento').append("<option value='" 
						+ value + "'>" + value + "</option>");
				});
			});
		}
	</script>
	<script>
		$(document).ready(function () {
			
			init();

			$(document).on('change', '#departamento', function(e) { 
				e.preventDefault();

				$('#municipio').html('');

				const departamento = $('#departamento').val();
				const filtrados = JSONFinal.filter(d => d.departamento === departamento);
				const municipios = filtrados[0].ciudades;

				$('#municipio').append('<option value="0">Seleccione uno</option>')
				$.each(municipios, function (key, value) {
					$('#municipio').append("<option value='" 
						+ value + "'>" + value + "</option>");
				});
			});
            

            $(document).on('change', '#cliente_id', function(e) { 
                e.preventDefault();

                var cliente = parseInt($('#cliente_id').val());

                if (cliente != '') {

                    $.ajax({
                        type: "GET",
                        url: 'e/api/admin/ventas/cliente/' + cliente,
                        dataType: 'json',
                        success: function (response) {
                            
                            $('#venta_id').html('');
                            $('#venta_id').append('<option value="0">Seleccione una</option>')
                            $.each(response, function (key, value) {
                                $('#venta_id').append("<option value='" 
                                    + value.id + "'>" + "código: " + value.id + '-' + "fecha: "
                                    + value.fecha + '-' + "valor: $" + value.valor +
                                    "</option>");
                            });
                            
                        }

                    });

                }

            });
		});
	</script>
	
@endsection