@extends('layouts.store') 

@section('estilos')
<link rel="stylesheet" type="text/css" href="{{ asset('asset/styles/cart.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/styles/cart_responsive.css') }}">
    
@endsection

@section('content')
<div class="super_container_inner">
	{{--<div id="carrito">--}}
	<div id="">
		<div class="super_overlay"></div>

		<!-- Home -->

		<div class="home">
			<div class="home_container d-flex flex-column align-items-center justify-content-end">
				<div class="home_content text-center">
					<div class="home_title">Carrito de Compras</div>
					<div class="breadcrumbs d-flex flex-column align-items-center justify-content-center">
						<ul class="d-flex flex-row align-items-start justify-content-start text-center">
						<li><a href="{{ route('home') }}">Inicio</a></li>
							<li>Mi Carrito</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<cart :user_id="{{  auth()->user() ? json_encode(auth()->id()) : '0' }}" :ruta="ruta"></cart>
			<!-- Cart -->
		{{--<div class="cart_section">
			<div class="container">
				@if (count($productos) > 0)
				<div class="row">
					<div class="col">
						<div class="cart_container">
							<!-- Cart Bar -->
							<div class="cart_bar">
								<ul class="cart_bar_list item_list d-flex flex-row align-items-center justify-content-end">
									<li class="mr-auto">Producto</li>
									<li>Color</li>
									<li>Talla</li>
									<li>Precio</li>
									<li>Cantidad</li>
									<li>Total</li>
								</ul>
							</div>

							<!-- Cart Items -->
							<div class="cart_items">
								<ul class="cart_items_list">
									@foreach ($productos as $producto)

									<!-- Cart Item -->
									<li class="cart_item item_list d-flex flex-lg-row flex-column align-items-lg-center 		align-items-start justify-content-lg-end justify-content-start">
										<div class="product d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-start mr-auto">
										<div><div class="product_number">{{ $producto->codigo}}</div></div>
											<div>
												<div class="product_image">
													<img src="{{ url('storage/' . $producto->imagen) }}" alt="">
												</div>
											</div>
											<div class="product_name_container">
											<div class="product_name"><a href="{{ route('producto.show', $producto->slug) }}">{{ $producto->nombre }}</a></div>
												<div class="product_text">{!! $producto->descripcion_corta !!}</div>
											</div>
										</div>
										<div class="product_color product_text">
											<span>Color: </span>
											{{ $producto->color}}
										</div>
										<div class="product_size product_text">
											<span>Talla: </span>{{ $producto->talla }}
										</div>
										<div class="product_price product_text"><span>Precio: </span>
											<div id="precio_{{ $producto->ref}}">{{ floatval($producto->precio_actual) }}</div>
										</div>
										<div class="product_quantity_container">
											<div class="product_quantity ml-lg-auto mr-lg-auto text-center">
												<span class="product_text product_num" id="prod_{{ $producto->ref}}">{{ $producto->cantidad}}</span>
												<div class="qty_sub qty_button trans_200 text-center" id="{{ 		  	
												$producto->ref}}">
													<span>-</span>
												</div>
												<div class="qty_add qty_button trans_200 text-center" id="{{ $producto->ref}}">
													<span>+</span>
												</div>
											</div>
										</div>
										
										<div class="product_total product_text">
											<span>Total: </span>
											<div id="total_{{ $producto->ref}}">{{ floatval($producto->precio_actual * $producto->cantidad) }}</div> 
											
										</div>
										<a href="" v-on:click.prevent="remove({{$producto->ref}})"><i class="fa fa-trash text-danger" style="font-size: 20px" title="quitar del carrito"></i></a>
										{{--<input type="hidden" id="stock_{{ $producto->ref}}" value="{{ $producto->stock}}">--}}
									{{--</li>

									@if ($producto->stock == 0)
									<div class="">
										<span class="float-right text-danger">Este producto se ha agotado!</span>
									</div>
									@else
										@if ($producto->cantidad > $producto->stock)
										<div class="">
											<span class="float-right text-danger">Quedan {{$producto->stock}} unidad(es)!</span>
										</div>
										@endif
									@endif
									
									@endforeach
								</ul>
							</div>

							<!-- Cart Buttons -->
							<div class="cart_buttons d-flex flex-row align-items-start justify-content-start">
								<div class="cart_buttons_inner ml-sm-auto d-flex flex-row align-items-start justify-content-start flex-wrap">
								<div class="button button_clear trans_200"><a href="" v-on:click.prevent="limpiarCarrito({{$producto->carrito}})">limpiar carrito</a></div>
									<div class="button button_continue trans_200"><a href="" id="continuar">continuar comprando</a></div>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="row cart_extra_row">
					<div class="col-lg-6">
						<div class="cart_extra cart_extra_1">
							<div class="cart_extra_content cart_extra_coupon">
								<div class="cart_extra_title">Código de cupón</div>
								<div class="coupon_form_container">
									<form action="#" id="coupon_form" class="coupon_form">
										<input type="text" class="coupon_input" required="required">
										<button class="coupon_button">aplicar</button>
									</form>
								</div>
								<div class="coupon_text">Ingresa tu cupón de compra para obtener un fabuloso descuento!.</div>
								<div class="shipping">
									<div class="cart_extra_title">Método de Envío</div>
									<ul>
										<li class="shipping_option d-flex flex-row align-items-center justify-content-start">
											<label class="radio_container">
												<input type="radio" id="radio_1" name="shipping_radio" class="shipping_radio">
												<span class="radio_mark"></span>
												<span class="radio_text">Entrega al día siguiente</span>
											</label>
											<div class="shipping_price ml-auto">$4.99</div>
										</li>
										<li class="shipping_option d-flex flex-row align-items-center justify-content-start">
											<label class="radio_container">
												<input type="radio" id="radio_2" name="shipping_radio" class="shipping_radio">
												<span class="radio_mark"></span>
												<span class="radio_text">Envío estandar</span>
											</label>
											<div class="shipping_price ml-auto">$1.99</div>
										</li>
										<li class="shipping_option d-flex flex-row align-items-center justify-content-start">
											<label class="radio_container">
												<input type="radio" id="radio_3" name="shipping_radio" class="shipping_radio" checked>
												<span class="radio_mark"></span>
												<span class="radio_text">Personal Pickup</span>
											</label>
											<div class="shipping_price ml-auto">Free</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6 cart_extra_col">
						<div class="cart_extra cart_extra_2">
							<div class="cart_extra_content cart_extra_total">
								<div class="cart_extra_title">Total Carrito</div>
								<ul class="cart_extra_total_list">
									<li class="d-flex flex-row align-items-center justify-content-start">
										<div class="cart_extra_total_title">Subtotal</div>
										<div class="cart_extra_total_value ml-auto" id="subtotal">${{ floatval($producto->total) }}</div>
									</li>
									<li class="d-flex flex-row align-items-center justify-content-start">
										<div class="cart_extra_total_title">Envío</div>
										<div class="cart_extra_total_value ml-auto">Gratis</div>
									</li>
									<li class="d-flex flex-row align-items-center justify-content-start">
										<div class="cart_extra_total_title">Total</div>
										<div class="cart_extra_total_value ml-auto" id="neto">${{ floatval($producto->total) }}</div>
									</li>
								</ul>
								<div class="checkout_button trans_200"><a href="" id="pago">proceder al pago</a></div>
							</div>
						</div>
					</div>
				</div>

				@else
				<div class="alert alert-info pt-5 col-md-7 text-center m-auto">
					<h4 class="alert-heading">{{ __("Aún no tienes productos en tu carrito") }}</h4>
				</div>
				@endif 

			</div>
		</div>--}}
	</div>

