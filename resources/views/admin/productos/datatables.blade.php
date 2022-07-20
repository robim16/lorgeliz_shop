@extends('layouts.admin')


@section('titulo', 'Administración de productos')

@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="{{ route('product.index') }}">Productos</a></li>
@endsection

@section('estilos')
    
    <style type="text/css">
        .table1 {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            text-align: center;
        }

        .table1 td,
        .table1 th {
            padding: .75rem;
            vertical-align: center;
            border-top: 1px solid #dee2e6;
        }

    </style>

@endsection


@section('content')

    <div id="" class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sección de productos</h3>

                    <div class="card-tools">

                        <form>
                            <div class="input-group input-group-sm" style="width: 150px;">
                                {{-- <input type="text" name="busqueda" class="form-control float-right" placeholder="Buscar"
                                    value="{{ request()->get('busqueda') }}">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
                                </div> --}}
                                <a class="m-2 float-right btn btn-primary" href="{{ route('product.create')}}"> <i class="fas fa-plus"></i> Crear</a>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                {{-- <a class="m-2 float-right btn btn-primary" href="{{ route('product.create')}}"> <i class="fas fa-plus"></i> Crear</a> --}}
                    <table id="table-products" class="table1 table-head-fixed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Marca</th>
                                <th>Slider</th>
                                <th colspan="3">Acciones</th>
                            </tr>
                        </thead>
                    
                    </table>
                    
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->

@endsection

@section('scripts')

{{-- @push('scripts') --}}

{{-- <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script> --}}

<script>
	
	// $(document).ready(function() {
    $(function () {
		$("#table-products").DataTable({
			pageLength: 15,
			lengthMenu: [ 5, 10, 15, 20, 30, 40, 25, 50, 75, 100 ],
			// processing: true,
			serverSide: true,
			language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
            },
			ajax: '{{ route('product.view_all') }}',
			
			columns: [
                {data: 'id', name:'id'},
                {data: 'imagen'},
                {data: 'nombre', name:'nombre'},
                {data: 'descripcion_corta', name: 'descripcion_corta'},
                {data: 'marca', name: 'marca'},
                {data: 'slider_principal', name: 'slider_principal'},
                {data: 'actions'},
			
			]
				
		});
    });
	// })
</script>

{{-- @endpush --}}

@endsection