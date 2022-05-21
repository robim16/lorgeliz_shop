

const listsales = new Vue({
    el: '#listdevolucion',
    data: {
       
    }, 
    
    methods: {
        pdfListadoDevoluciones(){
            // let url = '/lorgeliz_tienda_copia/public/admin/devoluciones/listado';
            let url = 'http://lorenzogeliztienda.com/admin/devoluciones/listado'
            window.open(url);
        },
        
    },

    mounted(){


    }

});