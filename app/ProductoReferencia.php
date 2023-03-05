<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

class ProductoReferencia extends Pivot implements Auditable
{
    //protected $with = ['colorProducto'];

    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['color_producto_id', 'talla_id', 'stock'];
    protected $table = 'producto_referencia';

    public $timestamps = false;

   // public function producto () {
   //     return $this->belongsTo(Producto::class);
   // }

   // public function color (){
   //     return $this->belongsTo(Color::class);
    //}

    public function colorProducto()
    {
        return $this->belongsTo(ColorProducto::class);
    }

    public function talla()
    {
        return $this->belongsTo(Talla::class);
    }

    public function devoluciones()
    {
        return $this->hasMany(Devolucione::class, 'producto_referencia_id');
    }

    public function carritos()
    {
        return $this->belongsToMany(Carrito::class);
    }

    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'producto_venta');
    }


    public static function obtenerProducto ($producto,$talla)
    {

        try {
            

            return ProductoReferencia::with('colorProducto.producto:id,precio_actual')
                ->where('color_producto_id', $producto)
                ->where('talla_id', $talla)
                ->get();

        } catch (\Exception $e) {

            Log::debug('Error obteniendo la referencia en obtenerProducto.
                Error: '.json_encode($e));
        }

    }



    public static function disponibles()
    {
        try {

            return ProductoReferencia::where('stock', '>' , '0')
                ->select('color_producto_id')
                ->groupBy('color_producto_id')
                ->get();
            
        } catch (\Exception $e) {

            Log::debug('Error obteniendo la referencias disponibles.
                Error: '.json_encode($e));
        }
        
    }
}
