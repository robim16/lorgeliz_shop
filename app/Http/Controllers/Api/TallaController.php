<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Talla;
use Illuminate\Http\Request;

class TallaController extends Controller
{
    public function index(Request $request, $id)
    {
        //obtener tallas en la vista productos de la tienda

        if (!$request->ajax()) return redirect('/');

        $tallas = Talla::join('producto_referencia', 'tallas.id', '=', 'producto_referencia.talla_id')
        ->join('color_producto', 'producto_referencia.color_producto_id', '=', 'color_producto.id')
        ->where('producto_referencia.color_producto_id', $id)
        ->where('producto_referencia.stock', '>', '0')
        ->select('tallas.*', 'producto_referencia.stock')
        ->get();

        return $tallas; 
    }
}
