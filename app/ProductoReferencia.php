<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log;

class ProductoReferencia extends Pivot
{
    //protected $with = ['colorProducto'];

    protected $fillable = ['color_producto_id', 'talla_id', 'stock'];
    protected $table = 'producto_referencia';

    public $timestamps = false;

   // public function producto () {
   //     return $this->belongsTo(Producto::class);
   // }

   // public function color (){
   //     return $this->belongsTo(Color::class);
    //}

    public function colorProducto (){
        return $this->belongsTo(ColorProducto::class);
    }

    public function talla (){
        return $this->belongsTo(Talla::class);
    }

    public function devoluciones (){
        return $this->hasMany(Devolucione::class, 'producto_referencia_id');
    }

    public function carritos (){
        return $this->belongsToMany(Carrito::class);
    }

    public function ventas (){
        return $this->belongsToMany(Venta::class, 'producto_venta');
    }
    

    public static function obtenerProducto ($producto,$talla){

        // return ProductoReferencia::join('color_producto', 'producto_referencia.color_producto_id', '=', 'color_producto.id')
        // ->join('productos', 'color_producto.producto_id', '=','productos.id')
        // ->where('producto_referencia.color_producto_id', $producto)
        // ->where('producto_referencia.talla_id', $talla)
        // ->select('producto_referencia.id as referencia', 'productos.precio_actual', 'producto_referencia.stock')
        // ->get();

        try {
            
            return ProductoReferencia::with('colorProducto.producto:id,precio_actual')
                ->where('color_producto_id', $producto)
                ->where('talla_id', $talla)
                ->get();

        } catch (\Exception $e) {
            Log::debug('Error obteniendo la referencia en obtenerProducto.Error: '.json_encode($e));
        }

    }

    public static function disponibles(){

        try {
          
            return ProductoReferencia::where('stock', '>' , '0')
                ->groupBy('color_producto_id')
                ->select('color_producto_id')
                ->get();

        } catch (\Exception $e) {
            Log::debug('Error obteniendo la referencias disponibles.Error: '.json_encode($e));
        }
    }
}
