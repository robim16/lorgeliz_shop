

const factura = new Vue({
    el: '#venta_cliente',
    data: {
        
    }, 
    
    methods: {
        pdfVenta(id){
            window.open('/lorgeliz_tienda_copia/public/pedidos/factura/'+ id + ',' + '_blank');
            
        },
        
    },

});