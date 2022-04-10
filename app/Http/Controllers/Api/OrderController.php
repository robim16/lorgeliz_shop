<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ProductoVenta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show(Request $request, $id) {

        $productos = ProductoVenta::whereHas('venta.pedido',
        function (Builder $query) use ($id) {
           $query->where('id', $id);
        })
        ->whereHas('venta',
        function (Builder $query) use ($id) {
           $query->where('cliente_id', auth()->user()->cliente->id);
        })
        ->with(['productoReferencia.colorProducto.color', 'productoReferencia.colorProducto.producto',
        'productoReferencia.talla','venta.pedido', 'productoReferencia.colorProducto.imagenes'])
        ->get();

        return $productos;

    }
}
