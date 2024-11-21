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
       
        $direcciones = DireccionEntrega::where('user_id', auth()->user()->id)
            ->update(['activa' => false]);
           
        $direccion_pedido = DireccionEntrega::where('id', $request->direccion)
            ->where('user_id', auth()->user()->id)
            ->update(['activa' => true]);
       

        $response = "se ha actualizado la dirección de entrega del pedido";
        
        return response()->json($response);
    }
}
