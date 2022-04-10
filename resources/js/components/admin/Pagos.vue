<template>
    <div class="input-group-append">
        <a href="" class="btn btn-success mx-1" @click.prevent="imprimirPagos"><i class="fas fa-print"></i></a>
    </div>
</template>

<script>
export default {
    data (){
        return{

        }
        
    }, 
    methods: {
        imprimirPagos(){
            window.open('/lorgeliz_tienda_copia/public/admin/informes/pdf/pagos');
            
        },
    },
}
</script>
