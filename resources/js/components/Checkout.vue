<template>
    <main>
        <div class="payment_options">
            <div class="checkout_title">Método de Pago</div>
            <ul>
            <li class="shipping_option d-flex flex-row align-items-center justify-content-start">
                <label class="radio_container">
                    <input type="radio" id="radio_2" name="payment_radio" class="payment_radio" @click.prevent="setOption(1)" :checked="option==1">
                    <span class="radio_mark"></span>
                    <span class="radio_text">Pagar contra entrega</span>
                </label>
            </li>
            <li class="shipping_option d-flex flex-row align-items-center justify-content-start">
                <label class="radio_container">
                    <input type="radio" id="radio_3" name="payment_radio" class="payment_radio" @click.prevent="setOption(2)" :checked="option==2">
                    <span class="radio_mark"></span>
                    <span class="radio_text">Pagar con epayco</span>
                </label>
            </li>
            </ul>
        </div>
        <div class="cart_text">
            <p>Puedes pagar contra entrega o a tráves de epayco. Aceptamos todas las tarjetas, efecty, pse, daviplata y otros medios</p>
        </div>
        <div class="checkout_button trans_200">
            <a href="" @click.prevent="verifyAndSale">realizar pedido</a>
		</div>
    </main>
</template>

<script>
export default {
    props: {
        ruta:{
            required: true,
            type: String
        }
    },
    data() {
        return {
            option: 0, 
            name: "Artículos de moda",
            description: "Pedidos realizados en Lorgeliz Shopp",
            invoice: "1234",
            currency: "cop",
            amount: "",
            tax_base: "0",
            tax: "0",
            country: "co",
            lang: "es",	
            external: "false",
            // confirmation: "http://localhost/lorgeliz_tienda_copia/public/ventas/epayco/confirm",
            // response: "http://localhost/lorgeliz_tienda_copia/public/payments/epayco/response",
            confirmation: this.ruta + "/ventas/epayco/confirm",
            response: this.ruta + "/payments/epayco/response",
            p_confirm_method: "POST",
            name_billing: "",
            address_billing: "",
            type_doc_billing: "cc",
            mobilephone_billing: "",
            number_doc_billing: "",
            key: '12d3b45147fae13431996471aa5966af',
            test: true
        }
    },
    methods:{
        setOption(option){
            this.option = option;
        },

        verifyAndSale(){

            // let url = '/lorgeliz_tienda_copia/public/stock/verificar'
            let url = `${this.ruta}/stock/verificar`

            axios.get(url)
            .then(response => {
                if (response.data.data == 'success') {
                    if (this.option == 1) {

                        // let url = '/lorgeliz_tienda_copia/public/ventas';

                        let url = `${this.ruta}/ventas`;

                        axios.post(url)
                        .then(response => {
                            if (response.data.data == 'success') {
                                // var pedido = response.pedido;
                                swal(
                                    'Pedido recibido!',
                                    'Hemos recibido tu pedido. En breve empezaremos a alistarlo y nos pondremos en contacto contigo!',
                                    'success'
                                )
                                // window.location.href = `/lorgeliz_tienda_copia/public/pedidos/` + pedido;
                            }

                        })
                        .catch(error => {
                            console.log(error)
                        });
                        
                    } else {
                        var handler = ePayco.checkout.configure({
							key: this.key,
							test: this.test
						})  

						var data={
						name: this.name,
						description: this.description,
						invoice: this.invoice,
						currency: this.currency,
						amount: this.amount,
						tax_base: this.tax_base,
						tax: this.tax,
						country: this.country,
						lang: this.lang,

						external: this.external,

						confirmation: this.confirmation,
						response: this.response,
						p_confirm_method: this.p_confirm_method,

						name_billing: this.name_billing,
						address_billing: this.address_billing,
						type_doc_billing: this.type_doc_billing,
						mobilephone_billing: this.mobilephone_billing,
						number_doc_billing: this.number_doc_billing

						}
						handler.open(data)
                    }
                }
                else{
					swal(
						'Uno de tus productos está agotado!',
						'Te informamos que uno de los productos que pusiste en el carrito se agotó!',
						'error'
					)
					setTimeout(() => {
						// window.location.href = `/lorgeliz_tienda_copia/public/cart`;

                        window.location.href = `${this.ruta}/cart`;
					}, 4000);
				}
            })
            .catch(error => {
                console.log(error)
            });
     
        }
    },
    mounted() {
        this.amount = data.datos.amount;
        this.name_billing = data.datos.name_billing;
        this.address_billing = data.datos.address_billing;
        this.mobilephone_billing = data.datos.mobilephone_billing;
        this.number_doc_billing = data.datos.number_doc_billing;
    }
}
</script>

