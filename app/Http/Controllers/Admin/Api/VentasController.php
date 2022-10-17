<?php

namespace App\Http\Controllers\Admin\Api;

use App\Cliente;
use App\Http\Controllers\Controller;
use App\Venta;
use Illuminate\Http\Request;

class VentasController extends Controller
{
    public function obtener(Request $request, Cliente $cliente)
    {
        // if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
       
            $ventas = Venta::doesntHave('envio')
                ->where('cliente_id', $cliente->id)
                ->get(); 
            
            return $ventas;
            
        } catch (\Exception $e) {
            //throw $th;
        }

    }
}