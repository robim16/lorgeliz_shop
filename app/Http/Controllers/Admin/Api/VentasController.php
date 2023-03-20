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
        if (!request()->ajax()) {
            abort(401, 'Acceso denegado');
        }

        $venta = $request->venta;

        $ventas = Venta::when(
            $venta,
            function ($query) use ($venta, $cliente) {
                return $query->where('id', $venta)
                    ->orDoesntHave('envio')
                    ->where('cliente_id', $cliente->id);
            },
            function ($query) use ($cliente) {
                return $query->doesntHave('envio')
                    ->where('cliente_id', $cliente->id);
            }
        )
            ->get();

        return $ventas;
    }
}
