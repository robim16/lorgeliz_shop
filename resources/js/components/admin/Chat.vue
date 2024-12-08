<template>

    <div class="row">
        <chat-list @chat="enable" @modal="abrirModal" :ruta="ruta"></chat-list>
        <!-- escucha el evento emitido por el componente hijo y ejecuta la función indicada -->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg pt-5" role="document">
                <div class="modal-content pt-3">
                    <div class="modal-header">
                        <h4 class="modal-title">Seleccionar cliente</h4>
                        <button type="button" class="close" @click="cerrarModal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-4 mr-1" v-model="criterio">
                                            <option value="" selected>Seleccione</option>
                                            <option value="nombres">Nombres</option>
                                            <option value="apellidos">Apellidos</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarClientes(1,buscar,criterio)" class="form-control mr-1" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarClientes(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Imagen</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="cliente in arrayClientes" :key="cliente.id">
                                        <td v-text="cliente.cliente.id"></td>
                                        <td v-text="cliente.nombres"></td>
                                        <td v-text="cliente.apellidos"></td>
                                        <td>
                                            <img :src="'../storage/' + cliente.imagene.url" class="rounded-circle" :style="'width: 34px'">
                                        </td>
                                        <td v-text="cliente.telefono"></td>
                                        <td v-text="cliente.email"></td>
                                        <td>
                                            <a href="" class="btn btn-primary btn-sm btn-icon" title="chatear" @click.prevent="enable(cliente.id)">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        </td>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- <div class="col-md-3 ml-auto mr-5" v-show="show"> -->
            <!-- DIRECT CHAT PRIMARY -->
            <!-- <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header">
                <h3 class="card-title">Direct Chat</h3>

                <div class="card-tools">
                <span title="3 New Messages" class="badge bg-primary">3</span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                    <i class="fas fa-comments"></i>
                </button> -->
                <!-- data-card-widget="remove" -->
                <!-- <button type="button" class="btn btn-tool" @click="disable">
                    <i class="fas fa-times"></i>
                </button>
                </div>
            </div> -->
            <!-- /.card-header -->
            <!-- <div class="card-body"> -->
                <!-- Conversations are loaded here -->
                <!-- <div class="direct-chat-messages"> -->
                    <!-- Message. Default to the left -->
                    <!-- <div v-for="message in arrayMensajes" :key="message.id" :class="message.from_id == user ? 'direct-chat-msg' : 'direct-chat-msg right'">
                        <div class="direct-chat-infos clearfix">
                        <span :class="message.from_id === user ? 'direct-chat-name float-left' : 'direct-chat-name float-right'">{{ message.user.nombres}}</span>
                        <span :class="message.from_id === user ? 'direct-chat-timestamp float-right' : 'direct-chat-timestamp float-left'">{{ message.fecha}}</span>
                        </div> -->
                        <!-- /.direct-chat-infos -->
                        <!-- <img class="direct-chat-img" :src="'../storage/' + message.user.imagene.url" alt="Message User Image"> -->
                        <!-- /.direct-chat-img -->
                        <!-- <div class="direct-chat-text">
                        {{ message.mensaje}}
                        </div> -->
                        <!-- /.direct-chat-text -->
                    <!-- </div> -->
                    <!-- /.direct-chat-msg -->
                <!-- </div> -->
                <!--/.direct-chat-messages-->

                <!-- Contacts are loaded here -->
                <!-- <div class="direct-chat-contacts">
                <ul class="contacts-list">
                    <li>
                    <a href="#">
                        <img class="contacts-list-img" :src="'../adminlte/dist/img/user1-128x128.jpg'" alt="User Avatar">

                        <div class="contacts-list-info">
                        <span class="contacts-list-name">
                            Count Dracula
                            <small class="contacts-list-date float-right">2/28/2015</small>
                        </span>
                        <span class="contacts-list-msg">How have you been? I was...</span>
                        </div> -->
                        <!-- /.contacts-list-info -->
                    <!-- </a>
                    </li> -->
                    <!-- End Contact Item -->
                <!-- </ul> -->
                <!-- /.contatcts-list -->
                <!-- </div> -->
                <!-- /.direct-chat-pane -->
            <!-- </div> -->
            <!-- /.card-body -->
            <!-- <div class="card-footer">
                <form action="#" method="post">
                <div class="input-group">
                    <input type="text" name="message" placeholder="Escribe tu mensaje" class="form-control" v-model="mensaje">
                    <span class="input-group-append">
                    <button type="submit" class="btn btn-primary" v-on:click.prevent="sendMessage()">Enviar</button>
                    </span>
                </div>
                </form>
                <span style="color: rgb(243, 61, 61); font-weight: bold;" v-show="error">¡Escribe un mensaje!</span>
            </div> -->
            <!-- /.card-footer-->
            <!-- </div> -->
            <!--/.direct-chat -->
        <!-- </div> -->
        <!-- /.col -->

        <div v-if="arrayMensajes.length > 0 || getCliente != null" class="col-md-3 ml-auto mr-5">
            <div v-for="(mensajes,index) in arrayMensajes" :key="mensajes.id">
                <messenger :mensajes="mensajes" :user="user" :cliente="activos[index]" :ruta="ruta"></messenger>
            </div>
        </div>
        
    </div>

