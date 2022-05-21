
const product = new Vue({
    el: '#informesaldos',
    data: {
        
    }, 
    
    methods: {
        pdfInformeSaldos(){
            // let url = '/lorgeliz_tienda_copia/public/admin/informes/pdf/saldos';

            let url = 'http://lorenzogeliztienda.com/admin/informes/pdf/saldos'
            window.open(url);
            
        },
        
    },

});