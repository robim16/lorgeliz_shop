<?php

namespace App\Services\Admin;

use App\ColorProducto;
use App\Http\Requests\StockRequest;
use App\ProductoReferencia;
use Illuminate\Http\Request;

class StockService
{

    public function saveStock($request)
    {
        
        $colorproducto = ColorProducto::where('color_id', $request->color_id)
            ->where('producto_id', $request->producto_id)
            ->with('producto:id,slider_principal,estado')
            ->first();

        $referencia = ProductoReferencia::where('color_producto_id', $colorproducto->id)
            ->where('talla_id', $request->talla_id)
            ->first(); // buscar la referencia


        if ($referencia == '') { //si no existe la referencia, se crea

            $producto = new ProductoReferencia();
            $producto->color_producto_id = $colorproducto->id;
            $producto->talla_id = $request->talla_id;
            $producto->stock = $request->cantidad;

            $producto->save();

        } else {

            if ($request->operacion == 1) {

                $referencia->stock = $referencia->stock + $request->cantidad; //sino, se actualiza el stock
            } else {
                $referencia->stock = $referencia->stock - $request->cantidad;
            }

            $referencia->save();
        }

        return $colorproducto;
    }
}
