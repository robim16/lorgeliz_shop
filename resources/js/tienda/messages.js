
const notification = new Vue({
    el: '#message',
    data: {
        messages: [], 
    },

    computed:{
        
    },
    methods:{
        read_at(id){
            let url = '/lorgeliz_tienda_copia/public/chats/'+id;
            let me = this;
            axios.put(url).then(response => {
               me.loadMessages();
            });   
        },

        //obtiene los mensajes del cliente para mostrar las notificaciones
        loadMessages(){
            axios.get('/lorgeliz_tienda_copia/public/chats/messages').then(response => {
                this.messages=response.data.chats;
            }).catch(function(error){
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

});