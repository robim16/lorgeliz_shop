<?php

namespace App\Services\Admin;

use App\ColorProducto;
use App\Imagene;
use App\Jobs\UploadProductsImages;
use App\Producto;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductService
{

    public function saveProduct(Request $request, Producto $producto)
    {

        // $producto = new Producto();

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



    public function createColorProducto(Request $request, Producto $producto, $url_imagenes, $activo)
    {

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



    public function validarColorProducto(Request $request)
    {

        $producto_id = $request->producto;

        $color_producto = ColorProducto::where('color_id', $request->color)
            ->where('producto_id', $producto_id)
            ->first();

        if ($color_producto) {

            session()->flash('message', ['success', ("Este producto ya ha sido creado anteriormente")]);
            
            return redirect()->back();
        }

    }



    // public function updateProduct(Request $request, Producto $producto)
    // {

    //     $producto->nombre = $request->nombre;
    //     $producto->tipo_id = $request->tipo_id;
    //     $producto->marca = $request->marca;
    //     $producto->precio_anterior = $request->precioanterior;
    //     $producto->precio_actual = $request->precioactual;
    //     $producto->porcentaje_descuento = $request->porcentajededescuento;
    //     $producto->descripcion_corta = $request->descripcion_corta;
    //     $producto->descripcion_larga = $request->descripcion_larga;
    //     $producto->especificaciones = $request->especificaciones;
    //     $producto->estado = $request->estado;

    //     if ($request->sliderprincipal) {
    //         $producto->slider_principal= 'Si';    
    //     }
    //     else {
    //         $producto->slider_principal= 'No';    
    //     }
        

    //     $producto->save();


    //     return $producto;

    // }



    public function updateColorProducto(Request $request, $slug)
    {
        
        $producto = ColorProducto::where('slug',$slug)->firstOrFail();


        if ($request->activo) {
            $producto->activo = 'Si';    
        }
        else {
            $producto->activo = 'No';    // se edita el campo activo del color
        }

        $producto->color_id = $request->color;

        $producto->save();

        return $producto;


    }



    public function eliminarProducto($numventas, $color, $productos, $id)
    {
        if ($numventas == 0) {

            if ($productos == 1) {
                Producto::where('id', $color->producto_id)->delete(); //si no tiene ventas y hay un sólo color, se elimina
            }

            $color->delete(); // se elimina el color

            $imagenes = Imagene::where('imageable_id', $id)->get();

            foreach ($imagenes as $imagen) {
                $imagen->delete(); // se eliminan las imágenes de la bd
            }

        } else {

            $color->activo = 'No'; //si tiene ventas, se desactiva

            $color->save();
        }

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

        // UploadProductsImages::dispatch($producto,$request->all());
        
    }



    public function deleteImage(Request $request, $id)
    {

        $image = Imagene::find($id);

        $eliminar = Storage::disk('public')->delete($image->url); // se elimina del directorio

        $image->delete(); // se elimina de la bd

        return "eliminado id:" . $id . ' ' . $eliminar;

    }
    

}
