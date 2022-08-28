

const factura = new Vue({
    el: '#venta_cliente',
    data: {
        
    }, 
    
    methods: {
        pdfVenta(id){
            // window.open('/lorgeliz_tienda_copia/public/pedidos/factura/'+ id + ',' + '_blank');

            let url = 'http://dev.lorenzogeliztienda.com/pedidos/factura/';
           
            window.open(url + id + ',' + '_blank');
        },
        
    },

});