

const inventarios = new Vue({
    el: '#inventarios',
    data: {
        active: '',
        producto: '',
        talla: 0,
        color: 0,
        cantidad: 1,
        arrayTallas: [],
        arrayColores: [],
        operacion: '',
        alertShow: false,
        arrayProductos: [],
        pagination: {
            'total': 0,
            'current_page': 0,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0,
        },
        offset: 3,
    }, 
    
    computed:{

        isActived: function(){
            return this.pagination.current_page;
        },
        
        //Calcula los elementos de la paginación
        pagesNumber: function() {
            if(!this.pagination.to) {
                return [];
            }
            
            var from = this.pagination.current_page - this.offset; 
            if(from < 1) {
                from = 1;
            }

            var to = from + (this.offset * 2); 
            if(to >= this.pagination.last_page){
                to = this.pagination.last_page;
            }  

            var pagesArray = [];
            while(from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
    }, 

    methods: {

        traerInventario(page){

            let url = 'http://dev.lorenzogeliztienda.com/admin/stock/productos?page=' + page;

            axios.get(url).then(response => {
                var respuesta = response.data;
                this.arrayProductos = respuesta.productos.data;
                this.pagination = respuesta.pagination;
                this.active = 0;
            }); 

        },

        pdfInventarios(){
            let url = 'http://dev.lorenzogeliztienda.com/admin/stock/listado'
            window.open(url);
        },
        
        selectProducto(data =[], param){
            //recibe una instancia de producto
            // this.producto =  data['id'];//se accede a las propiedades como un array

            this.alertShow = false;

            this.producto =  data['color_producto']['producto_id'];
            
            this.operacion = param;

            this.getTallas();
            this.getColores();

            this.talla = data['talla_id'];
            
            this.color = data['color_producto']['color_id'];
        },

        getTallas(){

            this.talla = 0;

            let url = 'http://dev.lorenzogeliztienda.com/api/admin/tallas/' +this.producto;
    
            axios.get(url).then(response => {
                this.arrayTallas = response.data;
            }); 
    
        }, 

        getColores(){
            this.color = 0;
            
            let url = 'http://dev.lorenzogeliztienda.com/api/admin/colores/'+this.producto;

    
            axios.get(url).then(response => {
                this.arrayColores = response.data;
            }); 
        },

        ingresarStock(){
            let url = 'http://dev.lorenzogeliztienda.com/admin/stock';
            

            axios.post(url,{
                'producto_id': this.producto,
                'talla_id': this.talla,
                'color_id': this.color,
                'cantidad': this.cantidad,
                'operacion': this.operacion
                }).then(response => {

                    if (response.data.data == 'success') {
                        this.traerInventario(1)
                        this.alertShow = true
                    }

                }).catch(error => {
                    console.log(error.response.data)
                    for (var [el, message] of Object.entries(error.response.data)) {
                        // $(`#${el}-error`).html(message)
                        document.getElementById(`${el}-error`).innerHTML = message;
                        
                    }
                });

        },

        reset(){
            this.producto = '';
            this.talla = '';
            this.color = '';
            this.operacion = 3;
        },

        cambiarPagina(page){
            //Actualiza la página actual
            
            this.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página

            this.traerInventario(page);
            
        }
    },

    mounted(){
        this.traerInventario(1)
    }

});