
@extends('layouts.store')

@section('estilos')
{{--<link rel="stylesheet" type="text/css" href="{{ asset('asset/plugins/OwlCarousel2-2.2.1/owl.carousel.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/plugins/OwlCarousel2-2.2.1/owl.theme.default.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/plugins/OwlCarousel2-2.2.1/animate.css') }}">--}}


<link rel="stylesheet" type="text/css" href="{{ asset('asset/styles/category.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('asset/styles/category_responsive.css') }}">

@endsection

@section('content')
<div class="super_container_inner">
	<div class="super_overlay"></div>

	<!-- Home -->

	<div class="home">
		<div class="home_container d-flex flex-column align-items-center justify-content-end">
			<div class="home_content text-center">
				<div class="home_title">Página de Categorías</div>
				<div class="breadcrumbs d-flex flex-column align-items-center justify-content-center">
					<ul class="d-flex flex-row align-items-start justify-content-start text-center">
						<li><a href="{{ route('home')}}">Inicio</a></li>
						<li><a href="{{ route('categorias')}}">Categorías</a></li>
						<li>Todos</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div id="">
		<category :keyword="keyword" :ruta="ruta"></category>
	</div>
	<!-- Products -->

	{{--<div class="products" id="categoria">
		<div class="container">
			<div class="row products_bar_row">
				<div class="col">
					<div class="products_bar d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-start justify-content-center">
						<div class="products_bar_links">
							<ul class="d-flex flex-row align-items-start justify-content-start">
								<li :class="listar == 7 ? 'active' : ''"><a href="" v-on:click.prevent="getproductos(1)">Todos</a></li>
								<li :class="listar == 4 ? 'active' : ''"><a href="" v-on:click.prevent="hotProducts(1)">Populares</a></li>
								<li :class="listar == 6 && estado == 1 ? 'active' : ''"><a href="" v-on:click.prevent="getProductByState(1,1)">Nuevos</a></li>
								<li :class="listar == 5 ? 'active' : ''"><a href="" v-on:click.prevent="saleProductos(1)">Más Vendidos</a></li>
								<li :class="listar == 6 && estado == 2 ? 'active' : ''"><a href="" v-on:click.prevent="getProductByState(1,2)">Ofertas</a></li>
							</ul>
							
						</div>
						<div class="products_bar_side d-flex flex-row align-items-center justify-content-start ml-lg-auto">
							<div class="products_dropdown product_dropdown_sorting">
								<div class="isotope_sorting_text"><span>Ordenar Por</span><i class="fa fa-caret-down" aria-hidden="true"></i></div>
								<ul>
									<li class="item_sorting_btn" v-on:click.prevent="getproductos(1)">Default</li>
									<li class="item_sorting_btn" v-on:click.prevent="getProductsByOrder(1,1)">Precio</li>
									<li class="item_sorting_btn" v-on:click.prevent="getProductsByOrder(1,2)">Nombre</li>
								</ul>
							</div>

							
							<div class="products_dropdown text-right product_dropdown_filter">
								<div class="isotope_filter_text"><span>Género</span><i class="fa fa-caret-down" aria-hidden="true"></i></div>
								<ul>
									<li class="item_filter_btn" v-on:click.prevent="getProductByGenre(1,1)">Masculino</li>
									<li class="item_filter_btn" v-on:click.prevent="getProductByGenre(1,2)">Femenino</li>
									<li class="item_filter_btn" v-on:click.prevent="getProductByGenre(1,3)">Niños</li>
									
								</ul>
							</div>
							<div class="products_dropdown text-right product_dropdown_filter">
								<div class="isotope_filter_text"><span>Filtrar</span><i class="fa fa-caret-down" aria-hidden="true"></i></div>
								<ul>
									<li class="item_filter_btn" v-on:click.prevent="getproductos(1)">Todos</li>
									<li class="item_filter_btn" v-on:click.prevent="hotProducts(1)">Populares</li>
									<li class="item_filter_btn" v-on:click.prevent="getProductByState(1,1)">Nuevos</li>
									<li class="item_filter_btn" v-on:click.prevent="saleProductos(1)">Más Vendidos</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div style="padding-bottom: 2300px">
				<div class="row products_row products_container grid">
						
					<div v-for="producto in arrayProductos" :key="producto.cop" class="col-xl-4 col-md-6 grid-item">
						
						<div class="product">
							<span class="badge-new" v-show="producto.estado==1"><b v-text="'Nuevo'"></b></span>
							<span class="badge-offer"><b v-text="'-' + producto.porcentaje_descuento +'%'" v-show="producto.porcentaje_descuento>0"></b></span>
							<div class="product_image">
							<img :src="'storage/' + producto.url" alt="producto">
							</div>
							<div class="product_content">
								<div class="product_info d-flex flex-row align-items-start justify-content-start">
									<div>
										<div>
										<div class="product_name"><a :href="'product/' + producto.slug" v-text="producto.nombre + '-' + producto.color"></a></div>
										</div>
									</div>
									<div class="ml-auto text-right">
										<div class="product_category">En <a href="" v-text="producto.tipo"  v-on:click.prevent="getProductByTipo(1,producto.tipo_id)"></a></div>
										<div class="product_price text-right" v-text="'$'+producto.precio_actual"><span></span></div>
										<del class="price-old text-right" v-show="producto.precio_actual<producto.precio_anterior" v-text="'$'+producto.precio_anterior" style="font-size: 17px"></del>
									</div>
								</div>
								<div class="product_buttons">
									<div class="text-right d-flex flex-row align-items-start justify-content-start">										
										<div class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
											<div><div><img src="{{ asset('asset/images/cart.svg') }}" class="svg" alt=""><div>+</div></div></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				
				</div>	
			</div>
					
			<div class="row page_nav_row">
				<div class="col">
					<div class="page_nav">
						{{--<ul class="d-flex flex-row align-items-start justify-content-center">
							<li class="active"><a href="#">01</a></li>
							<li><a href="#">02</a></li>
							<li><a href="#">03</a></li>
							<li><a href="#">04</a></li>
						</ul>--}}
						
						{{--<ul class="d-flex flex-row align-items-start justify-content-center">
							<li class="" v-if="pagination.current_page > 1">
								<a href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
							</li>
							<li v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
								<a href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
							</li>
							<li v-if="pagination.current_page < pagination.last_page">
								<a class="" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
							</li>
						</ul>
                        
					</div>
				</div>
			</div>
		</div>
	</div>--}}
@endsection

{{-- @section('scripts')

<script>
	window.data = {
		categoria: "",
		subcategoria: "",
    }
</script> --}}
{{--<script src="{{ asset('asset/plugins/OwlCarousel2-2.2.1/owl.carousel.js') }}"></script>
<script src="{{ asset('asset/plugins/progressbar/progressbar.min.js') }}"></script>--}}
{{--<script src="{{ asset('asset/plugins/Isotope/isotope.pkgd.min.js') }}"></script>--}}
{{--<script src="{{ asset('asset/plugins/Isotope/fitcolumns.js') }}"></script>--}}
{{--<script src="{{ asset('asset/js/category.js') }}"></script>--}}
{{-- @endsection --}}
