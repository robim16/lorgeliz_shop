<?php

namespace App\Http\Controllers\Admin;

use App\ColorProducto;
// use App\Imagene;
use App\Producto;
use App\ProductoReferencia;
use App\ProductoVenta;
// use App\Events\ProductStatusEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\Admin\ProductService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Storage;
// use Intervention\Image\Facades\Image;
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
        // $busqueda = $request->get('busqueda');

        // $productos = Producto::orWhere('productos.nombre', 'like', "%$busqueda%")
        //     ->orWhere('productos.id', 'like', "%$busqueda%")
        //     ->with('colors:id')
        //     ->withCount('colors')
        //     ->paginate(10);
       

        // $pivots = new \Illuminate\Database\Eloquent\Collection();

        // foreach ($productos as $producto) {
        //     $pivots = $pivots->concat($producto->colors[0]->pivot->all());
        // }

        // $pivots->load('imagenes');


        // foreach ($productos as $producto) {
        //     $producto->colors[0]->pivot->load(['imagenes' => function ($query) {
        //         $query->select('id', 'url', 'imageable_id')
        //             ->limit(1);
        //     }]);
        // }


        // return view('admin.productos.index', compact('productos')); //index de productos en admin

        return view('admin.productos.index');

    }


    public function product(Request $request, $id)
    {

        $busqueda = $request->get('busqueda');


        try {

            $productos = ColorProducto::whereHas(
                'producto',
                function (Builder $query) use ($id, $busqueda) {
                    $query->where('id', $id)
                        ->where('nombre', 'like', "%$busqueda%")
                        ->orderBy('created_at');
                }
            )
                ->with(['color', 'producto', 'imagenes' => function ($query) {
                    $query->select('id', 'url', 'imageable_id');
                }])
                ->paginate(5);

            return view('admin.productos.coloresproducto', compact('productos'));
        } catch (\Exception $e) {
            return $e;
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

            $producto = new Producto();

            $producto = $productService->saveProduct($request, $producto);

            $url_imagenes = $productService->uploadImage($request, $producto);


            $activo = "";


            if ($request->activo) {
                $activo = 'Si';    
            }
            else {
                $activo = 'No';    
            }


            $color_producto = $productService->createColorProducto($request, $producto, $url_imagenes, $activo);


            DB::commit();


            session()->flash('message', ['success', ("Se ha creado el producto exitosamente")]);

            return redirect()->route('product.index');
            
        } catch (Exception $e) {

            session()->flash('message', ['warning', ("ha ocurrido un error".$e)]);

            Log::debug('Error creando el producto.Request: ' . json_encode($request) . ' ' . 'Error: ' . $e);

            return redirect()->back();

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
        $producto = Producto::join('color_producto', 'productos.id', 'color_producto.producto_id')
            ->select('productos.*', 'color_producto.id as cop', 'color_producto.activo')
            ->where('productos.id', $id)
            ->with('colors')
            ->firstOrFail();

        $estados = $this->estado_productos();

        return view('admin.productos.show', compact('producto', 'estados')); //mostrar el producto
    }



    public function showColor($slug)
    {
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $producto = Producto::join('color_producto', 'productos.id', 'color_producto.producto_id')
            ->select('productos.*', 'color_producto.id as cop', 'color_producto.activo')
            ->where('productos.id', $id)
            ->firstOrFail();

        $estados = $this->estado_productos();

        return view('admin.productos.edit', compact('producto', 'estados')); // editar el producto
    }



    public function editColor($slug)
    {
        $producto = Producto::join('color_producto', 'productos.id', 'color_producto.producto_id')
            ->join('colores', 'color_producto.color_id', 'colores.id')
            ->select(
                'productos.*',
                'color_producto.id as cop',
                'color_producto.activo',
                'color_producto.color_id as color_id',
                'color_producto.slug',
                'colores.nombre as color'
            )
            ->where('color_producto.slug', $slug)
            ->firstOrFail();

        $estados = $this->estado_productos();

        return view('admin.productos.editcolor', compact('producto', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, Producto $producto)
    public function update(ProductRequest $request, $id, ProductService $productService)
    {

        try {


            DB::beginTransaction();

            $producto = Producto::where('id', $id)->firstOrFail();


            $productService->saveProduct($request, $producto);


            DB::commit();


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

            Log::debug('Error actualizando el producto' . json_encode($producto));

            return redirect()->back()
                ->withInput($request->input());

            DB::rollBack();
        }
    }



    public function updateColor(Request $request, AdminProductService $productService, $slug)
    {

        try {

            DB::beginTransaction();

            $color_producto = $productService->updateColorProducto($request, $slug);

            $producto = $color_producto->producto_id;

            $url_imagenes = $productService->uploadImage($request, $producto);

            $color_producto->imagenes()->createMany($url_imagenes);

            DB::commit();

            session()->flash('message', ['success', ("Se ha actualizado el producto exitosamente")]);

            return redirect()->route('product.colors', $producto);

        } catch (Exception $e) {

            session()->flash('message', ['warning', ("ha ocurrido un error")]);

            Log::debug('Error editando el color producto' . ' ' . 'color_producto: '
                . json_encode($producto));

            return redirect()->back();

            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductService $productService, $id)
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


            $productService->eliminarProducto($numventas, $color, $productos, $id);


            DB::commit();

            session()->flash('message', ['success', ("Se ha desactivado o eliminado el producto")]);

            if ($productos > 1) {
                return back();
            } else {
                return redirect()->route('product.index');
            }

        } catch (\Exception $exception) {

            session()->flash('message', ['warning', ("Ha ocurrido un error al eliminar el producto")]);

            Log::debug('Error eliminando el color_producto: '
                . json_encode($color));

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

            Log::debug('Error activando el color_producto: ' . json_encode($color));

            session()->flash('message', ['warning', ("Ha ocurrido un error al activar el producto")]);

            return back();
        }
    }



    public function createColor($id)
    {
        $producto = Producto::where('id', $id)->firstOrFail();

        $estados = $this->estado_productos();
        return view('admin.productos.createcolor', compact('producto', 'estados'));
    }



    public function storeColor(Request $request, ProductService $productService)
    {

        try {

            DB::beginTransaction();

            $producto_id = $request->producto;

            $productService->validarColorProducto($request);

            $producto = Producto::where('id', $producto_id)->first();

            $url_imagenes = $productService->uploadImage($request, $producto);

            $activo = 'Si';

            $productService->createColorProducto($request, $producto, $url_imagenes, $activo);

            DB::commit();

            session()->flash('message', ['success', ("Se ha creado el producto exitosamente")]);

            return redirect()->route('product.colors', $producto->id);

        } catch (Exception $e) {

            session()->flash('message', ['warning', ("ha ocurrido un error")]);

            Log::debug('Error creando el color_producto.Error: ' . json_encode($e));

            return back();

            DB::rollBack();
        }
    }



    //reemplazado por ruta api imagenController
    public function eliminarImagen(Request $request, ProductService $productService, $id)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {


            $productService->deleteImage($request, $id);

        } catch (\Exception $e) {

            session()->flash('message', ['warning', ("ha ocurrido un error")]);

            Log::debug('Error eliminando la imagen. Error ' . json_encode($e));

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
