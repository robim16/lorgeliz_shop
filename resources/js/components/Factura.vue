
<template>
    <main>
        <a href="" class="btn btn-success" title="descargar factura" @click.prevent="imprimirFactura(id)">
            <i class="fas fa-download"></i>
        </a>
    </main>
  
</template>

<script>
export default {
    // props: ['id'],
    props: {
        id:{
            required:true,
            type:Number
        },

        ruta:{
            required: true,
            type: String
        }
    },
    data (){
        return {

        }
    },
    methods: {
        imprimirFactura(id){
            let url = '/lorgeliz_tienda_copia/public/pedidos/factura/'+ id + ',' + '_blank';

            window.open(`${this.ruta}/pedidos/factura/${id},_blank`);
        },
    }

}
</script>

