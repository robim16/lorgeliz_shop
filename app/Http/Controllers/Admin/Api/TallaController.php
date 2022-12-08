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

        try {
        
            $tipo = Producto::where('id', $id)->firstOrFail(); 

            $tallas = Talla::join('talla_tipo', 'tallas.id', 'talla_tipo.talla_id')
            ->where('talla_tipo.tipo_id', $tipo->tipo_id)
            ->get();

            return $tallas;

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function fetchTallasByTipo(Request $request)
    //obtener las tallas de un tipo de producto para mostrar en el select las que ya han sido seleccionadas
    //, en la vista index de tipo de producto
    {   
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
            
            $id  = $request->id;
    
            $tallas = Talla::join('talla_tipo', 'tallas.id', 'talla_tipo.talla_id')
            ->where('talla_tipo.tipo_id', $id)
            ->get();
            
            return $tallas;

        } catch (\Exception $e) {
            return $e;
        }
    }

}
