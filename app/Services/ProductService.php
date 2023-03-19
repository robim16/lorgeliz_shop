<?php

namespace App\Services;

use App\ColorProducto;
use App\Producto;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductService
{

    public function createProduct(Request $request)
    {

        $producto = new Producto();

        $producto->nombre = $request->nombre;
        $producto->tipo_id = $request->tipo_id;
        $producto->marca = $request->marca;
        $producto->precio_anterior = $request->precioanterior;
        $producto->precio_actual = $request->precioactual;
        $producto->porcentaje_descuento = $request->porcentajededescuento;
        $producto->descripcion_corta = $request->descripcion_corta;
        $producto->descripcion_larga = $request->descripcion_larga;
        $producto->especificaciones = $request->especificaciones;
        $producto->estado = $request->estado;

        if ($request->sliderprincipal) {
            $producto->slider_principal = 'Si';
        } else {
            $producto->slider_principal = 'No';
        }


        $producto->save();

        return $producto;
    }


    public function uploadImage(Request $request, Producto $producto)
    {

        $url_imagenes = [];

        if ($request->hasFile('imagenes')) {

            $imagenes = $request->file('imagenes');

            foreach ($imagenes as $imagen) {

                $nombre = time() . '_' . $imagen->getClientOriginalName();


                $image = Image::make($imagen)->encode('jpg', 75);
                $image->resize(530, 591, function ($constraint) {
                    $constraint->upsize();
                });

                

                $path = "imagenes/productos/producto_" . $producto->id . "/" . $nombre;

                Storage::disk('public')->put($path, $image->stream());

                $url_imagenes[]['url'] = $path;
            }

            return $url_imagenes;
        }

    }


    public function createColorProducto(Request $request, Producto $producto, $url_imagenes)
    {

        if ($request->activo) {
            $activo = 'Si';    
        }
        else {
            $activo = 'No';    
        }

        $colorproducto = ColorProducto::create([
            'producto_id'=>$producto->id,
            'color_id' => $request->color,
            'activo' => $activo 
        ]);
        

        $color_producto = ColorProducto::where('slug', $colorproducto->slug)
            ->where('color_id', $colorproducto->color_id)
            ->where('producto_id', $colorproducto->producto_id)
            ->first();
        

        $color_producto->imagenes()->createMany($url_imagenes);

    }
    

}
