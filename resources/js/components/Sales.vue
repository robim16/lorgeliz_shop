
<template>
    <!-- Lo mas vendido -->
    <div class="lomasvendido owl-carousel owl-theme">
        <!-- item-->
        <div v-for="producto in productoSales" :key="producto.id" class="owl-item">
            <div class="product">
                <div class="product_image">
                <a :href="'productos/' + producto.slug">
                    <img :src="'storage/' + producto.imagenes[0].url" alt="">
                </a>
                </div>
                <div class="product_content">
                    <div class="product_info d-flex flex-row align-items-start justify-content-start">
                        <div>
                            <div>
                                <div class="product_name"><a :href="'productos/' + producto.slug">{{ producto.producto.nombre}}-{{producto.color.nombre}}</a></div>
                            </div>
                        </div>
                        <div class="ml-auto text-right">
                            <div class="product_category">En <a href="" @click.prevent="setSubcategoria(producto.producto.tipo.id)">{{producto.producto.tipo.nombre}}</a></div>
                            <div class="product_price text-right">{{ producto.producto.precio_actual | currencyFormat}}<span></span></div>
                        </div>
                    </div>
                    <div class="product_buttons">
                        <div class="text-right d-flex flex-row align-items-start justify-content-start">
                            <div
                                class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
                                <div>
                                    <div><a :href="'productos/' + producto.slug">
                                            <img src="asset/images/cart.svg" class="svg" alt=""></a>
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
</template>

<script>
    export default {
		// props:['productos'],
        props: {
            productos:{
                required: true,
                type: Array
            },

            ruta:{
                required: true,
                type: String
            }
        },
        data (){
            return {
                productoSales: [],
            }
		},
		
        methods : {
            getProductos(){
				// let url = '/lorgeliz_tienda_copia/public/index';

                let url = `${this.ruta}/index`

				axios.get(url).then(response => {
					this.productoSales = response.data.vendidos
				}).catch(error => {
                    console.log(error);
                });
            
        	},

            setSubcategoria(subcategoria){
                localStorage.setItem('subcategory', JSON.stringify(subcategoria));
                // window.location.href = `/lorgeliz_tienda_copia/public/categorias`;

                window.location.href = `${this.ruta}/categorias`;
            }
        },

        filters: {
            currencyFormat: function (number) {
                return new Intl.NumberFormat('es-CO', {style: 'currency',currency: 'COP', minimumFractionDigits: 0}).format(number);
            }
        },
        
        mounted() {

            this.productoSales = this.productos;
			
            window.Echo.channel('add-product').listen('AddProductEvent', (e) => {

                // let product = e.data.product;
                let product = e.data;
                console.log(product);

                const index = this.productoSales.findIndex(p => p.id == product.id);

                if (index == -1) {
                    // this.productoSales.push(product);
                    this.getProductos();
                }
                
            });

            window.Echo.channel('producto-agotado').listen('ProductoAgotado', (e) => {

                // let products = [];

                products = e.data;

                Object.values(products).map(product => {
                    const index = this.productoSales.findIndex(p => p.id == product.id);

                    if (index > -1) {
                        this.productoSales.splice(index, 1);
                    }
                })
                
            });

            window.Echo.channel('new-sale').listen('SalesEvent', (e) => {
                this.getProductos();
            });
		   
        }
    }
</script>