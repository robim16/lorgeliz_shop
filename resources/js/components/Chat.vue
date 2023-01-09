<template>
    <!-- <section class="content"> -->
    <!-- <div id=""> -->
        <!-- <div class="container-fluid">
            <div class="row"> -->
                <div class="col-md-3 ml-auto mr-5">
                    <!-- DIRECT CHAT PRIMARY -->
                    <div class="card card-primary card-outline direct-chat direct-chat-primary">
                    <div class="card-header">
                        <h3 class="card-title">Lorgeliz Chat</h3>
    
                        <div class="card-tools">
                        <span title="3 New Messages" class="badge bg-primary">3</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                            <i class="fas fa-comments"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!-- Conversations are loaded here -->
                        <div class="direct-chat-messages">
                            <!-- Message. Default to the left -->
                            <div v-for="message in arrayMensajes" :key="message.id" :class="message.from_id == user ? 'direct-chat-msg' : 'direct-chat-msg right'">
                                <div class="direct-chat-infos clearfix">
                                <span :class="message.from_id === user ? 'direct-chat-name float-left' : 'direct-chat-name float-right'">{{ message.user.nombres}}</span>
                                <span :class="message.from_id === user ? 'direct-chat-timestamp float-right' : 'direct-chat-timestamp float-left'">{{ message.fecha}}</span>
                                </div>
                                <!-- /.direct-chat-infos -->
                                <img class="direct-chat-img" :src="urlLength === 4 ? 'storage/' + message.user.imagene.url : '../storage/' + message.user.imagene.url" alt="Message User Image">
                                <!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                {{ message.mensaje}}
                                </div>
                                <!-- /.direct-chat-text -->
                            </div>
                            <!-- /.direct-chat-msg -->
                        </div>
                        <!--/.direct-chat-messages-->
    
                        <!-- Contacts are loaded here -->
                        <div class="direct-chat-contacts">
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
                                </div>
                                <!-- /.contacts-list-info -->
                            </a>
                            </li>
                            <!-- End Contact Item -->
                        </ul>
                        <!-- /.contatcts-list -->
                        </div>
                        <!-- /.direct-chat-pane -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <form action="#" method="post">
                        <div class="input-group">
                            <input type="text" name="message" placeholder="Escribe tu mensaje" class="form-control" v-model="mensaje">
                            <span class="input-group-append">
                            <button type="submit" class="btn btn-primary" @click.prevent="sendMessage">Enviar</button>
                            </span>
                        </div>
                        </form>
                        <span style="color: rgb(243, 61, 61); font-weight: bold;" v-show="error">¡Escribe un mensaje!</span>
                    </div>
                    <!-- /.card-footer-->
                    </div>
                    <!--/.direct-chat -->
                </div>
                <!-- /.col -->
            <!-- </div>
        </div>
    </div> -->
    
<!-- </section> -->
</template>

<script>
    export default {
        // props: ['role_id'],
        props: {
            // role_id:{
            //     required:true,
            //     type:Number
            // }

            ruta:{
                required: true,
                type: String
            }
        },
        data (){
            return {
                mensaje: '',
                user: '',
                arrayMensajes: [],
                error: false, 
                show: false,
                urlLength: ''
            }
		},

        methods:{

            loadMessages(){
                // let url ='/lorgeliz_tienda_copia/public/chats';
                let url = `${this.ruta}/chats`
                axios.get(url).then(response =>{
                    this.arrayMensajes = response.data.chats;
                    this.user = response.data.user;
                }).catch(error => {
                    console.log(error);
                });
            },

            sendMessage(){
                if (this.mensaje != '') {

                    this.error = false;
                    // let url = '/lorgeliz_tienda_copia/public/chats';
                    // let me = this;

                    let url = `${this.ruta}/chats`

                    axios.post(url,{
                        'mensaje': this.mensaje,
                    }).then(response => {
                        this.mensaje = '';
                        if (response.data.data == 'success') {
                            this.loadMessages();
                        }
                    }).catch(error => {
                        console.log(error);
                    })
                }
                else{
                    this.error = true;
                }
            }
        },

       created() {
            this.loadMessages();
            var url = window.location.pathname;
            this.urlLength = url.split('/').length;
           

            window.Echo.channel('chat-added').listen('ChatEvent', (e) => {
                let chats = e.data.chats;
                // let chats = e.data;
                
                // this.arrayMensajes = [];
                // chats.map(chat => {
                //     this.arrayMensajes.push(chat);
                // });

                this.arrayMensajes.push(chats);
            });
        }
    }
</script>