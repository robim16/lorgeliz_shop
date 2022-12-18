

const payments = new Vue({
    el: '#payments',
    data: {
        x_transaction_date: '',
        x_amount: '',
        x_response: '',
        x_response_reason_text: '',
        x_cod_response: '',
        x_transaction_id: '',
        modal: 0,
    }, 
    
    methods: {
        
        pdfListPagos(){
            // let url = '/lorgeliz_tienda_copia/public/admin/payments/list';
            let url = 'http://lorenzogeliztienda.com/admin/payments/list'
            window.open(url);
        },

        imprimirPago(id){
            // window.open('/lorgeliz_tienda_copia/public/admin/payments/payment/'+ id + ',' + '_blank');
            // let url = `/lorgeliz_tienda_copia/public/admin/payments/${id},_blank/pdf`;
            
            let url = `http://lorenzogeliztienda.com/admin/payments/${id},_blank/pdf`
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

            this.abrirModal();
        },

        cerrarModal(){
            this.modal=0;
        }, 

        abrirModal(){               
            this.modal = 1;
        },
        
    },

    mounted(){


    }

});

