

const product = new Vue({
    el: '#imprimir_pedidos',
    data: {
        
    }, 
    
    methods: {
        pdfInformePedidos(){
            window.open('/lorgeliz_tienda_copia/public/admin/pedidos/listado/pdf');
        },

        imprimir(id){
            window.open('/lorgeliz_tienda_copia/public/admin/pedidos/pedido/pdf/'+ id + ',' + '_blank');
        },
        
    },

});