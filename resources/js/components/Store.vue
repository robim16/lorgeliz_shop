<template>
	<main>
	
    <div class="home">
        <!-- Home Slider -->
        <div class="home_slider_container">
            <div v-for="producto in productoSlider" :key="producto.cop" class="owl-carousel owl-theme home_slider">
			
				<div class="owl-item">
					<div class="background_image" :style="{ 'background-image': 'url(' + 'storage/' + producto.imagen + ')'}">
					</div>
					<div class="container fill_height">
						<div class="row fill_height">
							<div class="col fill_height">
								<div class="home_container d-flex flex-column align-items-center justify-content-start">
									<div class="home_content">
										<div class="home_title">Nuevos Artículos</div>
										<div class="home_subtitle">Summer Wear</div>
										<div class="home_items">
											<div class="row">
												<div class="col-sm-3 offset-lg-1">
												</div>
												<div class="col-lg-4 col-md-6 col-sm-8 offset-sm-2 offset-md-0">
													<div class="product home_item_large">
														<div
															class="product_tag d-flex flex-column align-items-center justify-content-center">
															<div>
																<div>desde</div>
																<div style="font-size: 25px">{{ producto.precio_actual}}<span></span>
																</div>
																<del class="price-oldslider" v-text="producto.precio_anterior > producto.precio_actual ? producto.precio_anterior : ''">
																<span></span></del>
															</div>
														</div>
														<div class="product_image"><a
																:href="'product/' + producto.slug">
																<img :src="'storage/' + producto.imagen" alt="">
															</a>
														</div>
														<div class="product_content">
															<div class="product_buttons">
																<div
																	class="text-right d-flex flex-row align-items-start justify-content-start">
																	<div
																		class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
																		<div>
																			<div><img
																					src="asset/images/cart_2.svg"
																					alt="">
																				<div>+</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-3">

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				

            </div>
            <div class="home_slider_nav home_slider_nav_prev"><i class="fa fa-chevron-left" aria-hidden="true"></i>
            </div>
            <div class="home_slider_nav home_slider_nav_next"><i class="fa fa-chevron-right" aria-hidden="true"></i>
            </div>

            <!-- Home Slider Dots -->

            <div class="home_slider_dots_container">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="home_slider_dots">
                                <ul id="home_slider_custom_dots"
                                    class="home_slider_custom_dots d-flex flex-row align-items-center justify-content-center">
                                    <li class="home_slider_custom_dot active">01</li>
                                    <li class="home_slider_custom_dot">02</li>
                                    <li class="home_slider_custom_dot">03</li>
                                    <li class="home_slider_custom_dot">04</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
	 <!-- Products -->

    <div class="products">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section_title text-center">Nuevos Productos en Lorgeliz Shop</div>
                </div>
            </div>
            <div class="row page_nav_row">
                <div class="col">
                    <div class="page_nav">
                        <ul class="d-flex flex-row align-items-start justify-content-center">
                            <li class="active"><a href="">Mujeres</a></li>
                            <li><a href="">Hombres</a></li>
                            <li><a href="">Niños</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row products_row">
               
                <div v-for="nuevo in productoNuevo" :key="nuevo.cop" class="col-xl-4 col-md-6">
                    <div class="product">
                        <div class="product_image">
                            <a :href="'product/' + nuevo.slug">
							<img :src="'storage/' + nuevo.imagen" alt="">
						</a>
                        </div>
                        <div class="product_content">
                            <div class="product_info d-flex flex-row align-items-start justify-content-start">
                                <div>
                                    <div>
                                    <div class="product_name"><a href="">{{ nuevo.nombre}} {{ nuevo.color}}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-auto text-right">
                                    <div class="product_category">En <a href="">{{ nuevo.tipo}}</a></div>
                                    <div class="product_price text-right">{{ nuevo.precio_actual}}<span></span></div>
                                </div>
                            </div>
                            <div class="product_buttons">
                                <div class="text-right d-flex flex-row align-items-start justify-content-start">
                                    <div
                                        class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
                                        <div>
                                            <div><a href=""><img
                                                    src="asset/images/cart_2.svg" class="svg" alt="">
                                                </a>
                                                <div>+</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
                
            </div>
            <div class="row load_more_row">
                <div class="col">
                    <div class="button load_more ml-auto mr-auto"><a href="#">cargar más</a></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lo mas visto -->

    <div class="lomasvendidocontenedor">
        <div class="section_title text-center">Lo más Visto</div>
        <br>
        <div class="lomasvendido owl-carousel owl-theme">

            <!-- item-->
            
                <div v-for="producto in productoPopular" :key="producto.cop" class="">
                <div class="product">
                    <div class="product_image">
                        <a :href="'product/' + producto.slug">
							<img :src="'storage/' + producto.imagen" alt="">
						</a>
                    </div>
                    <div class="product_content">
                        <div class="product_info d-flex flex-row align-items-start justify-content-start">
                            <div>
                                <div>
                                    <div class="product_name"><a href="">{{ producto.nombre}}-{{ producto.color}}</a></div>

                                </div>
                            </div>
                            <div class="ml-auto text-right">
                                <div class="product_category">En <a href="">{{ producto.tipo}}</a></div>
                                <div class="product_price text-right">{{ producto.precio_actual}}<span></span></div>
                            </div>
                        </div>
                        <div class="product_buttons">
                            <div class="text-right d-flex flex-row align-items-start justify-content-start">
                                <div
                                    class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
                                    <div>
                                        <div><a href="">
                                                <img src="asset/images/cart_2.svg" class="svg" alt=""></a>
                                            <div>+</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>
    </main>
    
</template>

<script>
    export default {
		
        data (){
            return {
                productoSlider: [],
				productoNuevo: [],
				productoPopular: [],
				productoVendido: [],
				productoOferta: [],
                cantidad: 0
            }
		},
		
        methods : {
            getProductos(){
				let url = '/lorgeliz_tienda_copia/public/index';

				axios.get(url).then(response => {
					this.productoSlider = response.data.slider;
					this.productoNuevo = response.data.nuevos;
					this.productoPopular = response.data.populares;
					this.productoVendido = response.data.vendidos;
					this.productoOferta = response.data.ofertas;
				}); 
            
        	},
        },
        mounted() {
			this.getProductos();
		   
        }
    }
</script>
