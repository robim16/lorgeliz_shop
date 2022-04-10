const app = new Vue({
    el: '#notification',
    data: {
        notifications: [],
        arrayNotificationsVentas: [], 
    },

    computed:{
        // listar : function(){
        //     //this.arrayNotificationsVentas = Object.values(this.notifications[0]);
        //     //if (this.notifications == '') {
        //         //return this.arrayNotificationsVentas = [];
        //     //}
        //     //else{
        //        // this.arrayNotificationsVentas = Object.values(this.notifications[0]);
        //         //if (this.arrayNotificationsVentas.length > 3) {
        //             //return Object.values(this.arrayNotificationsVentas[4]);
                   
        //         //} else {
        //            // return Object.values(this.arrayNotificationsVentas[0]);
        //         //}
        //    // }
            
        // }
    },
    methods:{
        readNotification(id,ruta){
            let url = '/lorgeliz_tienda_copia/public/admin/notification/'+id;
            axios.put(url).then(response => {
                window.location.href = ruta;
            });   
        }
    },
    created() {

        let url = '/lorgeliz_tienda_copia/public/api/admin/notifications';
        // '/lorgeliz_tienda_copia/public/admin/notification'
        axios.get('/lorgeliz_tienda_copia/public/admin/notification').then(response => {
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