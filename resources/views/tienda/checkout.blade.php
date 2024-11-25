@extends('layouts.store')

@section('estilos')
	<link rel="stylesheet" type="text/css" href="{{ asset('asset/styles/checkout.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('asset/styles/checkout_responsive.css') }}">
@endsection

@section('content')

<div class="super_container_inner">
	<div>
		<div class="super_overlay"></div>

		<!-- Home -->

		<div class="home">
			<div class="home_container d-flex flex-column align-items-center justify-content-end">
				<div class="home_content text-center">
					<div class="home_title">Pagar Pedido</div>
					<div class="breadcrumbs d-flex flex-column align-items-center justify-content-center">
						<ul class="d-flex flex-row align-items-start justify-content-start text-center">
						<li><a href="{{ route('home')}}">Inicio</a></li>
							<li>Pagar Pedido</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<!-- Checkout -->
		{{-- <div class="checkout">
			<div class="container">
				<div class="row">
				
					<div class="col-lg-6">
						<div class="billing">
							<div class="checkout_title">Información de Pago</div>
							<div class="checkout_form_container">
								<form action="#" id="checkout_form" class="checkout_form">
									<div class="row">
										<div class="col-lg-6">
											
											<label for="checkout_name">Nombres</label>
											<input type="text" id="checkout_name" class="checkout_input" placeholder="Nombres" required="required" value="{{ $carrito->cliente->user->nombres}}">
										</div>
										<div class="col-lg-6">
											
											<label for="checkout_last_name">Apellidos</label>
											<input type="text" id="checkout_last_name" class="checkout_input" placeholder="Apellidos" required="required" value="{{ $carrito->cliente->user->apellidos}}">
										</div>
									</div>
									<div>
										
										<label for="checkout_company">Empresa</label>
										<input type="text" id="checkout_company" placeholder="Empresa" class="checkout_input">
									</div>
									<div>
										
										<label for="checkout_country">País</label>
										<select name="checkout_country" id="checkout_country" class="dropdown_item_select checkout_input" require="required">
											
											<option>Colombia</option>
										</select>
									</div>
									<div>
								
										<label for="checkout_province">Departamento</label>
										<select name="checkout_province" id="checkout_province" class="dropdown_item_select checkout_input" require="required">
											<option value="{{ $carrito->cliente->user->departamento}}" selected>{{ $carrito->cliente->user->departamento }}</option>
										
										</select>
									</div>
									<div>
										
										<label for="checkout_city">Ciudad</label>
										<select name="checkout_city" id="checkout_city" class="dropdown_item_select checkout_input" require="required">
											<option value="{{ $carrito->cliente->user->municipio }}" selected> {{ $carrito->cliente->user->municipio}}</option>
											
										</select>
									</div>
									<div>
										
										<label for="checkout_address">Dirección del envío</label>
										<input type="text" id="checkout_address" class="checkout_input" placeholder="Dirección" required="required" value="{{ $carrito->cliente->user->direccion}}">
										
										<a href="" class="text-primary" data-toggle="modal" data-target="#modalDir">cambiar</a>
									</div>
									
									<div>
									
										<label for="checkout_phone">Teléfono</label>
										<input type="phone" id="checkout_phone" class="checkout_input" placeholder="Teléfono" required="required" value="{{ $carrito->cliente->user->telefono}}">
									</div>
									<div>
										
										<label for="checkout_email">Email</label>
										<input type="phone" id="checkout_email" class="checkout_input" placeholder="Email" required="required" value="{{ $carrito->cliente->user->email}}">
									</div>
									<div class="checkout_extra">
										<ul>
											<li class="billing_info d-flex flex-row align-items-center justify-content-start">
												<label class="checkbox_container">
													<input type="checkbox" id="cb_1" name="billing_checkbox" class="billing_checkbox">
													<span class="checkbox_mark"></span>
													<span class="checkbox_text">Términos y condiciones</span>
												</label>
											</li>
											
											<li class="billing_info d-flex flex-row align-items-center justify-content-start">
												<label class="checkbox_container">
													<input type="checkbox" id="cb_3" name="billing_checkbox" class="billing_checkbox">
													<span class="checkbox_mark"></span>
													<span class="checkbox_text">Suscríbete a nuestro boletín</span>
												</label>
											</li>
										</ul>
									</div>
								</form>
							</div>
						</div>
					</div>

					<div class="col-lg-6 cart_col">
						<div class="cart_total">
							<div class="cart_extra_content cart_extra_total">
								<div class="checkout_title">Total carrito</div>
								<ul class="cart_extra_total_list">
									<li class="d-flex flex-row align-items-center justify-content-start">
										<div class="cart_extra_total_title">Subtotal</div>
										<div class="cart_extra_total_value ml-auto">${{ floatval($carrito->subtotal)}}</div>
									</li>
									<li class="d-flex flex-row align-items-center justify-content-start">
										<div class="cart_extra_total_title">Envío</div>
										<div class="cart_extra_total_value ml-auto">${{ floatval($carrito->envio)}}</div>
									</li>
									<li class="d-flex flex-row align-items-center justify-content-start">
										<div class="cart_extra_total_title">Total</div>
										<div class="cart_extra_total_value ml-auto">${{ floatval($carrito->total)}}</div>
									</li>
								</ul>
								<checkout :ruta="ruta"/>	
							</div>
						</div>
					</div>
				</div>
			</div>
        </div> --}}

		<checkout :ruta="ruta" :carrito="{{ $carrito }}" :factura="{{ $factura }}"
			:direcciones="{{ $direcciones }}"/>	

	</div>

	{{-- <div class="modal fade" id="modalDir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-primary modal-lg pt-5" role="document">
			<div class="modal-content pt-3">
				<div class="modal-header">
					<h4 class="modal-title">Seleccionar dirección de envío del pedido</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div id="alerta" class="alert alert-success alert-dismissible fade show d-none" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<p>{{ 'Se ha actualizado la dirección de envío del pedido' }}</p>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-sm">
							<thead>
								<tr>
									<th>Dirección</th>
									<th>Descripción</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($direcciones as $direccion)
									<tr>
										<td>{{ $direccion->direccion}}</td>
										<td>{{ $direccion->descripcion}}</td>
										<td>
											<a href="javascript:void(0)" onclick="selectDirection({{$direccion->id}})" class="btn btn-primary btn-sm btn-icon" title="recibir pedido aquí">
												<i class="fa fa-check"></i>
											</a>
										</td>
									</tr>                                
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div> --}}
	
