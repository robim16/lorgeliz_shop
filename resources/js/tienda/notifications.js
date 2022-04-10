
const notification = new Vue({
    el: '#clientNotification',
    data: {
        notifications: [], 
    },

    computed:{
        
    },
    methods:{
        readNotification(id,ruta){
            let url = '/lorgeliz_tienda_copia/public/notification/'+id;
            axios.put(url).then(response => {
                window.location.href = ruta;
            });   
        }
    },
    created() {
        axios.get('/lorgeliz_tienda_copia/public/notification').then(response => {
          this.notifications = response.data;
        }).catch(function(error){
           console.log(error)
        });

        var clienteId = $('meta[name="clienteId"]').attr('content');

        Echo.private('App.Cliente.' + clienteId).notification((notification) => {
            this.notifications.unshift(notification);
        });
    }

});