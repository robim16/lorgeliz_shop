

const product = new Vue({
    el: '#productos',
    data: {
        nombre: '',
        slug: '',
        
        precioanterior:0,
        precioactual:0,
        descuento:0,
        porcentajededescuento:0,
        descuento_mensaje:'0'
    }, 
    computed: {

        generardescuento : function(){

            if (this.porcentajededescuento > 100) {
               
                this.porcentajededescuento = 100;
                
                this.descuento = (this.precioanterior* this.porcentajededescuento) /100;
                this.precioactual = this.precioanterior - this.descuento;
                this.descuento_mensaje = 'Este producto tiene el 100% de descuento, por tanto es gratis.';
                return this.descuento_mensaje;
  
            } else 

            if (this.porcentajededescuento < 0) {
              
                this.porcentajededescuento = 0;
                
                this.descuento = (this.precioanterior* this.porcentajededescuento) /100;
                this.precioactual = this.precioanterior - this.descuento;
                this.descuento_mensaje = '';
                return this.descuento_mensaje;
  
            } else 

                if (this.porcentajededescuento > 0) {
                    
                    this.descuento = (this.precioanterior* this.porcentajededescuento) /100;
                    
                    this.precioactual = this.precioanterior - this.descuento;
    
                    if (this.porcentajededescuento == 100) {
                        this.descuento_mensaje = 'Este producto tiene el 100% de descuento, por tanto es gratis.';
                    }
                    else {
                        this.descuento_mensaje = 'Hay un descuento de $'+ this.descuento;
                    }
    
                    return this.descuento_mensaje;
                }

                else {
                  
                  this.descuento = '';
                  this.precioactual = this.precioanterior
                  
                  this.descuento_mensaje = '';
                 
                  return this.descuento_mensaje;
                }
             
             
        }
    
    },
    methods: {
        eliminarimagen(imagen) { 
                  
            // let url = '/lorgeliz_tienda_copia/public/admin/productos/eliminarimagen/'+imagen;
            let url = `/lorgeliz_tienda_copia/public/api/admin/imagenes/${imagen}/delete`;
            axios.delete(url).then(response => {
                //console.log(response.data);
            });    

        },
        
    },
    mounted(){
        if (data.editar=='Si') {
            this.precioanterior = data.datos.precioanterior;
            this.porcentajededescuento = data.datos.porcentajededescuento;

        }
    }

});