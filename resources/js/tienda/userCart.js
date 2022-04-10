

const user_cart = new Vue({
    el: '#user_cart',
    data: {
        productos: 0,
    }, 

    computed:{
        
    },
    
    methods: {
        carritoUser(){
            let url = '/lorgeliz_tienda_copia/public/cart/products';

            axios.get(url).then(response => {
                this.productos = response.data;
            }); 
            
        },

    },

    mounted() {
        this.carritoUser();

        // window.Echo.private(`cart-updated.${this.user_id}`).listen('UserCart', (e) => {
        //     let cart = e.cart;
        //     this.productos = cart.cantidad;
        // });
    }
});
