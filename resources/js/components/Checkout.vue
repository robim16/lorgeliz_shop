<template>
    <main>
        <div class="checkout">
			<div class="container">
				<div class="row">
					<!-- Billing -->
					<div class="col-lg-6">
						<div class="billing">
							<div class="checkout_title">Información de Pago</div>
							<div class="checkout_form_container">
								<form action="#" id="checkout_form" class="checkout_form">
									<div class="row">
										<div class="col-lg-6">
											<!-- Name -->
											<label for="checkout_name">Nombres</label>
											<input type="text" id="checkout_name" class="checkout_input" placeholder="Nombres" required="required" value="" v-model="nombres">
										</div>
										<div class="col-lg-6">
											<!-- Last Name -->
											<label for="checkout_last_name">Apellidos</label>
											<input type="text" id="checkout_last_name" class="checkout_input" placeholder="Apellidos" required="required" value="" v-model="apellidos">
										</div>
									</div>
									<div>
										<!-- Company -->
										<label for="checkout_company">Empresa</label>
										<input type="text" id="checkout_company" placeholder="Empresa" class="checkout_input">
									</div>
									<div>
										<!-- Country -->
										<label for="checkout_country">País</label>
										<select name="checkout_country" id="checkout_country" class="dropdown_item_select checkout_input" require="required">
											<option>Colombia</option>
										</select>
									</div>
									<div>
										<!-- Province -->
										<label for="checkout_province">Departamento</label>
										<select name="checkout_province" id="checkout_province" class="dropdown_item_select checkout_input" require="required" v-model="departamento">
                                            <option :value="departamento" v-for="departamento in departamentos" :key="departamento">
                                                {{ departamento }}
                                            </option>
										</select>
									</div>
									<div>
										<!-- City / Town -->
										<label for="checkout_city">Ciudad</label>
										<select name="checkout_city" id="checkout_city" class="dropdown_item_select checkout_input" require="required" v-model="municipio">
											<option :value="municipio" selected v-for="municipio in municipios" :key="municipio">
                                                {{ municipio }}
                                            </option>
										</select>
									</div>
									<div>
										<!-- Address -->
										<label for="checkout_address">Dirección del envío</label>
										<input type="text" id="checkout_address" class="checkout_input" placeholder="Dirección" required="required" value="" v-model="address_billing">
										<a href="" class="text-primary" data-toggle="modal" data-target="#modalDir">cambiar</a>
									</div>
									
									<div>
										<!-- Phone no -->
										<label for="checkout_phone">Teléfono</label>
										<input type="phone" id="checkout_phone" class="checkout_input" placeholder="Teléfono" required="required" value="" v-model="mobilephone_billing">
									</div>
									<div>
										<!-- Email -->
										<label for="checkout_email">Email</label>
										<input type="phone" id="checkout_email" class="checkout_input" placeholder="Email" required="required" value="" v-model="email">
									</div>
									<div class="checkout_extra">
										<ul>
											<li class="billing_info d-flex flex-row align-items-center justify-content-start">
												<label class="checkbox_container">
													<input type="checkbox" id="cb_1" name="billing_checkbox" class="billing_checkbox">
													<span class="checkbox_mark"></span>
													<span class="checkbox_text">Términos y condiciones</span>
												</label>
											</li>
											
											<li class="billing_info d-flex flex-row align-items-center justify-content-start">
												<label class="checkbox_container">
													<input type="checkbox" id="cb_3" name="billing_checkbox" class="billing_checkbox">
													<span class="checkbox_mark"></span>
													<span class="checkbox_text">Suscríbete a nuestro boletín</span>
												</label>
											</li>
										</ul>
									</div>
								</form>
							</div>
						</div>
					</div>

					<!-- Cart Total -->
					<div class="col-lg-6 cart_col">
						<div class="cart_total">
							<div class="cart_extra_content cart_extra_total">
								<div class="checkout_title">Total carrito</div>
								<ul class="cart_extra_total_list">
									<li class="d-flex flex-row align-items-center justify-content-start">
										<div class="cart_extra_total_title">Subtotal</div>
										<div class="cart_extra_total_value ml-auto">${{ subtotal }}</div>
									</li>
									<li class="d-flex flex-row align-items-center justify-content-start">
										<div class="cart_extra_total_title">Envío</div>
										<div class="cart_extra_total_value ml-auto">${{ envio }}</div>
									</li>
									<li class="d-flex flex-row align-items-center justify-content-start">
										<div class="cart_extra_total_title">Total</div>
										<div class="cart_extra_total_value ml-auto">${{ amount }}</div>
									</li>
								</ul>
                                <div class="payment_options">
                                    <div class="checkout_title">Método de Pago</div>
                                    <ul>
                                        <li class="shipping_option d-flex flex-row align-items-center justify-content-start">
                                            <label class="radio_container">
                                                <input type="radio" id="radio_2" name="payment_radio" class="payment_radio" @click="setOption(1)" :checked="option==1">
                                                <span class="radio_mark"></span>
                                                <span class="radio_text">Pagar contra entrega</span>
                                            </label>
                                        </li>
                                        <li class="shipping_option d-flex flex-row align-items-center justify-content-start">
                                            <label class="radio_container">
                                                <input type="radio" id="radio_3" name="payment_radio" class="payment_radio" @click="setOption(2)" :checked="option==2">
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
                                    <button @click.prevent="verifyAndSale" :disabled="isDisabled">realizar pedido</button>
                                </div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </main>
