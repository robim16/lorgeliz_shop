const app = new Vue({
    el: '#notification',
    data: {
        notifications: [],
        arrayNotificationsVentas: [], 
    },

    computed:{
       
    },
    methods:{
        readNotification(id,ruta){
            let url = '/admin/notification/'+id;
           
            axios.put(url).then(response => {
                window.location.href = ruta;
            });   
        }
    },
    created() {

        let url = '/admin/notification'
       
        axios.get(url).then(response => {
          this.notifications = response.data;
        }).catch(error => {
            console.log(error)
        });

        var userId = $('meta[name="userId"]').attr('content');

        Echo.private('App.User.' + userId).notification((notification) => {
            this.notifications.unshift(notification);
        });
    }

});