<template>
	<main>
	
    <div class="products" id="">
		<div class="container">
			<div class="row products_bar_row">
				<div class="col">
					<div class="products_bar d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-start justify-content-center">
						<div class="products_bar_links">
							<ul class="d-flex flex-row align-items-start justify-content-start">
								<li :class="listar == 7 ? 'active' : ''"><a href="" @click.prevent="getproductos(1)">Todos</a></li>
								<li :class="listar == 4 ? 'active' : ''"><a href="" @click.prevent="hotProducts(1)">Populares</a></li>
								<li :class="listar == 6 && estado == 1 ? 'active' : ''"><a href="" @click.prevent="getProductByState(1,1)">Nuevos</a></li>
								<li :class="listar == 5 ? 'active' : ''"><a href="" @click.prevent="saleProductos(1)">Más Vendidos</a></li>
								<li :class="listar == 6 && estado == 2 ? 'active' : ''"><a href="" @click.prevent="getProductByState(1,2)">Ofertas</a></li>
							</ul>
							
						</div>
						<div class="products_bar_side d-flex flex-row align-items-center justify-content-start ml-lg-auto">
							<div class="products_dropdown product_dropdown_sorting">
								<div class="isotope_sorting_text"><span>Ordenar Por</span><i class="fa fa-caret-down" aria-hidden="true"></i></div>
								<ul>
									<li class="item_sorting_btn" @click.prevent="getproductos(1)">Default</li>
									<li class="item_sorting_btn" @click.prevent="getProductsByOrder(1,1)">Precio</li>
									<li class="item_sorting_btn" @click.prevent="getProductsByOrder(1,2)">Nombre</li>
								</ul>
							</div>

							
							<div class="products_dropdown text-right product_dropdown_filter">
								<div class="isotope_filter_text"><span>Género</span><i class="fa fa-caret-down" aria-hidden="true"></i></div>
								<ul>
									<li class="item_filter_btn" @click.prevent="getProductByGenre(1,1)">Masculino</li>
									<li class="item_filter_btn" @click.prevent="getProductByGenre(1,2)">Femenino</li>
									<li class="item_filter_btn" @click.prevent="getProductByGenre(1,3)">Niños</li>
									
								</ul>
							</div>
							<div class="products_dropdown text-right product_dropdown_filter">
								<div class="isotope_filter_text"><span>Filtrar</span><i class="fa fa-caret-down" aria-hidden="true"></i></div>
								<ul>
									<li class="item_filter_btn" @click.prevent="getproductos(1)">Todos</li>
									<li class="item_filter_btn" @click.prevent="hotProducts(1)">Populares</li>
									<li class="item_filter_btn" @click.prevent="getProductByState(1,1)">Nuevos</li>
									<li class="item_filter_btn" @click.prevent="saleProductos(1)" v-text="smallScreen != true ? 'Más Vendidos' : '+ Vendidos' "></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div
				v-if="arrayProductos.length == 0"
				class="col"
			>
				<a href="#" v-if="fetch == false" class="col-md-2 offset-5">
					<img src="img/preloader.gif" />
				</a>

				<div class="alert alert-primary alert-dismissible fade show" role="alert" v-else>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<p>No se ha encontrado ningún resultado que coincida con tu búsqueda</p>
				</div>
    		</div>

			<div style="padding-bottom: 0px" v-else>
				<div class="row products_row products_container grid">
						
					<div v-for="producto in arrayProductos" :key="producto.id" class="col-xl-4 col-md-6 grid-item">
						
						<div class="product">
							<span class="badge-new" v-show="producto.producto.estado==1"><b v-text="'Nuevo'"></b></span>
							<span class="badge-offer"><b v-text="'-' + producto.producto.porcentaje_descuento +'%'" v-show="producto.producto.porcentaje_descuento>0"></b></span>
							<div class="product_image">
								<a :href="'productos/' + producto.slug">
									<img :src="'storage/' + producto.imagenes[0].url" alt="producto">
									<!--<img :src="producto.url" alt="producto">!-->
								</a>
							</div>
							<div class="product_content">
								<div class="product_info d-flex flex-row align-items-start justify-content-start">
									<div>
										<div>
										<div class="product_name"><a :href="'productos/' + producto.slug" v-text="producto.producto.nombre + '-' + producto.color.nombre"></a></div>
										</div>
									</div>
									<div class="ml-auto text-right">
										<div class="product_category">En <a href="" v-text="producto.producto.tipo.nombre"  @click.prevent="getProductByTipo(1,producto.producto.tipo_id)"></a></div>
										<div class="product_price text-right">{{ producto.producto.precio_actual | currencyFormat }}<span></span></div>
										<del class="price-old text-right" v-show="producto.producto.precio_actual<producto.producto.precio_anterior" style="font-size: 17px">{{ producto.producto.precio_anterior | currencyFormat }}</del>
									</div>
								</div>
								<div class="product_buttons">
									<div class="text-right d-flex flex-row align-items-start justify-content-start">										
										<div class="product_button product_cart text-center d-flex flex-column align-items-center justify-content-center">
											<div><div><img src="asset/images/cart.svg" class="svg" alt=""><div>+</div></div></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				
				</div>	
			</div>
					
			<div class="row page_nav_row">
				<div class="col">
					<div class="page_nav">
						
						<ul class="d-flex flex-row align-items-start justify-content-center">
							<li class="" v-if="pagination.current_page > 1">
								<a href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
							</li>
							<li v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
								<a href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
							</li>
							<li v-if="pagination.current_page < pagination.last_page">
								<a class="" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
							</li>
						</ul>
                        
					</div>
				</div>
			</div>
		</div>
	</div>
    </main>
    
