

const product = new Vue({
    el: '#informecliente',
    data: {
        
    }, 
    
    methods: {
        pdfInformeClientes(){
            // let url = '/lorgeliz_tienda_copia/public/admin/informes/pdf/clientes';
           let url = 'http://dev.lorenzogeliztienda.com/admin/informes/pdf/clientes'

            window.open(url);
            
        },
        
    },

});