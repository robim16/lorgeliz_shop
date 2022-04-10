<template>
    <div class="home_slider_container">
        <div class="owl-carousel owl-theme home_slider">
        
            <div class="owl-item" v-for="producto in productoSlider" :key="producto.id">
                <div class="background_image" :style="{ 'background-image': 'url(' + 'storage/' + producto.imagenes[0].url + ')'}">
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
                                                            <div class="from">desde</div>
                                                            <div style="font-size: 25px">{{ producto.producto.precio_actual}}<span></span>
                                                            </div>
                                                            <del class="price-oldslider" v-text="producto.producto.precio_anterior > producto.producto.precio_actual ? producto.producto.precio_anterior : ''">
                                                            <span></span></del>
                                                        </div>
                                                    </div>
                                                    <div class="product_image"><a
                                                            :href="'productos/' + producto.slug">
                                                            <img :src="'storage/' + producto.imagenes[0].url" alt="">
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
</template>

<script>
    export default {
		// props:['productos'],
        props: {
            productos:{
                required: true,
                type: Array
            }
        },
        data (){
            return {
                productoSlider: [],
            }
		},
		
        methods : {
            getProductos(){
				let url = '/lorgeliz_tienda_copia/public/index';

				axios.get(url).then(response => {
					this.productoSlider = response.data.slider
				}).catch(error => {
                    console.log(error);
                });
            
        	},
        },
        mounted() {
            this.productoSlider = this.productos;
			// this.getProductos();

            window.Echo.channel('add-product').listen('AddProductEvent', (e) => {
               let product = e.data;
                
                if (product.producto.slider_principal == 'Si') {

                    let element = this.productoSlider.findIndex(p => p.id == product);

                    if (element == -1) {
                        // this.productoOffers.push(product);
                        this.getProductos();
                    }
                }
                
            });

            window.Echo.channel('producto-agotado').listen('ProductoAgotado', (e) => {

                products = e.data;

                Object.values(products).map(product => {

                    let index = this.productoSlider.findIndex(p => p.id == product.id);

                    if (index > -1) {
                        this.productoSlider.splice(index, 1);
                    }
                });
                
            });

            window.Echo.channel('change-status').listen('ProductStatusEvent', (e) => {
                let productos = e.data;
                let i = 0;
                
                productos.map(item => {
                    let index = this.productoSlider.findIndex(p => p.id == item.id);

                    if (index > -1) {
                        if (!item.producto.slider_principal == 'Si') {
                            this.productoSlider.splice(index, 1);
                        }
                    }
                    else{
                        if (!item.producto.slider_principal == 'Si') {
                            i++;
                        }
                    }
                })

                if (i > 0) {
                    this.getProductos();
                }
            });
		   
        }
    }
</script>
