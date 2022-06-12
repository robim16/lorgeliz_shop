<template>
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
                            <li class="active"><a href="" @click.prevent="setCategoria('mujeres')">Mujeres</a></li>
                            <li><a href="" @click.prevent="setCategoria('hombres')">Hombres</a></li>
                            <li><a href="" @click.prevent="setCategoria('niños')">Niños</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row products_row">
               
                <div v-for="nuevo in productoNuevo" :key="nuevo.id" class="col-xl-4 col-md-6">
                    <div class="product">
                        <div class="product_image">
                        <a :href="'productos/' + nuevo.slug">
							<img :src="'storage/' + nuevo.imagenes[0].url" alt="">
                        </a>
                        </div>
                        <div class="product_content">
                            <div class="product_info d-flex flex-row align-items-start justify-content-start">
                                <div>
                                    <div>
                                        <div class="product_name"><a :href="'productos/' + nuevo.slug">{{ nuevo.producto.nombre}}
                                            {{ nuevo.color.nombre}}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-auto text-right">
                                    <div class="product_category">En <a href=""  @click.prevent="setSubcategoria(nuevo.producto.tipo_id)">{{ nuevo.producto.tipo.nombre}}</a></div>
                                    <div class="product_price text-right">{{'$' + nuevo.producto.precio_actual}}<span></span></div>
                                </div>
                            </div>
                            <div class="product_buttons">
                                <div class="text-right d-flex flex-row align-items-start justify-content-start">
                                    <div
                                        class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
                                        <div>
                                            <div><a :href="'productos/' + nuevo.slug"><img src="asset/images/cart.svg" class="svg" alt="">
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
                    <div class="button load_more ml-auto mr-auto"><a href="" v-on:click.prevent="getProductos()">cargar más</a></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            ruta:{
                required: true,
                type: String
            }
        },

        data (){
            return {
               productoNuevo: [],
               cantidad: 0
            }
		},

         methods : {
            getProductos(){
				this.cantidad++;
                // let url = '/lorgeliz_tienda_copia/public/index?cantidad=' + this.cantidad;

                let url = `${this.ruta}/index?cantidad=${this.cantidad}`;

				axios.get(url).then(response => {
					this.productoNuevo = response.data.nuevos;
				}).catch(error => {
                    console.log(error);
                });
        	},

            setCategoria(categoria){
                localStorage.setItem('category', JSON.stringify(categoria));
                // window.location.href = `/lorgeliz_tienda_copia/public/categorias`;
                window.location.href = `${this.ruta}/categorias`;
            },

            setSubcategoria(subcategoria){
                localStorage.setItem('subcategory', JSON.stringify(subcategoria));
                // window.location.href = `/lorgeliz_tienda_copia/public/categorias`;
                window.location.href = `${this.ruta}/categorias`;
            }
        },
        mounted() {
			this.getProductos();

            window.Echo.channel('add-product').listen('AddProductEvent', (e) => {
               let product = e.data;
                
                if (product.data.producto.estado == 1) {

                    const index = this.productoNuevo.findIndex(p => p.id == product.id);

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
                    const index = this.productoNuevo.findIndex(p => p.id == product.id);

                    if (index > -1) {
                        this.productoNuevo.splice(index, 1);
                    }
                })
                
            });

            window.Echo.channel('change-status').listen('ProductStatusEvent', (e) => {
                let productos = e.data;
                let i = 0;
                
                productos.map(item => {
                    let index = this.productoNuevo.findIndex(p => p.id == item.id);

                    if (index > -1) {
                        if (!item.producto.estado == 1) {
                            this.productoSlider.splice(index, 1);
                        }
                    }
                    else{
                        if (item.producto.estado == 1) {
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
