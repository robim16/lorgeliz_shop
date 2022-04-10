<template>
    <main>
        <div class="product_text">
            <span style="color: black; font-weight: bold;">Talla</span>
            <select name="talla" id="talla" class="form-control" v-model="talla" @change="setStock" @click="change">
                <option value="0" selected>Seleccione una talla</option>
                <option v-for="talla in arrayTallas" :key="talla.id" :value="talla.id" v-text="select ? talla.nombre : talla.nombre  + ' unidades disponibles: ' + talla.stock"></option>
            </select>
        </div>

        <div class="product_text">
            <span style="color: black; font-weight: bold;" value="" class="producto">Cantidad</span>
            <input class="form-number form-control" type="number" id="cantidad" name="cantidad"
                value="1" step="1" min="0" v-model="cantidad">
                <span style="color: rgb(243, 61, 61); font-weight: bold;" v-text="stock >= cantidad ? '' : 'Puedes agregar máximo ' + stock + ' unidades a tu carrito!'" 
                ></span>

                <span style="color: rgb(243, 61, 61); font-weight: bold;" v-show="error && (cantidad == '' || cantidad == 0 || talla == '')">Debes indicar la cantidad y la talla del producto</span>
        </div>

        <div class="product_buttons">
            <div class="text-right d-flex flex-row align-items-start justify-content-start">
                <div
                    class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center" id="cart" v-on:click.prevent="getCarrito()">
                    <div>
                        <div><img :src="'../asset/images/cart.svg'" class="svg" alt="">
                            <div>+</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        // props : ['producto'],
        props: {
            producto:{
                required: true,
                type: String
            },
            ruta:{
                required: true,
                type: String
            }
        },
        data (){
            return {
               nombre: '',
                slug: '',
                cantidad: 0,
                //producto: '',
                carrito: '',
                cantidad: '',
                talla: 0,
                arrayTallas: [],
                arrayCarrito: [],
                stock: 0,
                select: true,
                error: false
            }
		},

        methods: {
            setVisitas(){

                // let url = `/lorgeliz_tienda_copia/public/productos/${this.producto}/update/visitas`;
                // let url = `/lorgeliz_tienda_copia/public/api/productos/${this.producto}/update/visitas`;
                let url = `${this.ruta}/api/productos/${this.producto}/update/visitas`
                axios.put(url).then(response => {
                   
                }).catch(error => {
                    console.log(error);
                });

            },

            change(){
                this.select =  !this.select;
            },

            setStock(){
                if (this.talla != 0 ) {
                    for (let i = 0; i < this.arrayTallas.length; i++) {
                        if (this.arrayTallas[i].id == this.talla) {
                            this.stock = this.arrayTallas[i].stock;
                        }
                    }
                }
                else{
                this.stock = 0;
                }
            },

            getTallas(){

                // let url = '/lorgeliz_tienda_copia/public/tallas/productos/'+this.producto;
                // let url = '/lorgeliz_tienda_copia/public/api/tallas/'+this.producto;

                let url = `${this.ruta}/api/tallas/${this.producto}`

                axios.get(url).then(response => {
                    // this.arrayTallas = response.data.tallas;
                    this.arrayTallas = response.data;
                }).catch(error => {
                    console.log(error);
                }); 

            }, 

            getCarrito(){
                // let url = '/lorgeliz_tienda_copia/public/cart/buscarCarrito';

                let url = `${this.ruta}/cart/buscarCarrito`

                if ((this.cantidad != '' && this.cantidad != 0) && this.talla != '') {

                    if(this.error) this.error = false;
                    axios.get(url).then(response => {
                        this.arrayCarrito = response.data.carrito;
        
                        if (this.arrayCarrito != null){
                            this.carrito = this.arrayCarrito.id;

                            // let url = '/lorgeliz_tienda_copia/public/cart/update';

                            let url = `${this.ruta}/cart/update`
                            
            
                            for (let i = 0; i < this.arrayTallas.length; i++) {
                                if (this.arrayTallas[i].id == this.talla) {
                                    if (this.cantidad <= this.arrayTallas[i].stock ) {
                                        axios.post(url,{
                                        'producto': this.producto,
                                        'talla': this.talla,
                                        'cantidad': this.cantidad,
                                        'carrito': this.carrito
                            
                                        }).then(response => {
                    
                                            if (response.data.data == 'error') {
                        
                                                var unidades = parseInt(response.data.carrito);
                                                var actual = parseInt(response.data.stock);
                                                var restantes = actual - unidades;
                        
                                                if (restantes == 0) {
                                                    swal(
                                                    'Producto agotado!',
                                                    'No puedes agregar más unidades de este producto a tu carrito!',
                                                    'error'
                                                    )
                                                }
                                                else{
                                                    swal(
                                                        'Producto con stock limitado!',
                                                        'Puedes agregar a tu carrito sólo ' + restantes + ' unidad(es) más de este producto',
                                                        'error'
                                                    )
                                                }
                                            }
                                            else{
                        
                                                swal(
                                                'Producto agregado al carrito!',
                                                'Haz agregado este producto a tu carrito',
                                                'success'
                                                )   
                        
                                            }
                                
                                        }).catch(error => {
                                            console.log(error);
                                        }); 
                                    } else{
                                        //let datos = 'No se puede agregar el producto al carrito!. La cantidad debe ser máximo ' + this.arrayTallas[i].stock;

                                        //bootbox.alert(datos);
                                        swal(
                                        'No se puede agregar el producto al carrito!',
                                        'La cantidad debe ser máximo ' + this.arrayTallas[i].stock,
                                        'error'
                                        )   
                                    }
                                }
                                
                            }
                    
                        } else{
            
                            // let url = '/lorgeliz_tienda_copia/public/cart/store';
                            // let url = '/lorgeliz_tienda_copia/public/cart';

                            let url = `${this.ruta}/cart`
                
                            for (let i = 0; i < this.arrayTallas.length; i++) {
                                if (this.arrayTallas[i].id == this.talla) {
                                    if (this.cantidad <= this.arrayTallas[i].stock) {
                                        axios.post(url,{
                                        'producto': this.producto,
                                        'talla': this.talla,
                                        'cantidad': this.cantidad
                            
                                        }).then(response => {
                                        
                                            swal(
                                                'Producto agregado al carrito!',
                                                'Haz agregado este producto a tu carrito',
                                                'success'
                                            )
                                        }).catch(error => {
                                            console.log(error);
                                        });
                                    }
                                    else{
                                        //let datos = 'No se puede agregar el producto al carrito!. La cantidad debe ser máximo ' + this.arrayTallas[i].stock;
                                        
                                        //bootbox.alert(datos);
                                        swal(
                                        'No se puede agregar el producto al carrito!',
                                        'La cantidad debe ser máximo ' + this.arrayTallas[i].stock,
                                        'error'
                                        )   
                                    }
                                }
                                
                            }
            
                        }
        
                    }); 
                
                }
                else{
                    // swal(
                    //     'No se puede agregar este producto al carrito!',
                    //     'Debes indicar la talla y la cantidad!',
                    //     'error'
                    // )
                    this.error = true;
                }

            },

        },

        mounted() {
            
            // this.producto = data.datos.producto;
            this.setVisitas();
            this.getTallas();

            window.Echo.channel('producto-agotado').listen('ProductoAgotado', (e) => {

                products = e.data;

                const index = this.products.findIndex(p => p.id == this.producto);

                if (index > -1) {
                    this.arrayTallas = [];
                }
               
            });

            window.Echo.channel('add-product').listen('AddProductEvent', (e) => {
               let product = e.data.product;
                
                if (product.id === this.producto) {
                    this.getTallas()
                }
            });

            window.Echo.channel('new-sale').listen('SalesEvent', (e) => {
                products = e.data;

                const index = this.products.findIndex(p => p.id == this.producto);

                if (index > -1) {
                    this.getTallas();
                }
            });

        }
    }
</script>
