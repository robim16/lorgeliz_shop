<template>
    <!-- <main> -->
        <!-- Cart -->
        <div class="cart_section">
            <div class="container">
                <div v-if="arrayProductos.length > 0">
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

                                
                                <!-- <div class="cart_items">
                                    <ul class="cart_items_list">   
                                        
                                       
                                        <li v-for="producto in arrayProductos" :key="producto.ref" class="cart_item item_list d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-end justify-content-start">
                                            <div class="product d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-start mr-auto">
                                            <div><div class="product_number"></div></div>
                                                <div>
                                                    <div class="product_image">
                                                        <a :href="'productos/' + producto.slug">
                                                            <img :src="'storage/' + producto.imagen" alt="">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product_name_container">
                                                <div class="product_name"><a :href="'productos/' + producto.slug">{{producto.nombre}}</a></div>
                                                    <div class="product_text" v-html="producto.descripcion_corta"></div>
                                                </div>
                                            </div>
                                            <div class="product_color product_text">
                                                <span>Color: </span>
                                                {{ producto.color}}
                                                
                                            </div>
                                            <div class="product_size product_text">
                                                <span>Talla: </span>
                                                {{ producto.talla}}
                                            </div>
                                            <div class="product_price product_text"><span>Precio: </span>
                                                <div id="">{{ '$' + producto.precio_actual }}</div>
                                            </div>
                                            <div class="product_quantity_container">
                                                <div class="product_quantity ml-lg-auto mr-lg-auto text-center">
                                                    <span class="product_text product_num" id="">{{ producto.cantidad }}</span>
                                                    <div class="qty_sub qty_button trans_200 text-center" id="" @click.prevent="updateCart(producto.ref,2)">
                                                        <span>-</span>
                                                    </div>
                                                    <div class="qty_add qty_button trans_200 text-center" id="" @click.prevent="updateCart(producto.ref,1)">
                                                        <span>+</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="product_total product_text">
                                                <span>Total: </span>
                                                <div id="">{{'$' + producto.precio_actual * producto.cantidad }}</div> 
                                                
                                            </div>
                                            <a href="" @click.prevent="remove(producto.ref)"><i class="fa fa-trash text-danger" style="font-size: 20px" title="quitar del carrito"></i></a>

                                            <div class="" v-if="producto.stock==0">
                                                <span class="float-right text-danger">Este producto se ha agotado!</span>
                                            </div>
                                        
                                            <div class="" v-else-if="producto.cantidad > producto.stock">
                                                <span class="float-right text-danger">Quedan {{ producto.stock }} unidad(es)!</span>
                                            </div>
                                        </li>
                                        
                                    </ul>
                                </div> -->

                                <div class="cart_items">
                                    <ul class="cart_items_list">   
                                        
                                        <!-- Cart Item -->
                                        <li v-for="(producto, index) in arrayProductos" :key="producto.id" class="cart_item item_list d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-end justify-content-start">
                                            <div class="product d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-start mr-auto">
                                                <div><div class="product_number">{{ index + 1 }}</div></div>
                                                <div>
                                                    <div class="product_image">
                                                        <a :href="'productos/' + producto.producto_referencia.color_producto.slug">
                                                            <img :src="'storage/' + producto.producto_referencia.color_producto.imagenes[0].url" alt="">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product_name_container">
                                                    <div class="product_name"><a :href="'productos/' + producto.producto_referencia.color_producto.slug">
                                                    {{producto.producto_referencia.color_producto.producto.nombre}}</a></div>
                                                    <div class="product_text" v-html="producto.producto_referencia.color_producto.producto.descripcion_corta"></div>
                                                </div>
                                            </div>
                                            <div class="product_color product_text">
                                                <span>Color: </span>
                                                {{ producto.producto_referencia.color_producto.color.nombre}}
                                                
                                            </div>
                                            <div class="product_size product_text">
                                                <span>Talla: </span>
                                                {{ producto.producto_referencia.talla.nombre}}
                                            </div>
                                            <div class="product_price product_text"><span>Precio: </span>
                                                <div id="">{{ producto.producto_referencia.color_producto.producto.precio_actual | currencyFormat}}</div>
                                            </div>
                                            <div class="product_quantity_container">
                                                <div class="product_quantity ml-lg-auto mr-lg-auto text-center">
                                                    <span class="product_text product_num" id="">{{ producto.cantidad }}</span>
                                                    <div class="qty_sub qty_button trans_200 text-center" id="" @click.prevent="updateCart(producto.producto_referencia.id,2)">
                                                        <span>-</span>
                                                    </div>
                                                    <div class="qty_add qty_button trans_200 text-center" id="" @click.prevent="updateCart(producto.producto_referencia.id,1)">
                                                        <span>+</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="product_total product_text">
                                                <span>Total: </span>
                                                <div id="">{{ producto.producto_referencia.color_producto.producto.precio_actual * producto.cantidad | currencyFormat }}</div> 
                                                
                                            </div>
                                            <a href="" @click.prevent="remove(producto.producto_referencia.id)" class="remove"><i class="fa fa-trash text-danger" style="font-size: 20px" title="quitar del carrito"></i></a>

                                            <div class="" v-if="producto.producto_referencia.stock==0">
                                                <span class="float-right text-danger">Este producto se ha agotado!</span>
                                            </div>
                                        
                                            <div class="" v-else-if="producto.cantidad > producto.producto_referencia.stock">
                                                <span class="float-right text-danger">Quedan {{ producto.producto_referencia.stock }} unidad(es)!</span>
                                            </div>
                                        </li>
                                        
                                    </ul>
                                </div>
                                <!-- Cart Buttons -->
                                <div class="cart_buttons d-flex flex-row align-items-start justify-content-start">
                                    <div class="cart_buttons_inner ml-sm-auto d-flex flex-row align-items-start justify-content-start flex-wrap">
                                    <div class="button button_clear trans_200"><a href="" @click.prevent="limpiarCarrito">vaciar carrito</a></div>
                                        <div class="button button_continue trans_200"><a :href="'./'" id="continuar">continuar comprando</a></div>
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
                                            <!-- <li class="shipping_option d-flex flex-row align-items-center justify-content-start">
                                                <label class="radio_container">
                                                    <input type="radio" id="radio_1" name="shipping_radio" class="shipping_radio">
                                                    <span class="radio_mark"></span>
                                                    <span class="radio_text">Entrega al día siguiente</span>
                                                </label>
                                                <div class="shipping_price ml-auto">$4.99</div>
                                            </li> -->
                                            <li class="shipping_option d-flex flex-row align-items-center justify-content-start">
                                                <label class="radio_container">
                                                    <input type="radio" id="radio_2" name="shipping_radio" class="shipping_radio">
                                                    <span class="radio_mark"></span>
                                                    <span class="radio_text">Envío estandar</span>
                                                </label>
                                                <div class="shipping_price ml-auto">{{ envio | currencyFormat}}</div>
                                            </li>
                                            <!-- <li class="shipping_option d-flex flex-row align-items-center justify-content-start">
                                                <label class="radio_container">
                                                    <input type="radio" id="radio_3" name="shipping_radio" class="shipping_radio" checked>
                                                    <span class="radio_mark"></span>
                                                    <span class="radio_text">Personal Pickup</span>
                                                </label>
                                                <div class="shipping_price ml-auto">Free</div>
                                            </li> -->
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
                                            <div class="cart_extra_total_value ml-auto" id="subtotal">{{ total | currencyFormat}}</div>
                                        </li>
                                        <li class="d-flex flex-row align-items-center justify-content-start">
                                            <div class="cart_extra_total_title">Envío</div>
                                            <div class="cart_extra_total_value ml-auto">{{ envio | currencyFormat}}</div>
                                        </li>
                                        <li class="d-flex flex-row align-items-center justify-content-start">
                                            <div class="cart_extra_total_title">Total</div>
                                            <div class="cart_extra_total_value ml-auto" id="neto">{{ totalneto + envio | currencyFormat }}</div>
                                        </li>
                                    </ul>
                                    <div class="checkout_button trans_200"><a href="http://lorenzogeliztienda.com/checkout" id="pago">proceder al pago</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div
                    v-if="arrayProductos.length == 0 && empty==false"
                    class="col-md-2 offset-5"
			    >
                    <a href="#">
                        <img src="img/preloader.gif" />
                    </a>
    		    </div>

                <div v-show="empty" class="alert alert-info pt-5 col-md-7 text-center m-auto">
                    <h4 class="alert-heading">Aún no tienes productos en tu carrito</h4>
                </div>
               
            </div>
        </div> 
    <!-- </main> -->
