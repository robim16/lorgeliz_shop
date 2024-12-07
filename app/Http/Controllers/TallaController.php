<?php

namespace App\Http\Controllers;

use App\Talla;
use Illuminate\Http\Request;

class TallaController extends Controller
{
    public function getProductoTallas(Request $request, $id)
    {
        //en desuso, se utiliza tallaController del directorio api
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $tallas = Talla::join('producto_referencia', 'tallas.id', '=',
            'producto_referencia.talla_id'
        )
            ->join('color_producto', 'producto_referencia.color_producto_id', '=', 'color_producto.id')
            ->where('producto_referencia.color_producto_id', $id)
            ->where('producto_referencia.stock', '>', '0')
            ->select('tallas.*', 'producto_referencia.stock')
            ->get();

        return ['tallas' => $tallas]; //obtener tallas en la vista productos de la tienda
    }
}
