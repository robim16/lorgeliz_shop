const order = new Vue({

  el: '#pedidos',
  data: {
    devolucion: ''
  },

  methods: {
    imprimir(id) {
      window.open('/lorgeliz_tienda_copia/public/pedidos/show/pdf/' + id + ',' + '_blank');
    },

    store(producto, venta, cantidad) {
      let url = '/lorgeliz_tienda_copia/public/devoluciones';

      axios.post(url, {
        'producto': producto,
        'venta': venta,
        'cantidad': cantidad
      }).then(function (response) {
        let devolucion = response.data.data;

        if (devolucion > 0) {

          swal(
            'Solicitud rechazada!',
            'Solicitaste el cambio de este producto antes!',
            'error'
          )

        } else {
          swal(
            'Producto enviado para cambio!',
            'Haz solicitado el cambio de este producto!',
            'success'
          )
        }

      }).catch(function (error) {
        console.log(error);
      });

    }

  },

  mounted() {

  }

});