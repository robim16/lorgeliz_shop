<template>
    <a class="m-2 float-right btn btn-warning" href="" @click.prevent="imprimirPagosMes">
        <i class="fa fa-print"></i>
    </a>
</template>

<script>
export default {
    data (){
        return{
            mes: ''
        }
    },
    methods: {
        imprimirPagosMes(){
            window.open('/lorgeliz_tienda_copia/public/admin/informes/pdf/pagos/mes?mes='+this.mes);
        },
    },
    mounted(){
        this.mes = data.datos.mes;
    }
}
</script>

