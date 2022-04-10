

const product = new Vue({
    el: '#infventashow',
    data: {
        mes: ''
    }, 
    
    methods: {
        pdfInformeVentas(){
            window.open('/lorgeliz_tienda_copia/public/admin/informes/pdf/ventas/mes?mes='+this.mes);
        },
        
    },

    mounted(){

        this.mes = data.datos.ventames;
    }

});