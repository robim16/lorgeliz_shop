<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Subcategoria;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
            
            $id  = $request->categoria;
            $subcategorias = Subcategoria::where('categoria_id', $id)->get(); 
            
            return $subcategorias; //obtener subcategorias al crear un producto en admin

        } catch (\Exception $e) {
            return $e;
        }
    }
}
