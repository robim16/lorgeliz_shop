

const product = new Vue({
    el: '#informeproducto',
    data: {
        
    }, 
    
    methods: {
        pdfInformeProductos(){
            // let url = '/lorgeliz_tienda_copia/public/admin/informes/pdf/productos';

            let url = 'http://dev.lorenzogeliztienda.com/admin/informes/pdf/productos'
            window.open(url);
            
        },
        
    },

});