</template>

<script>
export default {
    props: {
        ruta:{
            required: true,
            type: String
        },
		carrito:{
			required: true,
			type: Object
		},
		factura:{
			required: true,
			type: Object
		},
		direcciones:{
			required: true,
			type: Array
		}
    },
    data() {
        return {
            option: 0, 
            name: "Artículos de moda",
            description: "Pedidos realizados en Lorgeliz Shopp",
            invoice: "",
            currency: "cop",
            amount: "",
			subtotal: "",
			envio: "",
            tax_base: "0",
            tax: "0",
            country: "co",
            lang: "es",	
            external: "false",
            confirmation: this.ruta + "/checkout/epayco/confirm",
            response: this.ruta + "/checkout/epayco",
            p_confirm_method: "POST",
            name_billing: "",
            address_billing: "",
            type_doc_billing: "cc",
            mobilephone_billing: "",
            number_doc_billing: "",
            key: '15f20d656c02a318876c678239344a0e',
            test: true,
            isDisabled: false,
            departamento: "", 
            departamentos: [],
            municipio: "",
            municipios: [],
			jsonFinal: '',
			nombres: '',
			apellidos: '',
			pais: '', 
			email: ''
        }
    },
    methods:{

        init(){
			return new Promise(async (resolve, reject) => {
				await this.loadJSON(response => {

					// Parse JSON string into object
					var JSONFinal =  JSON.parse(response);
					this.departamentos = JSONFinal.map(d => d.departamento);

					resolve(JSONFinal);
				});
			
			});
		}, 


		loadJSON(callback) {
            let url = `${this.ruta}/colombia-json-master/colombia-json-master/colombia.json`
			var xobj = new XMLHttpRequest();
			xobj.overrideMimeType("application/json");
			xobj.open("GET", url, true); // Reemplaza colombia-json.json con el nombre que le hayas puesto
			xobj.onreadystatechange = function () {
				if (xobj.readyState == 4 && xobj.status == "200") {
					callback(xobj.responseText); //el callback recibe por parámetro el response de la petición
				}
			};
			xobj.send(null);
		},


        setMunicipios(JSONFinal) {
			
			const departamento = this.departamento;
			const filtrados = JSONFinal.filter(d => d.departamento === departamento);
		    this.municipios = filtrados[0].ciudades;

			// $('#checkout_city').append('<option value="0">Seleccione uno</option>')
			
		},


		selectDirection(direccion) {
			axios.post(`${this.ruta}/direcciones/seleccionar`, {direccion})
				.then(response => {
					var element = document.getElementById("alerta");
					element.classList.remove("d-none");

					setTimeout(() => {
						element.classList.add("d-none");
					}, 6000);
				}).catch(err => {
					console.log(err);
				})
		},


        setOption(option){
            this.option = option;
        },


        verifyAndSale(){
            let url = `${this.ruta}/stock/verificar`

            axios.get(url)
            .then(response => {
                if (response.data.data == 'success') {
                    if (this.option == 1) {

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

                        this.isDisabled = true;

                        var handler = ePayco.checkout.configure({
							key: this.key,
							test: this.test
						})  

						var data={
						name: this.name,
						description: this.description,
						invoice: this.invoice,
						currency: this.currency,
						amount: parseInt(this.amount),
						tax_base: parseInt(this.tax_base),
						tax: parseInt(this.tax),
                        tax_ico: parseInt(this.tax),
						country: this.country,
						lang: this.lang,

						external: this.external,

						confirmation: this.confirmation,
						response:`${this.response}/${this.invoice}`,
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


		this.nombres = this.carrito.cliente.user.nombres;
		this.apellidos = this.carrito.cliente.user.apellidos;
		this.departamento = this.carrito.cliente.user.departamento;
		this.municipio = this.carrito.cliente.user.municipio;
		this.amount = this.carrito.total;
        this.name_billing = `${this.carrito.cliente.user.nombres}${this.carrito.cliente.user.apellidos}`;
        this.address_billing = this.carrito.cliente.user.direccion;
        this.mobilephone_billing = this.carrito.cliente.user.telefono;
        this.number_doc_billing = this.carrito.cliente.user.identificacion;
        this.invoice = this.factura.id;
		this.email = this.carrito.cliente.user.email;
		this.subtotal = this.carrito.subtotal;
		this.envio = this.carrito.envio;
        this.isDisabled = false;


		this.init().
		then((data) => {
			this.jsonFinal = data;
			this.setMunicipios(this.jsonFinal);
		});

        document.addEventListener("DOMContentLoaded", function(event) {
            // this.jsonFinal = '';

			document.getElementById('checkout_province').addEventListener('change', function() {
				this.municipio = '';
                this.setMunicipios(this.jsonFinal);
  			});

        });
    }
}
</script>

