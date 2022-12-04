@extends('layouts.app')
@section('content')
<div class="container-fluid pt-5">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header" align="center" font-size="3rem"><b>{{ __('Formulario de Registro') }}</b></div>

				<div class="card-body">
					<form method="POST" action="{{ route('register') }}" novalidate enctype="multipart/form-data">
						@csrf

						<div class="form-group row">
							<label for="nombres" class="col-md-4 col-form-label text-md-right">{{ __('Nombres') }}</label>

							<div class="col-md-6">
								<input id="nombres" type="text" class="form-control @error('nombres') is-invalid @enderror" name="nombres" value="{{ old('nombres') }}" required autocomplete="nombres" autofocus>

								@error('nombres')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="apellidos" class="col-md-4 col-form-label text-md-right">{{ __('Apellidos') }}</label>

							<div class="col-md-6">
								<input id="apellidos" type="text" class="form-control @error('apellidos') is-invalid @enderror" name="apellidos" value="{{ old('apellidos') }}" required autocomplete="apellidos" autofocus>

								@error('apellidos')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
                        </div>
                        
                        <div class="form-group row">
							<label for="identificacion" class="col-md-4 col-form-label text-md-right">{{ __('identificacion') }}</label>

							<div class="col-md-6">
								<input id="identificacion" type="text" class="form-control @error('identificacion') is-invalid @enderror" name="identificacion" value="{{ old('identificacion') }}" required autocomplete="identificacion" autofocus>

								@error('identificacion')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="departamento" class="col-md-4 col-form-label text-md-right">{{ __('Departamento') }}</label>

							<div class="col-md-6">
								<select name="departamento" id="departamento" class="form-control @error('departamento') is-invalid @enderror" required autocomplete="departamento">
									<option value="0">Seleccione uno</option>
								</select>
								
								@error('departamento')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="municipio" class="col-md-4 col-form-label text-md-right">{{ __('Municipio') }}</label>

							<div class="col-md-6">
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

						<div class="form-group row">
							<label for="direccion" class="col-md-4 col-form-label text-md-right">{{ __('Dirección') }}</label>

							<div class="col-md-6">
								<input id="direccion" type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" value="{{ old('direccion') }}" required autocomplete="direccion" autofocus>

								@error('direccion')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="telefono" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>

							<div class="col-md-6">
								<input id="telefono" type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}" required autocomplete="telefono" autofocus>

								@error('telefono')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

							<div class="col-md-6">
								<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

								@error('email')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>


						<div class="form-group row">
							<label for="user" class="col-md-4 col-form-label text-md-right">{{ __('Usuario') }}</label>

							<div class="col-md-6">
								<input id="user" type="text" class="form-control @error('user') is-invalid @enderror" name="user" required autocomplete="user">

								@error('user')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

							<div class="col-md-6">
								<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

								@error('password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmación de contraseña') }}</label>

							<div class="col-md-6">
								<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
							</div>
						</div>
						
						<div class="form-group d-flex justify-content-center pl-5 ml-5">
							<div class="col-md-6">
								<input
								type="file"
								class="custom-file-input"
								id="imagen"
								name="imagen"
								accept="image/*"
								/>
								<label
								class="custom-file-label" for="imagen"
								>
								{{ __("Imagen del perfil") }}
							</label>
						</div>
					</div>
					<div class="form-group row mb-1">
						<div class="col-md-4 offset-md-4">
							<button type="submit" class="btn btn-success btn-block" style="margin: 25px 0">
								{{ __('Registrarme') }}
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
@endsection

@section('scripts')
<script>
    function loadJSON(callback) {
		let ruta = 'http://lorenzogeliztienda.com'
        let url = `${ruta}/colombia-json-master/colombia-json-master/colombia.json`
        var xobj = new XMLHttpRequest();
        xobj.overrideMimeType("application/json");
        // xobj.open("GET", "/lorgeliz_tienda_copia/public/colombia-json-master/colombia-json-master/colombia.json", true); // Reemplaza colombia-json.json con el nombre que le hayas puesto
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
       
	});
</script>
	
@endsection