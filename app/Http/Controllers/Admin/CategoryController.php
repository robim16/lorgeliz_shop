<?php

namespace App\Http\Controllers\Admin;

use App\Categoria;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Http\Request;


class CategoryController extends Controller
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
       
        $categorias = Categoria::where('nombre','like',"%$nombre%")
        ->orderBy('created_at')
        ->paginate(5);
        
        return view('admin.categorias.index',compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaRequest $request)
    {
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;

        $categoria->save();

        session()->flash('message', ['success', ("Se ha creado la categoría exitosamente")]);
        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $categoria = Categoria::where('slug',$slug)->firstOrFail();
        return view('admin.categorias.show',compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $categoria = Categoria::where('slug',$slug)->firstOrFail();
        return view('admin.categorias.edit',compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     //model binding (Categoria $categoria)
    public function update(CategoriaRequest $request, $id)
    {
        $categoria = Categoria::where('id', $id)->first();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;

        $categoria->save();

        session()->flash('message', ['success', ("Se ha actualizado la categoría exitosamente")]);
        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    //model binding (Categoria $categoria)
    {
        try{

            //$categoria->delete();
            $categoria = Categoria::where('id', $id)->first();
            $categoria->delete();

            session()->flash('message', ['success', ("Se ha eliminado la categoría")]);

            return redirect()->route('category.index');
        }

        catch (\Exception $exception){

            session()->flash('message', ['warning', ("No puedes eliminar la categoría porque está en uso")]);

            return redirect()->route('category.index');
        }
    }
}
