<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\SalesEvent;

use OwenIt\Auditing\Contracts\Auditable;

class Venta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    // protected $fillable = ['fecha', 'factura', 'valor', 'cliente_id'];
    protected $fillable = ['fecha', 'factura', 'subtotal', 'envio', 'valor', 'cliente_id'];
    
    public function pedido()
    {
        return $this->hasOne(Pedido::class);
    }

    public function devoluciones()
    {
        return $this->hasMany(Devolucione::class);
    }

    //public function productos (){
        //return $this->belongsToMany(Producto::class);
    //}

    public function productoReferencias()
    {
        return $this->belongsToMany(ProductoReferencia::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function productoVentas()
    {
        return $this->hasMany(ProductoVenta::class, 'venta_id');
    }


    public function envio()
    {
        return $this->hasOne(Envio::class, 'venta_id');
    }


    public static function boot() {
        parent::boot();
            
        static::created(function(Venta $venta) {

            $cart = Carrito::estado()
            ->cliente(auth()->user()->cliente->id)
            ->firstOrFail();
            // where('cliente_id', auth()->user()->cliente->id)
            // ->where('estado', 1)

            $cart->estado = '0';
            $cart->save();

            $carritos = CarritoProducto::where('carrito_id', $cart->id)
            ->get();
        
            foreach ($carritos as $carrito) {
                
                $productoVenta = new ProductoVenta();

                $productoVenta->producto_referencia_id = $carrito->producto_referencia_id;
                $productoVenta->venta_id = $venta->id;
                $productoVenta->cantidad = $carrito->cantidad;
                $productoVenta->precio_venta =  $carrito->productoReferencia->colorProducto->producto->precio_actual;
                $productoVenta->porcentaje_descuento = $carrito->productoReferencia->colorProducto->producto->porcentaje_descuento;

                $productoVenta->save();
            }


            $pedido = new Pedido();
            $pedido->fecha = \Carbon\Carbon::now();
            $pedido->direccion_entrega = Auth()->user()->direccion;
            $pedido->venta_id = $venta->id;
            $pedido->save();

            //$cliente = auth()->user()->cliente->id;

            //$details = [
                //'title' => 'Hemos recibido tu pedido',
                //'cliente' => $cliente,
                //'url' => url('/pedidos/'. $venta->id),
            //];
            
            //Mail::to(Auth()->user()->email)->send(new ClienteMessageMail($details));


            $productos = array();
            // $products['data'] = array();


            $productos = $carritos->map(function ($carrito){
                return $carrito->producto_referencia_id;
            });

            $vendidos = ProductoReferencia::whereIn('id', $productos)
            ->groupBy('color_producto_id')
            ->select('color_producto_id')
            ->get();

            // broadcast(new SalesEvent($vendidos));
            
        });

    }

    public function scopeEstado($query)
    {
        return $query->where('estado', '!=', '3');
    }

}
