

const category = new Vue({
    el: '#categoria',
    data: {
        active: '',
        arrayProductos: [],
        criterio: '',
        genero: '',
        pagination : {
            'total' : 0,
            'current_page' : 0,
            'per_page' : 0,
            'last_page' : 0,
            'from' : 0,
            'to' : 0,
        },
        offset : 3,
        listar: 0,
        value: 0,
        tipo: '',
        estado: 0,
        categoria: '',
        subcategoria: ''
    },
    
    methods: {
        getproductos(page){

            this.listar = 7;
            let url = '/lorgeliz_tienda/public/categorias/productos?page=' + page;

            axios.get(url).then(response => {
                var respuesta = response.data;
                this.arrayProductos = respuesta.productos.data;
                this.pagination = respuesta.pagination;
                this.active = 0;
            }); 

        },

        getProductByState(page,estado){
            
            this.listar = 6; 
            this.estado = estado;

            let url = '/lorgeliz_tienda/public/categorias/productos/estado?page=' + page + '&estado='  + this.estado;
            axios.get(url).then(response => {
                var respuesta = response.data;
                this.arrayProductos = respuesta.productos.data;
                this.pagination = respuesta.pagination;
            }); 

        },

        saleProductos(page){
            
            this.listar = 5;

            let url = '/lorgeliz_tienda/public/categorias/productos/vendidos?page=' + page;

            axios.get(url).then(response => {
                var respuesta = response.data;
                this.arrayProductos = respuesta.productos.data;
                this.pagination = respuesta.pagination;
            }); 

        },

        hotProducts(page){

            this.listar = 4;

            let url = '/lorgeliz_tienda/public/categorias/productos/vistos?page=' + page;

            axios.get(url).then(response => {
                var respuesta = response.data;
                this.arrayProductos = respuesta.productos.data;
                this.pagination = respuesta.pagination;
            }); 
        },

        getProductsByOrder(page,orden){

            this.listar = 3;
            this.orden = orden;

            if (orden==1) {
                this.criterio='precio_actual';
            }
            else{
                this.criterio='nombre'; 
            }

            let url = '/lorgeliz_tienda/public/categorias/productos/orden?page=' + page + '&criterio=' + this.criterio ;

            axios.get(url).then(response => {
                var respuesta = response.data;
                this.arrayProductos = respuesta.productos.data;
                this.pagination = respuesta.pagination;
            }); 
        },

        getProductByTipo(page,tipo){
           
            this.listar = 2;
            this.tipo = tipo;

            let url = '/lorgeliz_tienda/public/categorias/productos/tipo?page=' + page + '&tipo=' + this.tipo;

            axios.get(url).then(response => {
                var respuesta = response.data;
                this.arrayProductos = respuesta.productos.data;
                this.pagination = respuesta.pagination;
            }); 
        },

        getProductByGenre(page,value){
           
           this.listar = 1;
           this.value = value;

           if (this.value==1) {
               this.genero = 'hombres';
           }
           
           if (this.value==2) {
                this.genero = 'mujeres';
           }

           if (this.value==3) {
                this.genero = 'niños';
            }
            
           let url = '/lorgeliz_tienda/public/categorias/productos/genero?page=' + page + '&genero=' + this.genero;
           
            axios.get(url).then(response => {
                var respuesta = response.data;
                this.arrayProductos = respuesta.productos.data;
                this.pagination = respuesta.pagination;
            }); 
        },

        cambiarPagina(page){
            //Actualiza la página actual
            
            this.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página

            if (this.listar == 1) {
                this.getProductByGenre(page, this.value);
            }

            if (this.listar == 2) {
                this.getProductByTipo(page, this.tipo);
            }

            if (this.listar == 3) {
                this.getProductsByOrder(page, this.orden);
            }

            if (this.listar == 4) {
                this.hotProducts(page);
            }
            
            if (this.listar == 5) {
                this.saleProductos(page);
            }

            if (this.listar == 6) {
                this.getProductByState(page, this.estado);
            }
           
            if (this.listar == 7) {
                this.getproductos(page);
            }
        },
    },

    mounted(){
        //this.getproductos();
        //var categoria = this.getQueryParam('ref');

        if (data.categoria !='') {
            this.categoria = data.categoria;
        }

        if (data.subcategoria !='') {
            this.subcategoria = data.subcategoria;
        }

        if (this.categoria == "hombres" && this.subcategoria == '') {
            this.getProductByGenre(1,1);
        } 
        
        if (this.categoria == "mujeres" && this.subcategoria == '') {
            this.getProductByGenre(1,2);
        } 

        if (this.categoria == "niños" && this.subcategoria == '') {
            this.getProductByGenre(1,3);
        } 

        if (this.categoria == "nuevos" && this.subcategoria == '') {
            this.getProductByState(1,1);
        } 

        if (this.categoria == "ofertas" && this.subcategoria == '') {
            this.getProductByState(1,2);
        } 
        
        if (this.subcategoria != '') {
            this.getProductByTipo(1,this.subcategoria)
        }
    }

});