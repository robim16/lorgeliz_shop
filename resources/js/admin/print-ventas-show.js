

const product = new Vue({
    el: '#infventashow',
    data: {
        mes: ''
    }, 
    
    methods: {
        pdfInformeVentas(){
            // let url = '/lorgeliz_tienda_copia/public/admin/informes/pdf/ventas/mes?mes='+this.mes

            let url = 'http://dev.lorenzogeliztienda.com/admin/informes/pdf/ventas/mes?mes='+this.mes
            window.open(url);
        },
        
    },

    mounted(){

        this.mes = data.datos.ventames;
    }

});