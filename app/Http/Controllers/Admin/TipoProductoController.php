<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoRequest;
use App\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TipoProductoController extends Controller
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



    public function index(Request $request)
    {
        try {

            $nombre = $request->get('nombre');
           
            $tipos = Tipo::where('nombre','like',"%$nombre%")
            ->orderBy('id')
            ->paginate(5);
            
            return view('admin.tipo_producto.index',compact('tipos'));
           
        } catch (\Exception $e) {
            Log::debug('Error consultando el index de tipos de productos.Error: '.json_encode($e));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tipo_producto.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoRequest $request)
    {

        try {

            $tipo = new Tipo();
            $tipo->nombre = $request->nombre;
            $tipo->descripcion = $request->descripcion;
            $tipo->subcategoria_id = $request->subcategory_id;
    
            $tipo->save();
    
            session()->flash('message', ['success', ("Se ha creado el tipo exitosamente")]);
            return redirect()->route('tipo.index');

        } catch (\Exception $e) {

           Log::debug('Error creando el tipo de producto.Error: '.json_encode($e));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $tipo = Tipo::where('slug',$slug)->firstOrFail();
        return view('admin.tipo_producto.show',compact('tipo'));
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $tipo = Tipo::where('slug',$slug)->firstOrFail();
        return view('admin.tipo_producto.edit',compact('tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(TipoRequest $request, Tipo $tipo)
    public function update(TipoRequest $request, $id)
    {
        try {
            
            $tipo = Tipo::where('id', $id)->firstOrFail();
    
            $tipo->nombre = $request->nombre;
            $tipo->descripcion = $request->descripcion;
            $tipo->subcategoria_id = $request->subcategory_id;
    
            $tipo->save();
    
            session()->flash('message', ['success', ("Se ha creado editado el tipo exitosamente")]);

            return redirect()->route('tipo.index');

        } catch (\Exception $e) {

            Log::debug('Error editando el tipo de producto.Error: '.json_encode($e));
        }
        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function destroy(Tipo $tipo)
    public function destroy($id)
    {
        try{

            $tipo = Tipo::where('id', $id)->firstOrFail();

            $tipo->delete();

            session()->flash('message', ['success', ("Se ha eliminado el tipo de producto")]);

            return redirect()->route('tipo.index');
        }

        catch (\Exception $e){

            session()->flash('message', ['warning', ("No se puede eliminar la subcategoría porque está en uso")]);

            Log::debug('Error eliminando el tipo de producto.Error: '.json_encode($e));

            return redirect()->route('tipo.index');
        }
    }


    
    //ruta api, en desuso. obtiene los tipos de productos de acuerdo a una subcategoría, al crear un producto
    public function getTipo(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}


        $id  = $request->subcategoria;
        $tipos = Tipo::where('subcategoria_id', $id)->get(); 
        
        $response = ['data' => $tipos];
        
        return response()->json($response);

    }
}
