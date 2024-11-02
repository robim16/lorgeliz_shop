

const inventarios = new Vue({
    el: '#product_color',
    data: {
        producto: '',
        color: 0,
        alertShow: false,
        imagenes: ''
    },
    
    methods: {

        selectFiles(event){
            this.imagenes = event.target.files;
        },
        
        create_color(producto){

            let url = '/api/admin/products/color';
            this.producto = producto;

            const form = new FormData();
            form.append('producto', this.producto);
            form.append('color', this.color);
            

            for( var i = 0; i < this.imagenes.length; i++ ){
                let file = this.imagenes[i];
              
                form.append('imagenes[' + i + ']', file);
            }

           
            axios.post(url, form, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            }).then(response => {

                if (response.data.data == 'success') {
                    this.alertShow = true
                }

            }).catch(error => {
                console.log(error)

                for (var [el, message] of Object.entries(error.response.data)) {
                    // $(`#${el}-error`).html(message)
                    document.getElementById(`${el}-error`).innerHTML = message;

                }
            });

        },

        reset(){
            this.color = '';
            this.imagenes = '';
        },


    },

    mounted(){
       
    }

});