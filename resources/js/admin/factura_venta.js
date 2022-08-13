

const product = new Vue({
    el: '#factura_venta',
    data: {
        venta: 0
    }, 
    
    methods: {
        facturaVenta(id){
            // window.open('/lorgeliz_tienda_copia/public/admin/ventas/factura/'+ id + ',' + '_blank');
           
            window.open('http://dev.lorenzogeliztienda.com/admin/ventas/factura/'+ id + ',' + '_blank')
            
        },
        
    },

});