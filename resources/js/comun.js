
window.Vue = require('vue');

Vue.component('category', require('./components/Category.vue').default);
Vue.component('user-cart', require('./components/UserCart.vue').default);
Vue.component('product', require('./components/Product.vue').default);
Vue.component('store', require('./components/Store.vue').default);
Vue.component('slider', require('./components/Slider.vue').default);
Vue.component('sale', require('./components/Sales.vue').default);
Vue.component('offers', require('./components/Offers.vue').default);
Vue.component('popular', require('./components/Popular.vue').default);
Vue.component('product-new', require('./components/ProductsNew.vue').default);
Vue.component('cart', require('./components/Cart.vue').default);
Vue.component('chat-store', require('./components/Chat.vue').default);
Vue.component('chat-alert', require('./components/ChatAlert.vue').default);
Vue.component('notifications', require('./components/Notifications.vue').default);
Vue.component('messages', require('./components/Messages.vue').default);
Vue.component('factura', require('./components/Factura.vue').default);
Vue.component('order-detail', require('./components/OrderDetail.vue').default);
Vue.component('checkout', require('./components/Checkout.vue').default);

// admin components
Vue.component('chat', require('./components/admin/Chat.vue').default);
Vue.component('chat-list', require('./components/admin/ChatList.vue').default);
Vue.component('messenger', require('./components/admin/Messenger.vue').default);
Vue.component('pagos', require('./components/admin/Pagos.vue').default);
Vue.component('pagos-mes', require('./components/admin/PagosMes.vue').default);


if (document.getElementById('app')) {
    const app = new Vue({
        el: '#app',
        data: {
            keyword: '',
            location: '',
            // ruta: 'http://dev.lorenzogeliztienda.com'
            ruta: '/lorgeliz_tienda_copia/public'
        }, 
        methods:{
            setCategoria(categoria){
                localStorage.setItem('category', JSON.stringify(categoria));
                // window.location.href = `/lorgeliz_tienda_copia/public/categorias`;
                window.location.href = `${this.ruta}/categorias`;
            },

            setSubcategoria(subcategoria){
                localStorage.setItem('subcategory', JSON.stringify(subcategoria));
                window.location.href = `${this.ruta}/categorias`;
            },

            search(){
                this.location = window.location.pathname;

                if (this.location != `${this.ruta}/categorias`) {
                    localStorage.setItem('keyword', JSON.stringify(this.keyword));
                    window.location.href = `${this.ruta}/categorias`;
                } 
            }
        }

    });
}

if (document.getElementById('product_cart')) {
    require('./tienda/product');
}

// if (document.getElementById('chat')) {
//     require('./tienda/chat');
// }

// if (document.getElementById('inicio')) {
//     require('./tienda/index');
// }

if (document.getElementById('clientNotification')) {
    require('./tienda/notifications');
}

if (document.getElementById('message')) {
    require('./tienda/messages');
}

if (document.getElementById('notification')) {
    require('./admin/notifications');
}

if (document.getElementById('chatNotification')) {
    require('./admin/inbox');
}

if (document.getElementById('imprimir_pedidos')) {
    require('./admin/imprimir_pedidos');
}

if (document.getElementById('productos')) {
    require('./admin/product');
}

if (document.getElementById('venta_cliente')) {
    require('./tienda/facturacliente');
}

if (document.getElementById('carrito')) {
    require('./tienda/cart');
}

if (document.getElementById('pedidos')) {
    require('./tienda/orders');
}

if (document.getElementById('categoria')) {
    require('./tienda/category');
}

if (document.getElementById('informeventa')) {
    require('./admin/informe-ventas');
}

if (document.getElementById('informeproducto')) {
    require('./admin/informe-productos');
}

if (document.getElementById('informesaldos')) {
    require('./admin/informe_saldos');
}

if (document.getElementById('informecliente')) {
    require('./admin/informe-clientes');
}

if (document.getElementById('infventashow')) {
    require('./admin/print-ventas-show');
}

if (document.getElementById('listventas')) {
    require('./admin/listadoventas');
}

if (document.getElementById('listdevolucion')) {
    require('./admin/listadodevoluciones');
}

if (document.getElementById('listclientes')) {
    require('./admin/listadoclientes');
}

if (document.getElementById('inventarios')) {
    require('./admin/inventarios');
}

if (document.getElementById('payments')) {
    require('./admin/listadopagos');
}

if (document.getElementById('user_cart')) {
    require('./tienda/userCart');
}

if (document.getElementById('factura_venta')) {
    require('./admin/factura_venta');
}


