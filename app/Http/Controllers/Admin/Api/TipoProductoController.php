<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Tipo;
use Illuminate\Http\Request;

class TipoProductoController extends Controller
{
    public function index(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $id  = $request->subcategoria;
        
        $tipos = Tipo::where('subcategoria_id', $id)->get(); 
        
        return $tipos;
    }
}
