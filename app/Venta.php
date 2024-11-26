<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\SalesEvent;
use App\Jobs\SendClienteSalesMail;
use App\Mail\ClienteMessageMail;
use Illuminate\Support\Facades\Mail;

class Venta extends Model
{
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

            try {

                
                $cart = Carrito::estado()
                    ->cliente(auth()->user()->cliente->id)
                    ->firstOrFail();
               
    
                $cart->estado = '0';
                $cart->save();
    
                $carritos = CarritoProducto::where('carrito_id', $cart->id)
                    ->get();


                $data = array();

                foreach ($carritos as $carrito) {
                    $item = array(
                        'producto_referencia_id' => $carrito->producto_referencia_id,
                        'venta_id' => $venta->id,
                        'cantidad' => $carrito->cantidad,
                        'precio_venta' => $carrito->productoReferencia->colorProducto->producto->precio_actual,
                        'porcentaje_descuento' => $carrito->productoReferencia->colorProducto->producto->porcentaje_descuento
                    );

                    array_push($data, $item);
                }


                ProductoVenta::insert($data);

    
                $cliente = auth()->user();


                $direccion_pedido = DireccionEntrega::where('activa', true)
                    ->where('user_id', $cliente->id)
                    ->first();
                
    
                $pedido = new Pedido();
                $pedido->fecha = \Carbon\Carbon::now();
                // $pedido->direccion_entrega = $cliente->direccionEntregas->where('activa', true)->first(['direccion']);
                $pedido->direccion_entrega = $direccion_pedido->direccion;
                $pedido->venta_id = $venta->id;
                $pedido->save();
    
    
                $details = [
                    'title' => 'Hemos recibido tu pedido',
                    'cliente' => $cliente->nombres.' '.$cliente->apellidos,
                    'url' => url('/pedidos/'. $venta->id),
                ];


                
                // Mail::to($cliente->email)->send(new ClienteMessageMail($details));

                
                SendClienteSalesMail::dispatch($details, $cliente);
    
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

            } catch (\Exception $e) {
                throw $e;
                return $e;
            }
            
        });

    }

    public function scopeEstado($query){
        return $query->where('estado', '!=', '3');
    }

}
