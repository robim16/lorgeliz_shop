

const listsales = new Vue({
    el: '#listventas',
    data: {
       
    }, 
    
    methods: {
        pdfListadoVentas(){
            let url = '/lorgeliz_tienda_copia/public/admin/ventas/listado';

            // let url = 'http://dev.lorenzogeliztienda.com/admin/ventas/listado'
            window.open(url);
        },
        
    },

    mounted(){


    }

});