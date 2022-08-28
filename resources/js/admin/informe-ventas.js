

const product = new Vue({
    el: '#informeventa',
    data: {
        
    }, 
    
    methods: {
        pdfInformeVentas(){
            let url = '/lorgeliz_tienda_copia/public/admin/informes/pdf/ventas';
            // let url = 'http://dev.lorenzogeliztienda.com/admin/informes/pdf/ventas'
            window.open(url);
            
        },
        
    },

});