
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
        }
    },
    data (){
        return {

        }
    },
    methods: {
        imprimirFactura(id){
            window.open('/lorgeliz_tienda_copia/public/pedidos/factura/'+ id + ',' + '_blank');
        },
    }

}
</script>

