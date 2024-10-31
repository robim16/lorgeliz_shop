
const notification = new Vue({
    el: '#chatNotification',
    data: {
        notifications: [],
        location: ''
    },

    computed:{
        
    },
    methods:{
        readchat(chat){
            //marca como leído el mensaje
            let url = '/admin/chats/read/'+chat;
           
            axios.put(url).then(response => {
                
            });   
        },

        initChat(cliente, chat){
            // se ejecuta al hacer click sobre una notificación de chat, redirige al index, despliega la tabla 
            localStorage.setItem('cliente', JSON.stringify(cliente));//guardamos el id del cliente para mostrar sus mensajes

            if (this.location != '/admin/chats') {
                window.location.href = `/admin/chats`;//redirigimos al index, los mensajes se filtraran con id del cliente
                
            }
           
            this.readchat(chat);
        },

        loadNotifications(){
            let url = '/admin/chats/messages';
            
            axios.get(url).then(response => {
                this.notifications=response.data.chats;
                this.location = window.location.pathname;//se utiliza para obtener la url de las imágenes de los usuarios
      
            }).catch(function(error){
                console.log(error)
            });
        }
    },
    created() {
        //obtener los mensajes para mostrar en las notificaciones de chats

        this.loadNotifications();

        window.Echo.channel('chat-added').listen('ChatEvent', (e) => {
            this.loadNotifications();
        });
    }

});