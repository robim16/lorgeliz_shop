<template>
    <main>
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12">

                        <div
                            v-if="arrayProductos.length == 0"
                            class="col-md-2 offset-5"
                        >
                            <a href="#">
                                <img :src="'../img/preloader.gif'" />
                            </a>
                        </div>

                        <div class="card" v-else>
                            <div class="card-header">
                                <h3 class="card-title mb-2">Productos adquiridos con mi pedido</h3>

                                <div class="card-tools">
                                    <form>
                                        <div class="input-group input-group-sm" style="width: 180px;">

                                            <div class="input-group-append">
                                                <a href="" class="btn btn-info btn-sm btn-icon mx-1"
                                                    @click.prevent="imprimir(arrayProductos[0].venta.pedido.id)"
                                                    title="imprimir pedido"><i class="fas fa-print"></i></a>
                                            </div>

                                            <input type="text" name="busqueda" class="form-control float-right"
                                                placeholder="buscar" value="">

                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>


                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Producto</th>
                                            <th scope="col">Imagen</th>
                                            <th scope="col">Talla</th>
                                            <th scope="col">Color</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Precio unitario</th>
                                            <th scope="col">Subtotal</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="producto in arrayProductos" :key="producto.referencia">
                                            <td>
                                                <a :href="'../productos/' + producto.producto_referencia.color_producto.slug">{{ producto.producto_referencia.color_producto.producto.nombre }}</a>
                                            </td>
                                            <td>
                                                <a :href="'../productos/' + producto.producto_referencia.color_producto.slug">
                                                    <img :src="'../storage/' + producto.producto_referencia.color_producto.imagenes[0].url" alt=""
                                                        style="height: 50px; width: 50px;" class="rounded-circle">
                                                </a>
                                            </td>
                                            <td>{{ producto.producto_referencia.talla.nombre }}</td>
                                            <td>{{ producto.producto_referencia.color_producto.color.nombre }}</td>
                                            <td>{{ producto.cantidad }}</td>
                                            <!-- <td>{{ '$'+ producto.producto_referencia.color_producto.producto.precio_actual }}</td> -->
                                            <td>{{ producto.precio_venta | currencyFormat }}</td>
                                            <!-- <td>{{ '$'+ producto.producto_referencia.color_producto.producto.precio_actual * producto.cantidad }}</td> -->
                                            <td>{{ producto.precio_venta * producto.cantidad | currencyFormat }}</td>
                                            <!-- <td>
                                                <a href="" class="btn btn-success" title="solicitar cambio"
                                                    v-if="prodDevolucion[index] === true"
                                                    @click.prevent="store(producto.producto_referencia.id,
                                                    producto.venta.id,
                                                    producto.cantidad
                                                    )">
                                                    <i class="fas fa-recycle"></i>
                                                    
                                                </a>
                                                <span v-else>{{"cambio solicitado"}}</span>
                                            </td> -->

                                            <td>
                                                <a href="" class="btn btn-success btn-sm btn-icon" title="solicitar cambio"
                                                    @click.prevent="store(producto.producto_referencia.id,
                                                    producto.venta.id,
                                                    producto.cantidad
                                                    )" v-if="Object.values(producto.producto_referencia.devoluciones).length == 0">
                                                    <i class="fas fa-recycle"></i>
                                                </a>
                                                <span v-else>{{"cambio solicitado"}}</span>
                                            </td>
                                        
                                        </tr>

                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="text-right">Subtotal:</td>
                                            <td colspan="2" class="text-left">{{subtotal | currencyFormat}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="text-right">Envío:</td>
                                            <td colspan="2" class="text-left">{{envio | currencyFormat}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="text-right">Total pedido:</td>
                                            <td colspan="2" class="text-left">{{total | currencyFormat}}</td>
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </div>
    </main>
  
</template>

<script>
export default {
    props: {
        id: {
            type: Number,
            required: true
        },

        ruta:{
            required: true,
            type: String
        }
    },

    data (){
        return {
            devolucion:'',
            arrayProductos:[],
            // valor:0,
            prodDevolucion:[],
            total:0,
            subtotal:0,
            envio:0,

        }
    },
    
    computed: {
        
    }, 
    methods: {
        
        getProductos(){
            
            // let url = `${this.ruta}/pedidos/productos/${this.id}`

            let url = `${this.ruta}/api/pedidos/productos/${this.id}`

            axios.get(url).then(response => {
                this.arrayProductos = response.data.productos;
               // this.valor = this.arrayProductos[0].venta.valor;

                this.total = this.arrayProductos[0].venta.valor;
                this.subtotal = this.arrayProductos[0].venta.subtotal;
                this.envio = this.arrayProductos[0].venta.envio;

            }); 
        
        },

        
      

        store(producto, venta, cantidad){

           let url = `${this.ruta}/devoluciones`

            axios.post(url, {
                'producto': producto,
                'venta': venta,
                'cantidad': cantidad
            })
            .then( response => {
                   
                let devolucion = response.data.data;
       
                if (devolucion > 0) {
    
                    swal(
                        'Solicitud rechazada!',
                        'Solicitaste el cambio de este producto antes!',
                        'error'
                    )
    
                } else {
    
                    this.getProductos();
                    
    
                    swal(
                        'Producto enviado para cambio!',
                        'Haz solicitado el cambio de este producto!',
                        'success'
                    )
                }

            }).catch(function (error) {
                console.log(error);
            });
           
        }, 

        imprimir(id) {

            window.open(`${this.ruta}/pedidos/show/pdf/${id}, _blank`);
        },

    },

    filters: {
        currencyFormat: function (number) {
            return new Intl.NumberFormat('es-CO', {style: 'currency',currency: 'COP', minimumFractionDigits: 0}).format(number);
        }
    },

    created() {
        this.getProductos();
    }

}
</script>