</template>

<script>
import ChatList from './ChatList.vue';
import Messenger from './Messenger.vue';
    export default {
        components: { ChatList, Messenger },
        props: {
            user:{
                required:true,
                type:Number
            },

            ruta:{
                required: true,
                type: String
            }
        },
        data (){
            return {
                mensaje: '',
                arrayMensajes: [],
                arrayClientes: [],
                activos: [],
                error: false, 
                cliente: '',
                modal: 0,
                criterio: '',
                buscar: '',
                active: '',
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
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

            getCliente: function(){
                
                // if (localStorage.getItem('cliente')) {
				//     this.cliente = JSON.parse(localStorage.getItem('cliente'));//obtenemos el cliente si existe
                //     localStorage.removeItem('cliente');

                //     if (this.cliente != ''){
                //         this.enable(this.cliente);
                //     }
                //     return this.cliente;
			    // }
                // else{
                //     return null;
                // }
                
                let me = this;
              
                (() => {
                    
                    function idCliente() {
                        
                        if (localStorage.getItem('cliente')) {
                            me.cliente = JSON.parse(localStorage.getItem('cliente'));
                            localStorage.removeItem('cliente');
                        }
                    }
                    idCliente();
                })();

                return (this.cliente !=  null) ? this.cliente : null;
                
            },
    	}, 

        methods:{

            loadMessages(cliente){

                let url = `${this.ruta}/admin/chats/${cliente}`;
                axios.get(url).then(response => {
                    this.arrayMensajes.push(response.data.chats); 
                    // this.arrayMensajes = response.data.chats;
                    // this.user = response.data.user;
                })
            },

            // sendMessage(){
            //     if (this.mensaje != '') {

            //         this.error = false;
            //         let url = '/lorgeliz_tienda_copia/public/admin/chats';
            //         let me = this;

            //         axios.post(url,{
            //             'mensaje': this.mensaje,
            //             // 'admin': this.admin, 
            //             'cliente': this.cliente
            //         }).then(function (response) {
            //             me.mensaje = '';
            //             if (response.data.data == 'success') {
            //                 me.loadMessages();
            //             }
            //         }).catch(function (error) {
            //             console.log(error);
            //         })
            //     }
            //     else{
            //         this.error = true;
            //     }
            // }, 

            enable(cliente){// se ejecuta al escuchar el evento chat o al escoger un cliente en el modal
                if (this.modal==1) {//si se hizo desde el modal, se cierra
                    this.cerrarModal();
                }
                // this.show = true;//abre el messenger

                const index = this.activos.indexOf(cliente);

                if (index <= -1) {
                    this.activos.push(cliente);
                    // this.cliente = cliente;
                    this.loadMessages(cliente);//carga los mensajes del cliente
                } 
            },

            cerrarModal(){
                this.modal=0;
            }, 

            abrirModal(){               
                this.modal = 1;
                this.listarClientes(1, this.buscar,this.criterio);
            },

            listarClientes(page, buscar, criterio){

                let url = `${this.ruta}/admin/clientes?page=${page}&buscar=${buscar}&criterio=${criterio}`;

                axios.get(url).then(response =>{
                    var respuesta = response.data;
                    this.arrayClientes = respuesta.clientes.data;
                    this.pagination = respuesta.pagination;
					this.active = 0;
                })
            },


            cambiarPagina(page){
				//Actualiza la página actual
				
				this.pagination.current_page = page;
				//Envia la petición para visualizar la data de esa página
                this.listarClientes(page, this.buscar, this.criterio);
			}
        
        },

        mounted() {

            // this.loadMessages();
            if (this.getCliente != '' && this.getCliente != null){
                this.enable(this.getCliente);
            }

            window.Echo.channel('chat-added').listen('.new-message', (e) => {
                let chats = e.data.chats;
                // this.arrayMensajes = [];
                // console.log(chats);

                const index = this.activos.indexOf(e.data.cliente);

                // if (index > -1) {
                //     //si está en activos
                //     this.arrayMensajes[index].push(chats);
                // } else {
                //     this.enable(e.data.cliente);
                // }

                if (index == -1) {
                    //si no está en activos
                    this.enable(e.data.cliente);
                }
                
            });
        }
    }
</script>

<style>
    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    
</style>