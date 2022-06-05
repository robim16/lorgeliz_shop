@extends('layouts.admin')

@section('titulo', 'Crear tipo de producto')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tipo.index') }}">Tipo de Producto</a></li>
<li class="breadcrumb-item active">@yield('titulo')</li>
@endsection


@section('content')

<div id="category">
<form action="{{ route('tipo.store') }}" method="POST">
        @csrf

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Administrar tipos</h3>

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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                            value="{{ old('nombre') }}"
                            autofocus/>
                            
                            @if($errors->has('nombre'))
                            <small class="form-text text-danger">
                                {{ $errors->first('nombre') }}
                            </small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="category_id">Categoría</label>
                            <select name="category_id" id="category_id" class="form-control "
                                style="width: 100%;">
                                <option value="{{ "0" }}">
                                    {{ "Seleccione una" }}
                                </option>
                                @foreach(\App\Categoria::pluck('nombre', 'id') as $id => $categoria)

                                <option value="{{ $id }}">
                                    {{ $categoria }}
                                </option>

                                @endforeach

                            </select>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="subcategory_id">Subcategoría</label>
                            <select name="subcategory_id" id="subcategory_id" class="form-control"
                                style="width: 100%;">

                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="5">
                            {{ old('descripcion') }}
                            </textarea>
                
                            @if($errors->has('descripcion'))
                            <small class="form-text text-danger">
                                {{ $errors->first('descripcion') }}
                            </small>
                            @endif
                        </div>
                    </div>
                </div>


            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a class="btn btn-danger" href="{{ route('cancelar','tipo.index') }}">Cancelar</a>
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
	$(document).ready(function () {
		
		$.ajaxSetup({

			headers: {
				'X-CSRF-TOKEN': $("input[name= _token]").val()
			}
		});

		$(document).on('change', '#category_id', function(e) { 
			e.preventDefault();

			var categoria = parseInt($('#category_id').val());

			if (categoria != 0) {

				$.ajax({
					type: "GET",
					// url: "route('subcategory.get')",
                    url: 'http://lorenzogeliztienda.com/api/admin/subcategorias',
					data:{categoria:categoria},
					dataType: 'json',
					success: function (response) {

						$('#subcategory_id').html('');
						$('#subcategory_id').append('<option value="0">Seleccione una</option>')
						
						$.each(response.data, function (key, value) {
							$('#subcategory_id').append("<option value='" 
								+ value.id + "'>" + value.nombre + "</option>");
						});
						
					}

				});

			}

		});

	});
</script>
    
@endsection
