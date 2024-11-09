<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Producto;
use App\Talla;
use Illuminate\Http\Request;

class TallaController extends Controller
{
    
    public function index(Request $request, $id)
    {
        //obtener tallas al actualizar stock en el modal

        $tipo_producto = Producto::where('id', $id)->firstOrFail();

        $tallas = Talla::join('talla_tipo', 'tallas.id', 'talla_tipo.talla_id')
            ->where('talla_tipo.tipo_id', $tipo_producto->tipo_id)
            ->get();

        return $tallas;
    }
    

    //obtener las tallas de un tipo de producto para mostrar en el select las que ya han sido seleccionadas
    //, en la vista index de tipo de producto
    public function fetchTallasByTipo(Request $request)
    {   
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $id  = $request->id;

        $tallas = Talla::join('talla_tipo', 'tallas.id', 'talla_tipo.talla_id')
        ->where('talla_tipo.tipo_id', $id)
        ->get();
        
        return $tallas;
    }

}
