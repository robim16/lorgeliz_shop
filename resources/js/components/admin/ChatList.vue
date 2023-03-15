<template>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sección de chats</h3>
                <div class="card-tools">
                    <form>
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <div class="input-group-append">
                                <a href="" class="btn btn-success mx-1" @click.prevent="">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>

                             <div class="input-group-append">
                                <a href="" class="btn btn-primary mx-1" @click.prevent="abrirModal()" title="nuevo chat"><i class="fas fa-plus"></i></a>
                            </div>

                            <input v-model="buscar" type="text" name="buscar" class="form-control float-right" placeholder="Buscar"
                            value="">

                            <div class="input-group-append">
                                <button @click.prevent="chatList(1)" type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Imagen</th>
                            <th>Fecha de último mensaje</th>
                            <th>Ultimo mensaje</th>
                            <th colspan="2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="chat in arrayChats" :key="chat.id">
                            <td> {{chat.id }} </td>
                            <td> <a :href="'../admin/clientes/' + chat.cliente">{{chat.nombres }} {{chat.apellidos }}</a> </td>
                            <td> <img style="height: 40px; width: 40px;"
                                :src="'../storage/' + chat.imagene.url"
                                class="rounded-circle">
                            </td>
                            <td> {{ chat.fecha }} </td>
                            <td> {{ chat.mensaje }} </td>
                            <td> <a class="btn btn-primary" href="" title="ver chat" @click.prevent="chatear(chat.id)"><i class="fas fa-eye"></i></a></td>
                            <!-- <td> <a class="btn btn-primary" href="" title="ver chat" @click.prevent="chatear(chat.cliente)"><i class="fas fa-eye"></i></a></td> -->
                        </tr>
                        
                    </tbody>
                </table>

                <nav>
                    <ul class="pagination">
                        <li class="page-item disabled" aria-disabled="true" aria-label="&laquo; Anterior" v-if="pagination.current_page > 1">
                            <span class="page-link" aria-hidden="true" @click.prevent="cambiarPagina(pagination.current_page - 1)">&lsaquo;</span>
                        </li>
                        <li v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'page-item active' : 'page-item']" aria-current="page">
                            <a href="#" class="page-link" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)" rel="next" aria-label="Siguiente &raquo;">&rsaquo;</a>
                        </li>
                    </ul>
                    
                </nav>
                
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

</template>

<script>
    export default {
        props: {
            ruta:{
                required: true,
                type: String
            }
        },

        data (){
            return {
                active: '',
                arrayChats: [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                buscar: '',
                cliente: ''
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
    	}, 
        methods:{

            chatList(page){
                // if (localStorage.getItem('cliente')) {
				//     this.cliente = JSON.parse(localStorage.getItem('cliente'));//obtenemos el cliente si existe
                //     localStorage.removeItem('cliente');
                //     this.buscar = this.cliente;
			    // }
			
                // let url ='/lorgeliz_tienda_copia/public/admin/chats/get?page='
                //     + page + '&buscar='+ this.buscar;
                
                let url = `${this.ruta}/admin/chats/get?page=${page}&buscar=${this.buscar}`
                // let url ='/lorgeliz_tienda_copia/public/api/admin/chats?page=' + page + '&buscar='+ this.buscar;
                axios.get(url).then(response =>{
                    var respuesta = response.data;
					this.arrayChats = respuesta.chats.data;
                    this.pagination = respuesta.pagination;
					this.active = 0;//obtenemos los mensajes de los clientes o de un cliente si se realizó la busqueda, se muestran en la tabla
                })
            },

            chatear(cliente){
                this.$emit('chat', cliente);//emite un evento que escucha el componente chat, indicando el cliente cuyo chat se desplegará en el messenger, al hacer click sobre él en la tabla
            },

            abrirModal(){
                this.$emit('modal');//emite el evento modal, el cual escucha el componente padre
            },

            cambiarPagina(page){
				//Actualiza la página actual
				
				this.pagination.current_page = page;
				//Envia la petición para visualizar la data de esa página
                this.chatList(page);
			},
        },

        mounted() {
            this.chatList(1);

            window.Echo.channel('chat-added').listen('.new-message', (e) => {
                this.chatList(1);
            });
        }
    }
</script>