</template>

<script>
    export default {
		// props : ['keyword'],
		props: {
            keyword:{
                required:true,
                type:String
            },

			ruta:{
				required: true,
				type: String
			}
        },
        data (){
            return {
                active: '',
                arrayProductos: [],
                criterio: '',
                genero: '',
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                listar: 0,
                value: 0,
                tipo: '',
                estado: 0,
                categoria: '',
                subcategoria: '',
				smallScreen: false,
				fetch: false
            }
		},
		computed:{
			isActived: function(){
				return this.pagination.current_page;
			},
			//Calcula los elementos de la paginación
			pagesNumber: function() {
				if(!this.pagination.to) {
					return [];
				}
				
				var from = this.pagination.current_page - this.offset; 
				if(from < 1) {
					from = 1;
				}

				var to = from + (this.offset * 2); 
				if(to >= this.pagination.last_page){
					to = this.pagination.last_page;
				}  

				var pagesArray = [];
				while(from <= to) {
					pagesArray.push(from);
					from++;
				}
				return pagesArray;
			},

			//esta función se utiliza para realizar búsquedas cuando se hace dentro de la página de categorias
			buscar: function() {
				if (this.keyword != '') {
					this.getProductByKeyword(1,this.keyword);
					// return true
				}
				else{
					this.getproductos(1);
				}
				return this.keyword;
			}

    	}, 
        methods : {
            getproductos(page){

				this.listar = 7;
				// let url = '/lorgeliz_tienda_copia/public/categorias/productos?page=' + page;
				// let url = '/lorgeliz_tienda_copia/public/api/categorias/default?page=' + page;

				let url = `${this.ruta}/api/categorias/default?page=${page}`

				axios.get(url).then(response => {
					var respuesta = response.data;
					this.arrayProductos = respuesta.productos.data;
					this.pagination = respuesta.pagination;
					this.active = 0;
				}); 

        	},

			getProductByState(page,estado){
				
				this.listar = 6; 
				this.estado = estado;

				// let url = '/lorgeliz_tienda_copia/public/api/categorias/productos/estado?page=' + page 
				// 	+ '&estado='  + this.estado;

				let url = `${this.ruta}/api/categorias/productos/estado?page=${page}&estado=${this.estado}`

				// axios.get(url).then(response => {
				// 	var respuesta = response.data;
				// 	this.arrayProductos = respuesta.productos.data;
				// 	this.pagination = respuesta.pagination;
				// }); 

				this.getData(url);
			},

			saleProductos(page){
				
				this.listar = 5;

				// let url = '/lorgeliz_tienda_copia/public/api/categorias/productos/vendidos?page=' + page;

				let url = `${this.ruta}/api/categorias/productos/vendidos?page=${page}`
				// axios.get(url).then(response => {
				// 	var respuesta = response.data;
				// 	this.arrayProductos = respuesta.productos.data;
				// 	this.pagination = respuesta.pagination;
				// }); 

				this.getData(url);
			},

			hotProducts(page){

				this.listar = 4;

				// let url = '/lorgeliz_tienda_copia/public/api/categorias/productos/vistos?page=' + page;

				let url = `${this.ruta}/api/categorias/productos/vistos?page=${page}`

				// axios.get(url).then(response => {
				// 	var respuesta = response.data;
				// 	this.arrayProductos = respuesta.productos.data;
				// 	this.pagination = respuesta.pagination;
				// }); 

				this.getData(url);
			},

			getProductsByOrder(page,orden){

				this.listar = 3;
				this.orden = orden;

				if (orden==1) {
					this.criterio='precio_actual';
				}
				else{
					this.criterio='nombre'; 
				}

				// let url = '/lorgeliz_tienda_copia/public/api/categorias/productos/orden?page=' + page
				// 	+ '&criterio=' + this.criterio;

				let url = `${this.ruta}/api/categorias/productos/orden?page=${page}&criterio=${this.criterio}`

				// axios.get(url).then(response => {
				// 	var respuesta = response.data;
				// 	this.arrayProductos = respuesta.productos.data;
				// 	this.pagination = respuesta.pagination;
				// }); 

				this.getData(url);
			},

			getProductByTipo(page,tipo){
			
				this.listar = 2;
				this.tipo = tipo;

				// let url = '/lorgeliz_tienda_copia/public/api/categorias/productos/tipo?page=' + page
				//  + '&tipo=' + this.tipo;

				let url = `${this.ruta}/api/categorias/productos/tipo?page=${page}&tipo=${this.tipo}`

				// axios.get(url).then(response => {
				// 	var respuesta = response.data;
				// 	this.arrayProductos = respuesta.productos.data;
				// 	this.pagination = respuesta.pagination;
				// }); 

				this.getData(url);
			},

			getProductByGenre(page,value){
			
				this.listar = 1;
				this.value = value;

				if (this.value==1) {
					this.genero = 'hombres';
				}
				
				if (this.value==2) {
					this.genero = 'mujeres';
				}

				if (this.value==3) {
					this.genero = 'niños';
				}
				
				// let url = '/lorgeliz_tienda_copia/public/api/categorias/productos/genero?page=' + page
				//  + '&genero=' + this.genero;

				let url = `${this.ruta}/api/categorias/productos/genero?page=${page}&genero=${this.genero}`
			
				// axios.get(url).then(response => {
				// 	var respuesta = response.data;
				// 	this.arrayProductos = respuesta.productos.data;
				// 	this.pagination = respuesta.pagination;
				// });
				
				this.getData(url);
			},

			getProductByKeyword(page,keyword){
				this.listar = 8;

				// let url = '/lorgeliz_tienda_copia/public/api/categorias/productos/keyword?page=' + page
				//  + '&keyword=' + keyword;

				let url = `${this.ruta}/api/categorias/productos/keyword?page=${page}&keyword=${keyword}`

				// axios.get(url).then(response => {
				// 	var respuesta = response.data;
				// 	this.arrayProductos = respuesta.productos.data;
				// 	this.pagination = respuesta.pagination;
				// }); 
				this.getData(url);
			},

			getData(url){
				this.fetch = false;

				axios.get(url).then(response => {
					this.fetch = true;

					var respuesta = response.data;
					
					if (respuesta.productos.data.length > 0) {
						
						this.arrayProductos = respuesta.productos.data;
						this.pagination = respuesta.pagination;
					}
				}).catch(error => {
                    console.log(error);
                });
			},

			cambiarPagina(page){
				//Actualiza la página actual
				
				this.pagination.current_page = page;
				//Envia la petición para visualizar la data de esa página

				if (this.listar == 1) {
					this.getProductByGenre(page, this.value);
				}

				if (this.listar == 2) {
					this.getProductByTipo(page, this.tipo);
				}

				if (this.listar == 3) {
					this.getProductsByOrder(page, this.orden);
				}

				if (this.listar == 4) {
					this.hotProducts(page);
				}
				
				if (this.listar == 5) {
					this.saleProductos(page);
				}

				if (this.listar == 6) {
					this.getProductByState(page, this.estado);
				}
			
				if (this.listar == 7) {
					this.getproductos(page);
				}

				if (this.listar == 8) {
					this.getproductByKeyword(page, this.keyword);
				}
			},

			screenSize(){
				const windowSize = window.innerWidth;
				if (windowSize <= 411) {
					this.smallScreen = true;
					return
				} else {
					this.smallScreen = false
				}
			}
        },

		beforeCreate() {
			
		},

        created() {

			this.screenSize()
			console.log(this.smallScreen)

			if (localStorage.getItem('category')) {
				this.categoria = JSON.parse(localStorage.getItem('category'));
			}
			
			if (localStorage.getItem('subcategory')) {
				this.subcategoria = JSON.parse(localStorage.getItem('subcategory'));
			}

			if (localStorage.getItem('keyword')) {
				this.keyword = JSON.parse(localStorage.getItem('keyword'));
			}

		   	if (this.categoria == "hombres" && this.subcategoria == '') {
            	this.getProductByGenre(1,1);
				localStorage.removeItem('category');
			} 
			
			if (this.categoria == "mujeres" && this.subcategoria == '') {
				this.getProductByGenre(1,2);
				localStorage.removeItem('category');
			} 

			if (this.categoria == "niños" && this.subcategoria == '') {
				this.getProductByGenre(1,3);
				localStorage.removeItem('category');
			} 

			if (this.categoria == "nuevos" && this.subcategoria == '') {
            	this.getProductByState(1,1);
				localStorage.removeItem('category');
       		} 

			if (this.categoria == "ofertas" && this.subcategoria == '') {
				this.getProductByState(1,2);
				localStorage.removeItem('category');
			} 
			
			if (this.categoria == '' && this.subcategoria == '' && this.keyword == '') {
				this.getproductos(1);
			} 

			if (this.subcategoria != '') {
				this.getProductByTipo(1,this.subcategoria);
				localStorage.removeItem('subcategory');
			}

			if (this.keyword != '') {
				this.getProductByKeyword(1,this.keyword);
				localStorage.removeItem('keyword');
			}
        },

		filters: {
            currencyFormat: function (number) {
                return new Intl.NumberFormat('es-CO', {style: 'currency',currency: 'COP', minimumFractionDigits: 0}).format(number);
            }
        },

		mounted() {

            
           	window.Echo.channel('producto-agotado').listen('ProductoAgotado', (e) => {

                // let products = [];

                products = e.data;

                Object.values(products).map(product => {
                    const index = this.arrayProductos.findIndex(p => p.id == product.id);

                    if (index > -1) {
                        this.arrayProductos.splice(index, 1);
                    }
                })
                
            });

			window.Echo.channel('add-product').listen('AddProductEvent', (e) => {

               	let product = e.data.product;

				const index = this.arrayProductos.findIndex(p => p.id == product.id);

				if (index == -1) {
					if (this.listar === 7) {
						this.getProductos(this.pagination.current_page);
					}

					if (this.listar === 1) {
						this.getProductByGenre(this.pagination.current_page, this.value);
					}

					if (this.listar == 2) {
						this.getProductByTipo(this.pagination.current_page, this.tipo);
					}

					if (this.listar == 3) {
						this.getProductsByOrder(this.pagination.current_page, this.orden);
					}

					if (this.listar == 4) {
						this.hotProducts(this.pagination.current_page);
					}
					
					if (this.listar == 5) {
						this.saleProductos(this.pagination.current_page);
					}

					if (this.listar == 6) {
						if (product.producto.estado === this.estado) {
							this.getProductByState(this.pagination.current_page, this.estado);
						}
					}

					if (this.listar == 8) {
						this.getproductByKeyword(this.pagination.current_page, this.keyword);
					}
			
				}
               
            });

			window.Echo.channel('change-status').listen('ProductStatusEvent', (e) => {
                let productos = e.data;
                let i = 0;
                
				if (this.listar === 6) {
					
					productos.map(item => {
						let index = this.arrayProductos.findIndex(p => p.id == item.id);
	
						if (index > -1) {
							if (!item.producto.estado === this.estado) {
								this.arrayProductos.splice(index, 1);
							}
						}
						else{
							if (item.producto.estado === this.estado) {
								i++;
							}
						}
					})
	
					if (i > 0) {
						this.getProductByState(this.pagination.current_page, this.estado);
					}
				}
            });

			window.Echo.channel('new-visit').listen('VisitEvent', (e) => {
               	if (this.listar == 4) {
					this.hotProducts(this.pagination.current_page);
				}
            });

			window.Echo.channel('new-sale').listen('SalesEvent', (e) => {
               	if (this.listar == 5) {
					this.saleProductos(this.pagination.current_page);
				}
            });
        },

    }
</script>
