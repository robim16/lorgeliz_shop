const pagos_venta = new Vue({
    el: '#venta_show',
    data: {
        alertShow: false,
        valor: '',
        venta: 0,
        modal: 0,
        modal_epayco: 0,
        x_transaction_date: '',
        x_amount: '',
        x_response: '',
        x_response_reason_text: '',
        x_cod_response: '',
        x_transaction_id: '',
    }, 
    
    methods: {


        facturaVenta(id){
            window.open('/lorgeliz_tienda_copia/public/admin/ventas/factura/'+ id + ',' + '_blank');
           
            // window.open('http://dev.lorenzogeliztienda.com/admin/ventas/factura/'+ id + ',' + '_blank')
            
        },

        registrar_pago(venta){

            let url = `/lorgeliz_tienda_copia/public/admin/ventas/pagar/${venta}`;

            document.getElementById(`valor-error`).innerHTML = '';
            
            axios.put(url,{
                'valor': this.valor,
                
            }).then(response => {

                if (response.data.data == 'success') {
                    
                    this.alertShow = true

                    this.valor = ''
                }


            }).catch(error => {
               
                for (var [el, message] of Object.entries(error.response.data)) {
            
                    document.getElementById(`${el}-error`).innerHTML = message;
                    
                }
            });

        },


        pdfListPagos(){
            let url = '/lorgeliz_tienda_copia/public/admin/payments/list';

            // let url = 'http://dev.lorenzogeliztienda.com/admin/payments/list'
            window.open(url);
        },


        imprimirPago(id){
            // window.open('/lorgeliz_tienda_copia/public/admin/payments/payment/'+ id + ',' + '_blank');
            let url = `/lorgeliz_tienda_copia/public/admin/payments/${id},_blank/pdf`;
            
            // let url = `http://dev.lorenzogeliztienda.com/admin/payments/${id},_blank/pdf`
            window.open(url)
            
        },


        getResponse(ref_payco){

            let url = "https://secure.epayco.co/validation/v1/reference/" + ref_payco;

            axios.get(url).then(response => {
                if (response.success) {
                    this.x_transaction_date = response.data.x_transaction_date;
                    this.x_amount = response.data.x_amount;
                    this.x_response = response.data.x_response;
                    this.x_response_reason_text = response.data.x_response_reason_text;
                    this.x_cod_response = response.data.x_cod_response;
                    this.x_transaction_id = response.data.x_transaction_id;
                }
            }); 

            this.abrirModalEpayco();
        },


        cerrarModal(){
            this.modal = 0;
        }, 


        abrirModal(){               
            this.modal = 1;
        },


        cerrarModalEpayco(){
            this.modal_epayco = 0;
        }, 


        abrirModalEpayco(){               
            this.modal_epayco = 1;
        },

    },

    mounted(){
        this.valor = data.datos.valor;
    }

});

