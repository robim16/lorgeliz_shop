<?php

namespace App\Http\Controllers\Admin;

use App\Color;
use App\ColorProducto;
use App\Imagene;
use App\Producto;
use App\ProductoReferencia;
use App\ProductoVenta;
use App\Events\ProductStatusEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
// use Spatie\Dropbox\Client;
// use Symfony\Component\Console\Input\Input;
use Log;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth')->except('setVisitas');
        $this->middleware('auth');
    }



    public function index(Request $request)
    {
        $busqueda = $request->get('busqueda');

        try {

            $productos = Producto::orWhere('productos.nombre', 'like', "%$busqueda%")
                ->orWhere('productos.id', 'like', "%$busqueda%")
                ->with('colors')
                ->withCount('colors')
                ->paginate(10);


            foreach ($productos as $producto) {
                $producto->colors[0]->pivot->load(['imagenes' => function($query) {
                    $query->select('id', 'url', 'imageable_id')
                    ->limit(1);
                }]);
            }

            return view('admin.productos.index', compact('productos')); //index de productos en admin

        } catch (\Exception $e) {
            Log::debug('Error en index de productos.Error: ' . json_encode($e));

        }
    } 


    public function product(Request $request, $id)
    {

        $busqueda = $request->get('busqueda');

        //obtener todos los colores de un producto por id

        try {

            $productos = ColorProducto::whereHas(
                'producto',
                function (Builder $query) use ($id, $busqueda) {
                    $query->where('id', $id)
                        ->where('nombre', 'like', "%$busqueda%")
                        ->orderBy('created_at');
                }
            )
                ->with(['color', 'producto', 'imagenes'])
                ->paginate(5);

            return view('admin.productos.coloresproducto', compact('productos'));
        } catch (\Exception $e) {

            Log::debug('Error al obtener los colores del producto. Error: ' . json_encode($e));
        }
    }



    public function create()
    {
        $estados = $this->estado_productos();

        return view('admin.productos.create', compact('estados')); //vista para crear un producto
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request, ProductService $productService)
    {
        try {


            DB::beginTransaction();


            $producto = $productService->createProduct($request);

            
            $url_imagenes = $productService->uploadImage($request, $producto);

            $color_producto = $productService->createColorProducto($request, $producto, $url_imagenes);


            DB::commit();


            session()->flash('message', ['success', ("Se ha creado el producto exitosamente")]);

            return redirect()->route('product.index');

        } catch (Exception $e) {

            session()->flash('message', ['warning', ("ha ocurrido un error")]);

            Log::debug('Error creando el producto.Error: ' . json_encode($e));

            return redirect()->back()
                ->withInput($request->input());

            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try {

            $producto = Producto::join('color_producto', 'productos.id', 'color_producto.producto_id')
                ->select('productos.*', 'color_producto.id as cop', 'color_producto.activo')
                ->where('productos.id', $id)
                ->with('colors')
                ->firstOrFail();

            $estados = $this->estado_productos();

            return view('admin.productos.show', compact('producto', 'estados')); //mostrar el producto

        } catch (\Exception $e) {

            Log::debug('Error al mostrar el producto. Error: ' . json_encode($e));
        }
    }



    public function showColor($slug)
    {

        try {

            $producto = Producto::join('color_producto', 'productos.id', 'color_producto.producto_id')
                ->select(
                    'productos.*',
                    'color_producto.id as cop',
                    'color_producto.activo',
                    'color_producto.color_id as color',
                    'color_producto.slug'
                )
                ->where('color_producto.slug', $slug)
                ->firstOrFail();

            $estados = $this->estado_productos();

            return view('admin.productos.showcolor', compact('producto', 'estados')); //mostrar un color de un producto

        } catch (\Exception $e) {

            Log::debug('Error show de productos. Error: ' . json_encode($e));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $producto = Producto::join('color_producto', 'productos.id', 'color_producto.producto_id')
                ->select('productos.*', 'color_producto.id as cop', 'color_producto.activo')
                ->where('productos.id', $id)
                ->firstOrFail();

            $estados = $this->estado_productos();

            return view('admin.productos.edit', compact('producto', 'estados')); // editar el producto

        } catch (\Exception $e) {

            Log::debug('Error en función editar productos. Error: ' . json_encode($e));
        }
    }



    public function editColor($slug)
    {
        try {

            $producto = Producto::join('color_producto', 'productos.id', 'color_producto.producto_id')
                ->select(
                    'productos.*',
                    'color_producto.id as cop',
                    'color_producto.activo',
                    'color_producto.color_id as color',
                    'color_producto.slug'
                )
                ->where('color_producto.slug', $slug)
                ->firstOrFail();

            $estados = $this->estado_productos();

            return view('admin.productos.editcolor', compact('producto', 'estados'));
        } catch (\Exception $e) {

            Log::debug('Error en editColor de productos. Error: ' . json_encode($e));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, Producto $producto)
    public function update(ProductRequest $request, $id)
    {

        try {

            DB::beginTransaction();

            $producto = Producto::where('id', $id)->firstOrFail();

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

            DB::commit();

            /*$url_imagenes = [];
    
            if ($request->hasFile('imagenes')) {
    
                $imagenes = $request->file('imagenes');
    
                foreach ($imagenes as $imagen) {
    
                    $nombre = time().'_'.$imagen->getClientOriginalName();
    
                    $path = Storage::disk('public')->putFileAs("imagenes/productos/producto" . $producto->id, $imagen, $nombre);
    
                    $url_imagenes[]['url'] = $path;
    
    
                }
    
            }
    
            $colorproducto = ColorProducto::where('producto_id', $producto->id)
            ->where('color_id', $request->color)
            ->firstOrFail();
    
            if ($request->activo) {
                $colorproducto->activo= 'Si';    
            }
            else {
                $colorproducto->activo= 'No';    
            }
    
            $colorproducto->save();
    
            $colorproducto->imagenes()->createMany($url_imagenes);*/

            $colores = ColorProducto::whereIn('id', ProductoReferencia::disponibles())
                ->with('producto:id,slider_principal,estado')
                ->where('producto_id', $producto->id)
                ->get();


            $data = array();
            $data['producto'] = array();

            $data['producto'] = $colores;

            // broadcast(new ProductStatusEvent($data))->toOthers();


            session()->flash('message', ['success', ("Se ha actualizado el producto exitosamente")]);

            return redirect()->route('product.index');

        } catch (Exception $e) {

            session()->flash('message', ['warning', ("ha ocurrido un error" . $e)]);

            Log::debug('Error actualizando el producto.Error: ' . json_encode($e));

            return redirect()->back()
                ->withInput($request->input());

            DB::rollBack();
        }
    }



    public function updateColor(Request $request, $slug)
    {

        try {

            DB::beginTransaction();

            $producto = ColorProducto::where('slug', $slug)->firstOrFail();
            $producto_id = $producto->producto_id;

            if ($request->activo) {
                $producto->activo = 'Si';
            } else {
                $producto->activo = 'No';    // se edita el campo activo del color
            }

            $producto->color_id = $request->color;

            $url_imagenes = [];

            if ($request->hasFile('imagenes')) {

                $imagenes = $request->file('imagenes');
                foreach ($imagenes as $imagen) {

                    $nombre = time() . '_' . $imagen->getClientOriginalName();

                    //$path = Storage::disk('public')->putFileAs("imagenes/productos/producto" . $producto->producto_id, $imagen, $nombre);

                    //$url_imagenes[]['url'] = $path;

                    $image = Image::make($imagen)->encode('jpg', 75);
                    $image->resize(530, 591, function ($constraint) {
                        $constraint->upsize();
                    });

                    // Storage::disk('dropbox')->put("imagenes/productos/producto".$nombre, $image->stream()->__toString());
                    // $dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
                    // $response = $dropbox->createSharedLinkWithSettings("imagenes/productos/producto" . $nombre, ["requested_visibility" => "public"]);

                    // $url_imagenes[]['url'] = str_replace('dl=0', 'raw=1', $response['url']);

                    // $path = "imagenes/productos/producto/".$producto_id."/".$nombre;

                    $path = "imagenes/productos/producto_" . $producto->id . "/" . $nombre;

                    Storage::disk('public')->put($path, $image->stream());

                    $url_imagenes[]['url'] = $path;
                }
            }

            $producto->save();

            $producto->imagenes()->createMany($url_imagenes);

            DB::commit();

            session()->flash('message', ['success', ("Se ha actualizado el producto exitosamente")]);

            return redirect()->route('product.colors', $producto_id);
            
        } catch (Exception $e) {

            session()->flash('message', ['warning', ("ha ocurrido un error")]);

            Log::debug('Error editando el color producto.Error: ' . json_encode($e));

            return redirect()->back()
                ->withInput($request->input());

            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $numventas = ProductoVenta::whereHas(
                'productoReferencia.colorProducto',
                function (Builder $query) use ($id) {
                    $query->where('id', $id);
                }
            )
                ->count();


            DB::beginTransaction();


            $color = ColorProducto::where('id', $id)->first();

            $productos = ColorProducto::where('producto_id', $color->producto_id)->count(); //se consultan cuantos colores tiene el producto

            if ($numventas == 0) {
                // if ($numventas) {

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

            DB::commit();

            session()->flash('message', ['success', ("Se ha desactivado o eliminado el producto")]);

            if ($productos > 1) {
                return back();
            } else {
                return redirect()->route('product.index');
            }

        } catch (\Exception $e) {

            session()->flash('message', ['warning', ("Ha ocurrido un error al eliminar el producto")]);

            Log::debug('Error eliminando el color_producto.Error: '
                . json_encode($e));

            DB::rollBack();

            return back();
        }
    }



    public function activate($id)
    {
        try {

            $color = ColorProducto::where('id', $id)->first();

            $color->activo = 'Si';

            $color->save(); // se ectiva el color

            session()->flash('message', ['success', ("Se ha activado el producto")]);

            return back();

        } catch (\Exception $e) {

            Log::debug('Error activando el color_producto.Error :' . json_encode($e));

            session()->flash('message', ['warning', ("Ha ocurrido un error al activar el producto")]);

            return back();
        }
    }



    public function createColor($id)
    {
        try {

            $producto = Producto::where('id', $id)->firstOrFail();

            $estados = $this->estado_productos();
            return view('admin.productos.createcolor', compact('producto', 'estados'));
        } catch (\Exception $e) {

            Log::debug('Error en createColor de productos. Error: ' . json_encode($e));
        }
    }



    public function storeColor(Request $request)
    {

        try {

            DB::beginTransaction();

            $producto_id = $request->producto;

            $color_producto = ColorProducto::where('color_id', $request->color)
                ->where('producto_id', $producto_id)
                ->first();

            if ($color_producto) {

                session()->flash('message', ['success', ("Este producto ya ha sido creado anteriormente")]);
                return redirect()->back();
            }

            $producto = Producto::where('id', $producto_id)->first();

            $url_imagenes = [];

            if ($request->hasFile('imagenes')) {

                $imagenes = $request->file('imagenes');

                foreach ($imagenes as $imagen) {

                    $nombre = time() . '_' . $imagen->getClientOriginalName();

                    //$path = Storage::disk('public')->putFileAs("imagenes/productos/producto" . $producto, $imagen, $nombre);

                    //$url_imagenes[]['url'] = $path;

                    $image = Image::make($imagen)->encode('jpg', 75);
                    $image->resize(530, 591, function ($constraint) {
                        $constraint->upsize();
                    });

                    // Storage::disk('dropbox')->put("imagenes/productos/producto".$nombre, $image->stream()->__toString());
                    // $dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
                    // $response = $dropbox->createSharedLinkWithSettings("imagenes/productos/producto" . $nombre, ["requested_visibility" => "public"]);

                    // $url_imagenes[]['url'] = str_replace('dl=0', 'raw=1', $response['url']);

                    // $path = "imagenes/productos/producto/".$producto->id."/".$nombre;

                    $path = "imagenes/productos/producto_" . $producto->id . "/" . $nombre;

                    Storage::disk('public')->put($path, $image->stream());

                    $url_imagenes[]['url'] = $path;
                }
            }


            $colorproducto = new ColorProducto();
            $colorproducto->producto_id = $producto->id;
            $colorproducto->color_id = $request->color;
            $colorproducto->activo = 'Si';

            $colorproducto->save();


            $color_producto = ColorProducto::where('slug', $colorproducto->slug)
                ->where('color_id', $colorproducto->color_id)
                ->where('producto_id', $colorproducto->producto_id)
                ->first();


            $color_producto->imagenes()->createMany($url_imagenes);

            DB::commit();

            session()->flash('message', ['success', ("Se ha creado el producto exitosamente")]);

            return redirect()->route('product.colors', $producto->id);
        } catch (Exception $e) {

            session()->flash('message', ['warning', ("ha ocurrido un error")]);

            Log::debug('Error creando el color_producto.Error: ' . json_encode($e));

            return redirect()->back()
                ->withInput($request->input());

            DB::rollBack();
        }
    }



    //reemplazado por ruta api imagenController
    public function eliminarImagen(Request $request, $id)
    {
        if (!request()->ajax()) {
            abort(401, 'Acceso denegado');
        }

        try {

            $image = Imagene::find($id);

            $eliminar = Storage::disk('public')->delete($image->url); // se elimina del directorio

            $image->delete(); // se elimina de la bd

            return "eliminado id:" . $id . ' ' . $eliminar;
        } catch (\Exception $e) {

            session()->flash('message', ['warning', ("ha ocurrido un error")]);

            Log::debug('Error eliminando la imagen.Error: ' . json_encode($e));

            return back();
        }
    }



    public function estado_productos()
    {
        return [
            1,
            2
        ];
    }
}
