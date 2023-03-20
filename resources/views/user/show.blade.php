@extends('layouts.account')

@section('title')
<h1 class="m-0 text-dark"> Mi perfil </h1>
@endsection

@section('breadcumb')
<li class="breadcrumb-item">Mi cuenta</li>
@endsection

@section('content')

<!-- Main content -->
<div class="content">
    <div class="container">
        <div class="row mb-4">
            <div class="pt-1 mx-auto d-flex">
                <img src="{{ auth()->user()->imagene ? url('storage/' . auth()->user()->imagene->url) : '' }}" alt="{{ auth()->user()->nombres }}"
                    class="rounded-circle image-responsive">
                <h2 style="text-align: center" class="pt-4 pb-4"> {{ auth()->user()->nombres}}</h2>
            </div>
        </div>

        <div class="pl-5 pr-5">
        <form method="POST" action="{{ route('users.update', $user->id)}}" novalidate enctype="multipart/form-data">
                @method('PUT')

                @csrf

                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header text-center">
                                {{ __("Información de acceso") }}
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">
                                        {{ __("Correo electrónico") }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" readonly
                                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            name="email" value="{{ $user->email}}" required autofocus />

                                        @if($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">
                                        {{ __("Contraseña") }}
                                    </label>

                                    <div class="col-md-6">
                                        <input id="password" type="password"
                                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            name="password" required />

                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                        {{ __("Confirma la contraseña") }}
                                    </label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div><br>

                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header text-center">{{ __("Información del perfil") }}</div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="usuario" class="col-md-4 col-form-label text-md-right">
                                        {{ __("Usuario") }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="usuario" type="text"
                                            class="form-control{{ $errors->has('usuario') ? ' is-invalid' : '' }}"
                                            name="usuario" value="{{ old('usuario') ?: $user->username }}" required />

                                        @if($errors->has('usuario'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('usuario') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="nombres" class="col-md-4 col-form-label text-md-right">
                                        {{ __("Nombres") }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="nombres" type="text"
                                            class="form-control{{ $errors->has('nombres') ? ' is-invalid' : '' }}"
                                            name="nombres" value="{{ old('nombres') ?: $user->nombres }}" required />

                                        @if($errors->has('nombres'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('nombres') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="apellidos" class="col-md-4 col-form-label text-md-right">
                                        {{ __("Apellidos") }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="apellidos" type="text"
                                            class="form-control{{ $errors->has('apellidos') ? ' is-invalid' : '' }}"
                                            name="apellidos" value="{{ old('apellidos') ?: $user->apellidos }}" required />

                                        @if($errors->has('apellidos'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('apellidos') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="departamento" class="col-md-4 col-form-label text-md-right">
                                        {{ __("Departamento") }}
                                    </label>
                                    <div class="col-md-6">
                                        <select id="departamento" 
                                            class="form-control"
                                            name="departamento">
                                            {{-- <option value="0">Seleccione uno</option> --}}
                                            <option value="{{ old('departamento') ?: $user->departamento }}">{{ old('departamento') ?: $user->departamento }}</option>
                                        </select>

                                        @if($errors->has('departamento'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('departamento') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="municipio" class="col-md-4 col-form-label text-md-right">
                                        {{ __("Municipio") }}
                                    </label>
                                    <div class="col-md-6">
                                        <select id="municipio" 
                                            class="form-control"
                                            name="municipio">
                                            {{-- <option value="0">Seleccione uno</option> --}}
                                            <option value="{{ old('municipio') ?: $user->municipio }}">{{ old('municipio') ?: $user->municipio }}</option>
                                        </select>

                                        @if($errors->has('municipio'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('municipio') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="direccion" class="col-md-4 col-form-label text-md-right">
                                        {{ __("Dirección") }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="direccion" type="text"
                                            class="form-control{{ $errors->has('direccion') ? ' is-invalid' : '' }}"
                                            name="direccion" value="{{ old('direccion') ?: $user->direccion}}" required />

                                        @if($errors->has('direccion'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('direccion') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="telefono" class="col-md-4 col-form-label text-md-right">
                                        {{ __("Teléfono") }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="telefono" type="text"
                                            class="form-control{{ $errors->has('telefono') ? ' is-invalid' : '' }}"
                                            name="telefono" value="{{  old('telefono') ?: $user->telefono }}" required />

                                        @if($errors->has('telefono'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('telefono') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                            </div>


                            <div class="form-group ml-3 mr-2 row">
                                <div class="col-md-6 offset-4">
                                    <input type="file" class="custom-file-input" id="imagen" name="imagen" />
                                    <label class="custom-file-label" for="picture">
                                        {{ "Imagen" }}
                                    </label>

                                    @if ($errors->has('imagen'))
                                    <small class="form-text text-danger">
                                        {{ $errors->first('imagen') }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <div class="text-center">
                                    <button type="submit" name="revision" class="btn btn-success">
                                        <b>{{ __("Actualizar perfil") }}</b>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->


@endsection

@section('scripts')
<script>
    function loadJSON(callback) {
        // let url = "/lorgeliz_tienda_copia/public/colombia-json-master/colombia-json-master/colombia.json"
        
        let ruta = '/lorgeliz_tienda_copia/public'
        let url = `${ruta}/colombia-json-master/colombia-json-master/colombia.json`

        var xobj = new XMLHttpRequest();
        xobj.overrideMimeType("application/json");
        xobj.open("GET", url, true); // Reemplaza colombia-json.json con el nombre que le hayas puesto
        xobj.onreadystatechange = function () {
            if (xobj.readyState == 4 && xobj.status == "200") {
                callback(xobj.responseText); //el callback recibe por parámetro el response de la petición
            }
        };
        xobj.send(null);
    }

    // var JSONFinal = '';

    function init() {
        return new Promise(async (resolve, reject) => {
            await loadJSON(function (response) {

                // Parse JSON string into object
                const JSONFinal =  JSON.parse(response);
                const departamentos = JSONFinal.map(d => d.departamento);

                $.each(departamentos, function (key, value) {
                    $('#departamento').append("<option value='" 
                        + value + "'>" + value + "</option>");
                });

                resolve(JSONFinal);
            });
           
        });
    }

    function setMunicipios(JSONFinal) {
        const departamento = $('#departamento').val();
        const filtrados = JSONFinal.filter(d => d.departamento === departamento);
        const municipios = filtrados[0].ciudades;

        $('#municipio').append('<option value="">Seleccione uno</option>')
        $.each(municipios, function (key, value) {
            $('#municipio').append("<option value='" 
                + value + "'>" + value + "</option>");
        });
    }

</script>
<script>
	$(document).ready(function () {
       
        var JSONFinal = '';
        init().
        then((data)=>{
            JSONFinal = data;
            setMunicipios(JSONFinal);
        });

        $(document).on('change', '#departamento', function(e) { 
			e.preventDefault();
           
            $('#municipio').html('');
            setMunicipios(JSONFinal);
		});

	});
</script>
@endsection