</template>

<script>
    export default {
        props: {
            user_id: {
                type: Number,
                required: true
            },
            ruta:{
                required: true,
                type: String
            }
        },
        data (){
            return {
                arrayProductos: [],
                producto: '',
                operacion: '',
                cantidad: '',
                total: '',
                totalneto: '',
                carrito: '',
                empty: false,
                envio: 8000
            }
		},

        methods : {
            loadCart(){
                // let url = '/lorgeliz_tienda_copia/public/cart/user';
                let url = `${this.ruta}/cart/user`
                axios.get(url).then(response => {
                    this.arrayProductos = response.data.productos;

                    if (this.arrayProductos.length == 0) {
                        this.empty = true;
                    }
                    else{
                        // this.total = this.arrayProductos[0].total;
                        // this.totalneto = this.arrayProductos[0].total;
                        this.carrito = this.arrayProductos[0].carrito.id;
                        this.total = this.arrayProductos[0].carrito.total;
                        this.totalneto = this.arrayProductos[0].carrito.total;
                    }
                }).catch(error => {
                    console.log(error);
                }); 
            },

            updateCart(producto,operacion){
                this.producto = producto;
                this.operacion = operacion;

                // let url = '/lorgeliz_tienda_copia/public/cart/setCantidad';
                // let me = this;

                let url = `${this.ruta}/cart/setCantidad`

                axios.post(url,{
                'producto': this.producto,
                'operacion': this.operacion
                }).then(response => {
                    if (response.data.data == 'success') {
                       this.loadCart();
				    }
                    if (response.data.data == 'error') {
                        // swal(
                        //     'Stock limitado!',
                        //     'No puedes añadir más unidades de este producto a tu carrito!',
                        //     'error'
                        // )
                        bootbox.alert('Stock limitado. No puedes añadir más unidades de este producto a tu carrito!');
                    }

                }).catch(error => {
                    console.log(error);
                });
            },
            
            remove(producto){
                this.producto = producto;
                // let url = '/lorgeliz_tienda_copia/public/cart/remove/'+this.producto;
                // let url = `/lorgeliz_tienda_copia/public/cart/${this.producto}/remove`;
                // let me = this;

                let url = `${this.ruta}/cart/${this.producto}/remove`
        
                axios.delete(url).then(response => {
                    if (response.data.data == 'success') {
                        this.loadCart();
				    }
                }).catch(error => {
                    console.log(error);
                });
            },
        
            limpiarCarrito(){
                // let url = '/lorgeliz_tienda_copia/public/cart/delete/'+this.carrito;
                // let me = this;
                // let url = `/lorgeliz_tienda_copia/public/cart/${this.carrito}/delete`;

                let url = `${this.ruta}/cart/${this.carrito}/delete`
        
                axios.delete(url).then(response => {
                    if (response.data.data == 'success') {
                       swal(
                        'Haz limpiado tu carrito!',
                        'Tu carrito de compras está vacío!',
                        'success'
                        )

                        this.loadCart();
                        // window.location.href = `/lorgeliz_tienda_copia/public/`;
				    }
                }).catch(error => {
                    console.log(error);
                });
            }
        },

        filters: {
            currencyFormat: function (number) {
                return new Intl.NumberFormat('es-CO', {style: 'currency',currency: 'COP', minimumFractionDigits: 0}).format(number);
            }
        },

        created() {
            
            this.loadCart();
           
        },

        mounted() {

            window.Echo.private(`cart-updated.${this.user_id}`).listen('UserCart', (e) => {
                this.loadCart();
            });

            // window.Echo.channel('producto-agotado').listen('ProductoAgotado', (e) => {

            //     products = e.data;

            //     Object.values(products).map(product => {
            //         const index = this.arrayProductos.findIndex(p => p.id == product.id);

            //         if (index > -1) {
            //             this.arrayProductos.splice(index, 1);//no se debe borrar del carrito los productos del cliente
            //         }
            //     })
                
            // });

        }
    }
</script>

<style scoped>
    .product_color{
        padding-left: 67px;
    }

    .product_size{
        padding-left: 67px;
    }

    .product_price{
        padding-left: 66px;
    }

    .product_quantity_container{
        padding-left: 69px;
    }

    .product_total{
        padding-left: 65px;
    }

    .remove{
        padding-left: 28px; 
    }

    @media only screen and (max-width: 991px)
    {
        .product_color{
            padding-left: 0;
        }

        .product_size{
            padding-left: 0;
        }

        .product_price{
            padding-left: 0;
        }

        .product_quantity_container{
            padding-left: 0;
        }

        .product_total{
            padding-left: 0;
        }

        .remove{
            padding-left: 0; 
            margin: 5px 0;
            font-size: 30px;
        }
    }
</style>