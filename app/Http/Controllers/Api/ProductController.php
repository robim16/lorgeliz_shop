<?php

namespace App\Http\Controllers\Api;

use App\ColorProducto;
use App\Events\VisitEvent;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function setVisitas(Request $request, $id)
    {
        
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
            
            $producto = ColorProducto::where('id', $id)->first();
        
            $producto->visitas += 1;
    
            $producto->save(); // se incrementa el campo visitas
    
            $response = ['data' => 'success'];
                
            return response()->json($response);
    
            // broadcast(new VisitEvent());


        } catch (\Exception $e) {
            Log::debug('Error actualizando las visitas del producto.Error: '.json_encode($e));
        }

    }
}
