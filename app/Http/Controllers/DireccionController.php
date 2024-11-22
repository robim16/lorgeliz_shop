<?php

namespace App\Http\Controllers;

use App\DireccionEntrega;
use Illuminate\Http\Request;

class DireccionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function select_direction(Request $request)
    {
       
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}
        
        $direcciones = DireccionEntrega::where('user_id', auth()->user()->id)
            ->update(['activa' => false]);
           
        $direccion_pedido = DireccionEntrega::where('id', $request->direccion)
            ->where('user_id', auth()->user()->id)
            ->first();
       

        $response = ['data' => $direccion_pedido];

        $direccion_pedido->update(['activa' => true]);

        return response()->json($response);
    }
}
