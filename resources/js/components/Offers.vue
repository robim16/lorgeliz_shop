
<template>
    <!-- <div class="lomasvendidocontenedor">
        <div class="section_title text-center">En oferta</div>
        <br> -->
        <div class="lomasvendido owl-carousel owl-theme">
            <!-- item-->
            <div  v-for="producto in productoOffers" :key="producto.id" class="owl-item">
                <div class="product">
                <span class="badge-new"><b> Nuevo</b></span>
                <span class="badge-offer"><b> - {{ producto.producto.porcentaje_descuento}}</b></span>
                    <div class="product_image">
                        <a :href="'product/' + producto.slug">
                            <img :src="'storage/' + producto.imagenes[0].url" alt="">
                        </a>
                    </div>
                    <div class="product_content">
                        <div class="product_info">
                            <div>
                                <div>
                                <div class="product_name product_namesinwidth text-center"><a href="">{{ producto.producto.nombre}}-{{producto.color.nombre}}</a></div>

                                </div>
                            </div>
                            <div class="ml-auto">
                                <div class="product_price text-center">{{ producto.producto.precio_actual}}<span></span>
                                    <del class="price-old">{{ producto.producto.precio_anterior}}</del>
                                </div>
                            </div>
                        </div>
                        <div class="product_buttons">
                            <div class="text-right d-flex flex-row align-items-start justify-content-start">
                                <div
                                    class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
                                    <div>
                                        <div><a href="">
                                                <img src="asset/images/cart.svg" class="svg" alt="">
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
    <!-- </div> -->

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
                productoOffers: [],
            }
        },
        
        methods : {
            getProductos(){
                let url = '/lorgeliz_tienda_copia/public/index';

                axios.get(url).then(response => {
                    this.productoOffers = response.data.ofertas
                }).catch(error => {
                    console.log(error);
                });
            
            },
        },
        mounted() {
            this.productoOffers = this.productos;
            
            window.Echo.channel('add-product').listen('AddProductEvent', (e) => {
               let product = e.data.product;
                
                if (product.producto.estado == 2) {

                    const index = this.productoOffers.findIndex(p => p.id == product.id);

                    if (index == -1) {
                        // this.productoOffers.push(product);
                        this.getProductos();
                    }
                }
                
            });

            window.Echo.channel('producto-agotado').listen('ProductoAgotado', (e) => {

                // let products = [];

                products = e.data;

                Object.values(products).map(product => {
                    const index = this.productoOffers.findIndex(p => p.id == product.id);

                    if (index > -1) {
                        this.productoOffers.splice(index, 1);
                    }
                })
                
            });

            window.Echo.channel('change-status').listen('ProductStatusEvent', (e) => {
                let productos = e.data;
                let i = 0;
                
                productos.map(item => {
                    let index = this.productoOffers.findIndex(p => p.id == item.id);

                    if (index > -1) {
                        if (!item.producto.estado == 2) {
                            this.productoSlider.splice(index, 1);
                        }
                    }
                    else{
                        if (item.producto.estado == 2) {
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