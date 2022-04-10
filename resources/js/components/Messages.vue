<template>
    <div>
        <!-- Messages Dropdown Menu -->
        <li id="" class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-comments"></i>
            <span class="badge badge-danger navbar-badge">{{messages.length}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item" v-for="item in messages" :key="item.id"  @click.prevent="read_at(item.id)">
                <!-- Message Start -->
                <div class="media">
                
                <img :src="'../storage/' + item.user.imagene.url" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                    <h3 class="dropdown-item-title">
                    {{item.user.nombres}} {{item.user.apellidos}}
                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                    </h3>
                    <p class="text-sm">{{item.mensaje}}...</p>
                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
                </div> 
                <!-- Message End -->
            </a> 
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">Ver Todos Los Mensajes</a>
            </div>
        </li>
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
               messages: [], 
            }
		},

        methods:{
            read_at(id){
                // let url = '/lorgeliz_tienda_copia/public/chats/'+id;
                // let me = this;
                let url = `${this.ruta}/chats/${id}`
                axios.put(url).then(response => {
                    this.loadMessages();
                }).catch(error => {
                    console.log(error);
                });  
            },

            //obtiene los mensajes del cliente para mostrar las notificaciones
            loadMessages(){
                // let url = '/lorgeliz_tienda_copia/public/chats/messages';
                let url = `${this.ruta}/chats/messages`
                axios.get(url).then(response => {
                    this.messages = response.data.chats;
                }).catch(error => {
                    console.log(error)
                });
            }
        },
        created() {
            this.loadMessages();

            window.Echo.channel('chat-added').listen('ChatEvent', (e) => {
                this.loadMessages();
            });
        }
    }
</script>
