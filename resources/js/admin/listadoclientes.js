

const listsales = new Vue({
    el: '#listclientes',
    data: {
       
    }, 
    
    methods: {
        pdfListadoClientes(){
            // let url = '/lorgeliz_tienda_copia/public/admin/clientes/listado'
            let url = 'http://dev.lorenzogeliztienda.com/admin/clientes/listado';
            window.open(url);
        },
        
    },

    mounted(){


    }

});