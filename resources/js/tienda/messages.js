
const notification = new Vue({
    el: '#message',
    data: {
        messages: [], 
    },

    computed:{

    },
    methods:{
        read_at(id){
            // let url = '/lorgeliz_tienda_copia/public/chats/'+id;
            let url = 'http://dev.lorenzogeliztienda.com/chats/'+id;
            let me = this;
            axios.put(url).then(response => {
               me.loadMessages();
           });   
        },

        //obtiene los mensajes del cliente para mostrar las notificaciones
        loadMessages(){
            // let url = '/lorgeliz_tienda_copia/public/chats/messages';
            let url = 'http://dev.lorenzogeliztienda.com/chats/messages';
            
            axios.get(url).then(response => {
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