@endsection

@section('scripts')
	<script src="{{ asset('asset/js/cart.js') }}"></script>

	<script src="{{ asset('asset/plugins/bootstrap-4.1.2/bootstrap.min.js') }}"></script>
	<script src="{{ asset('asset/js/bootbox.min.js') }}"></script>


{{--<script>
	//let producto = [];
	//let producto = 0;
	let cantidad = 0;
	//let unidades = [];
	//let i = 0;
	//let opcion = 0;
	let operacion = '';
	let id;

	$(document).ready(function () {
		$('.qty_add').click(function (e) { 
			e.preventDefault();
			
			id = $(this).attr('id');
			
			operacion = 1;

			//arrayProductos(id,operacion);
			updateCar();
		});
		
		$('.qty_sub').click(function (e) { 
			e.preventDefault();

			id = $(this).attr('id');
			
			operacion = 2;
			
			//arrayProductos(id,operacion);
			updateCar();
		});

		$('#pago').click(function (e) { 
			e.preventDefault();

			window.location.href = `/lorgeliz_tienda_copia/public/checkout`;
			//opcion = 1;
			//updateCar();
		});

		$('#continuar').click(function (e) { 
			e.preventDefault();
			
			window.location.href = `/lorgeliz_tienda_copia/public/`;
			//opcion = 2;
			//updateCar();
		});
		
	});

	function arrayProductos(id,operacion){
		//let cantidad = parseInt($('#prod_' + id).html());
		//let stock = parseInt($('#stock_' + id).val());
		cantidad = parseInt($('#prod_' + id).html());
		let precio = parseInt($('#precio_' +id).html());
		let total = cantidad * precio;

		//producto = id;

		//if (cantidad <= stock) {

		$('#total_' + id).html(total);

		let neto = parseInt($('#neto').html());
		let totalneto = 0;

		if (operacion == 1) {
			totalneto = neto + precio;
		} else {
			totalneto = neto - precio;
		}
		
		$('#subtotal').html(totalneto);

		$('#neto').html(totalneto);

			//let count = 0;
			//for (let j = 0; j < producto.length; j++) {
				//if (producto[j] == id) {
					//unidades[j] = cantidad;
					//count++;
				//}
			//}
			//if (count == 0) {
				//producto[i] = id;
				//unidades[i] = cantidad;
				//i++;
			//}
		//} else {
			//swal(
				//'Stock limitado!',
				//'Puedes agregar máximo ' + stock + ' unidades de este producto!',
				//'error'
			//)
			//var newValue = cantidad - (cantidad - stock);
			
			//$('#prod_' + id).text(newValue);
		//}
	}

	function updateCar() {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $("input[name= _token]").val()
			}
		});

		//if (producto.length > 0 && unidades.length > 0) {
		if (id != '' && operacion != '') {
			$.ajax({
			type: "POST",
			url: "{{ route('cart.updateProduct') }}",
			data:{producto:id,
			operacion: operacion},
			//data:{producto:producto,
			//unidades: unidades},
			dataType: 'json',
			success: function (response) {
				if (response.data == 'success') {
					//if (opcion == 1) {
						//window.location.href = `/lorgeliz_tienda_copia/public/checkout`;
					//} else {
						//window.location.href = `/lorgeliz_tienda_copia/public/`;
					//}
					arrayProductos(id,operacion);
				}
				else{
					var newValue = parseInt($('#prod_' + id).html()) - 1;
			
					$('#prod_' + id).text(newValue);
					swal(
						'Stock limitado!',
						'No puedes añadir más unidades de este producto a tu carrito!',
						'error'
					)
				}
			}

			});
		}

		//else{

			//if (opcion == 1) {
				//window.location.href = `/lorgeliz_tienda_copia/public/checkout`;
			//} else {
				//window.location.href = `/lorgeliz_tienda_copia/public/`;
			//}
		//}
	}
</script>--}}
@endsection





		