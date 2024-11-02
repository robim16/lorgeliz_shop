<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Producto;
use App\Services\Admin\ProductService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    public function store_color(Request $request, ProductService $productService)
    {

        try {

            // return $request;
            DB::beginTransaction();

            $producto_id = $request->producto;

            $productService->validarColorProducto($request);

            $producto = Producto::where('id', $producto_id)->first();

            $url_imagenes = $productService->uploadImage($request, $producto);

            $activo = 'Si';

            $productService->createColorProducto($request, $producto, $url_imagenes, $activo);

            DB::commit();


            $response = ['data' => 'success'];
            
            return response()->json($response);

        } catch (Exception $e) {

            Log::debug('Error creando el color_producto.Error: ' . json_encode($e));

            DB::rollBack();

            return $e->getMessage();
        }
    }

}
