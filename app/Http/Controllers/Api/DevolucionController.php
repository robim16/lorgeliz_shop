<?php

namespace App\Http\Controllers\Api;

Use App\Devolucione;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DevolucionController extends Controller
{   //cae en desuso al cargar las devoluciones en order show
    public function verify(Request $request){

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}
        
        return Devolucione::where('venta_id',$request->venta)
        ->where('producto_referencia_id',$request->producto)
        ->first();
    }
}
