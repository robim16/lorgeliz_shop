<?php

namespace App\Http\Controllers\Admin\Api;

use App\Color;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index(Request $request, $id)
    { 
        //obtener colores al actualizar stock en el modal
        if (!$request->ajax()) return redirect('/');
        
        $colores = Color::join('color_producto', 'colores.id', 'color_producto.color_id')
        ->where('color_producto.producto_id', $id)
        ->get();

        return $colores;
    }
}
