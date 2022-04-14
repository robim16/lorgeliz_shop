<template>
    <div v-text="productos"></div>
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
                productos: 0,
            }
		},

        methods : {
            carritoUser(){
                // let url = '/lorgeliz_tienda_copia/public/cart/products';

                let url = `${this.ruta}/cart/products`;

                axios.get(url).then(response => {
                    this.productos = response.data.cantidad;
                }); 
               
            },
        },

        mounted() {
            
            // let url = '/lorgeliz_tienda_copia/public/cart/products';
            let url = `${this.ruta}/cart/products`;

            axios.get(url).then(response => {
                this.productos = response.data.cantidad;
            }).catch(error => {
                console.log(error);
            });

            window.Echo.private(`cart-updated.${this.user_id}`).listen('UserCart', (e) => {
                let cart = e.cart;
                this.productos = cart.cantidad;
            });
        }
    }
</script>
