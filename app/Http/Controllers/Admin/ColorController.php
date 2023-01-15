<?php

namespace App\Http\Controllers\Admin;

use App\Color;
use App\Producto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ColorRequest;
use Illuminate\Http\Request;

class ColorController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nombre = $request->get('nombre');
       
        $colores = Color::where('nombre','like',"%$nombre%")->orderBy('created_at')->paginate(5);
        return view('admin.colores.index',compact('colores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.colores.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ColorRequest $request)
    {
        $color = new Color();
        $color->nombre = $request->nombre;
        $color->descripcion = $request->descripcion;

        $color->save();

        session()->flash('message', ['success', ("Se ha creado el color exitosamente")]);
        return redirect()->route('color.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $color = Color::where('slug',$slug)->firstOrFail();
        return view('admin.colores.show',compact('color'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $color = Color::where('slug',$slug)->firstOrFail();
        return view('admin.colores.edit',compact('color'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ColorRequest $request, $id)
    {
        $color = Color::where('id', $id)->first();
        $color->nombre = $request->nombre;
        $color->descripcion = $request->descripcion;

        $color->save();

        session()->flash('message', ['success', ("Se ha actualizado el color exitosamente")]);
        return redirect()->route('color.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

            $color = Color::where('id', $id)->first();
            $color->delete();

            session()->flash('message', ['success', ("Se ha eliminado el color")]);

            return redirect()->route('color.index');
        }

        catch (\Exception $exception){

            session()->flash('message', ['warning', ("No puedes eliminar el color porque está en uso")]);

            return redirect()->route('color.index');
        }
    }

    //implementada en index de Admin/Api/ColorController
    public function getColores(Request $request, $id)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        //$id  = $request->producto;
        
        $colores = Color::join('color_producto', 'colores.id', 'color_producto.color_id')
        ->where('color_producto.producto_id', $id)
        ->get();

        return ['colores' => $colores];
        
        //$response = ['data' => $colores];
        
        //return response()->json($response); //obtener colores al actualizar stock en el modal

    }
}
