

const inventarios = new Vue({
    el: '#inventarios',
    data: {
       producto: '',
       talla: 0,
       color: 0,
       cantidad: '',
       arrayTallas: [],
       arrayColores: []
    }, 
    
    methods: {
        pdfInventarios(){
            window.open('/lorgeliz_tienda_copia/public/admin/stock/listado');
        },
        
        selectProducto(data =[]){//recibe una instancia de producto
            // this.producto =  data['id'];//se accede a las propiedades como un array

            this.producto =  data['color_producto']['producto_id'];

            this.getTallas();
            this.getColores();

            this.talla = data['talla_id'];
            // this.color = data['color_id'];
            this.color = data['color_producto']['color_id'];
        },

        getTallas(){
            this.talla = 0;
            // let url = '/lorgeliz_tienda_copia/public/admin/tallas/'+this.producto;

            let url = '/lorgeliz_tienda_copia/public/api/admin/tallas/'+this.producto;
    
            axios.get(url).then(response => {
            //   this.arrayTallas = response.data.tallas;
                this.arrayTallas = response.data;
            }); 
    
        }, 

        getColores(){
            this.color = 0;
            // let url = '/lorgeliz_tienda_copia/public/admin/colores/get/'+this.producto;

            let url = '/lorgeliz_tienda_copia/public/api/admin/colores/'+this.producto;
    
            axios.get(url).then(response => {
            //   this.arrayColores = response.data.colores;
                this.arrayColores = response.data;
            }); 
        },

        reset(){
            this.producto = '';
            this.talla = '';
            this.color = '';
        }
    },

});