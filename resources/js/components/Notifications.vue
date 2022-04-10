<template>
    <div id="">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">{{notifications.length}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">{{notifications.length}}</span>
                <div class="dropdown-divider"></div>
                <!-- <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a> -->
                <div v-if="notifications.length">
                    <a href="" class="dropdown-item" v-for="item in notifications" :key="item.id"
                        @click.prevent="readNotification(item.id, item.data.datos.notificacion.url)">
                        <i class="fas fa-envelope mr-2" v-show="item.data.datos.notificacion.msj"></i>
                       {{item.data.datos.notificacion.msj}}
                        <!-- <span class="float-right text-muted text-sm">3 mins</span> -->
                    </a>
                </div>
                <div v-else>
                    <a href="" class="ml-5" style="color: black"><span>no tienes notificaciones</span></a>
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">Todas Las Notificaciones</a>
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
                notifications: [], 
            }
		},

        methods:{
            readNotification(id,ruta){
                // let url = '/lorgeliz_tienda_copia/public/notification/'+id;
                let url = `${this.ruta}/notification/${id}`
                axios.put(url).then(response => {
                    window.location.href = ruta;
                }).catch(error => {
                    console.log(error);
                }); 
            }
        },

        created() {
            // let url = '/lorgeliz_tienda_copia/public/notification';
            let url = `${this.ruta}/notification`;
            axios.get(url).then(response => {
                this.notifications = response.data;
            }).catch(error => {
                console.log(error)
            });

            var clienteId = $('meta[name="clienteId"]').attr('content');

            Echo.private('App.Cliente.' + clienteId).notification((notification) => {
                this.notifications.unshift(notification);
            });
        }
    }
</script>