@endsection

@section('scripts')
	<script src="{{ asset('asset/js/checkout.js') }}"></script>

	{{-- <script>

		let ruta = 'http://127.0.0.1:8000';
		let url = `${ruta}/colombia-json-master/colombia-json-master/colombia.json`

		function loadJSON(callback) {
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


		function init() {
			return new Promise(async (resolve, reject) => {
				await loadJSON(function (response) {

					// Parse JSON string into object
					const JSONFinal =  JSON.parse(response);
					const departamentos = JSONFinal.map(d => d.departamento);

					$.each(departamentos, function (key, value) {
					$('#checkout_province').append("<option value='" 
						+ value + "'>" + value + "</option>");
					});

					resolve(JSONFinal);
				});
			
			});
		}

		function setMunicipios(JSONFinal) {
			
			const departamento = $('#checkout_province').val();
			const filtrados = JSONFinal.filter(d => d.departamento === departamento);
			const municipios = filtrados[0].ciudades;
			$('#checkout_city').append('<option value="0">Seleccione uno</option>')
			$.each(municipios, function (key, value) {
				$('#checkout_city').append("<option value='" 
					+ value + "'>" + value + "</option>");
			});
		}


		function selectDirection(direccion) {
			axios.post("http://127.0.0.1:8000/direcciones/seleccionar", {direccion})
				.then(response => {
					var element = document.getElementById("alerta");
					element.classList.remove("d-none");

					setTimeout(() => {
						element.classList.add("d-none");
					}, 6000);
				}).catch(err => {
					console.log(err);
				})
		}

	</script> --}}

	{{-- <script>
		window.data = {
			datos: {
				"amount": "{{$carrito->total}}",
				"name_billing": "{{$carrito->cliente->user->nombres}} {{$carrito->cliente->user->apellidos}}",
				"address_billing": "{{$carrito->cliente->user->direccion}}",
				"mobilephone_billing": "{{$carrito->cliente->user->telefono}}",
				"number_doc_billing": "{{$carrito->cliente->user->identificacion}}",
				"factura": "{{ $factura->id }}"
			}
		}
	</script> --}}

	{{-- <script> 

		document.addEventListener("DOMContentLoaded", function(event) {
			var JSONFinal = '';

			init().
			then((data) => {
				JSONFinal = data;
				setMunicipios(JSONFinal);
			});

			$(document).on('change', '#checkout_province', function(e) { 
				e.preventDefault();
				
				$('#checkout_city').html('');
				setMunicipios(JSONFinal);
			});
					

		});

	</script> --}}

	<script type="text/javascript" src="https://checkout.epayco.co/checkout.js"></script>

@endsection

