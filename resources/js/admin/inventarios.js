

const inventarios = new Vue({
    el: '#inventarios',
    data: {
       producto: '',
       talla: 0,
       color: 0,
       cantidad: '',
       arrayTallas: [],
       arrayColores: [],
       operacion: ''
    }, 
    
    methods: {
        
        pdfInventarios(){
            // let url = '/lorgeliz_tienda_copia/public/admin/stock/listado';
            let url = 'http://lorenzogeliztienda.com/admin/stock/listado'
            window.open(url);
        },
        
        selectProducto(data =[], param){
            //recibe una instancia de producto
            // this.producto =  data['id'];//se accede a las propiedades como un array

            this.producto =  data['color_producto']['producto_id'];
            
            this.operacion = param;

            this.getTallas();
            this.getColores();

            this.talla = data['talla_id'];
            // this.color = data['color_id'];
            this.color = data['color_producto']['color_id'];
        },

        getTallas(){
            this.talla = 0;
            // let url = '/lorgeliz_tienda_copia/public/admin/tallas/'+this.producto;

            // let url = '/lorgeliz_tienda_copia/public/api/admin/tallas/'+this.producto;
            let url = 'http://lorenzogeliztienda.com/api/admin/tallas/' +this.producto;
    
            axios.get(url).then(response => {
            //   this.arrayTallas = response.data.tallas;
                this.arrayTallas = response.data;
            }); 
    
        }, 

        getColores(){
            this.color = 0;
            // let url = '/lorgeliz_tienda_copia/public/admin/colores/get/'+this.producto;

            // let url = '/lorgeliz_tienda_copia/public/api/admin/colores/'+this.producto;

            let url = 'http://lorenzogeliztienda.com/api/admin/colores/'+this.producto;

    
            axios.get(url).then(response => {
            //   this.arrayColores = response.data.colores;
                this.arrayColores = response.data;
            }); 
        },

        ingresarStock(){

            let url = 'http://lorenzogeliztienda.com/admin/stock';

            axios.post(url,{
                'producto': this.producto,
                'talla': this.talla,
                'color': this.color,
                'cantidad': this.cantidad
                }).then(response => {
                //    console.log(response)

                }).catch(error => {
                    // console.log(error.response.data)
                    for (var [el, message] of Object.entries(error.response.data)) {
                        document.getElementById(`${el}-error`).innerHTML = message;
                    }
                });

        },


        reset(){
            this.producto = '';
            this.talla = '';
            this.color = '';
            this.operacion = 3;
        }
    },

});