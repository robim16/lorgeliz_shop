

const product = new Vue({
    el: '#informecliente',
    data: {
        
    }, 
    
    methods: {
        pdfInformeClientes(){
            window.open('/lorgeliz_tienda_copia/public/admin/informes/pdf/clientes');
            
        },
        
    },

});