<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Subcategoria;
use App\Http\Requests\SubcategoriaRequest;
use Illuminate\Http\Request;


class SubcategoryController extends Controller
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
       
        $subcategorias = Subcategoria::where('nombre','like',"%$nombre%")
        ->orderBy('created_at')
        ->paginate(5);
        
        return view('admin.subcategorias.index',compact('subcategorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.subcategorias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubcategoriaRequest $request)
    {
        try {
           
            $subcategoria = new Subcategoria();
            $subcategoria->nombre = $request->nombre;
            $subcategoria->descripcion = $request->descripcion;
            $subcategoria->categoria_id = $request->category_id;
    
            $subcategoria->save();
    
            session()->flash('message', ['success', ("Se ha creado la subcategoría exitosamente")]);

            return redirect()->route('subcategory.index');

        } catch (\Exception $e) {
            //throw $th;
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
        $subcategoria = Subcategoria::where('slug',$slug)->firstOrFail();
        return view('admin.subcategorias.show',compact('subcategoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $subcategoria = Subcategoria::where('slug',$slug)->firstOrFail();
        return view('admin.subcategorias.edit',compact('subcategoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function update(SubcategoriaRequest $request, Subcategoria $subcategoria)
    public function update(SubcategoriaRequest $request, $id)
    {
        try {
            
            $subcategoria = Subcategoria::where('id', $id)->first();
    
            $subcategoria->nombre = $request->nombre;
            $subcategoria->descripcion = $request->descripcion;
            $subcategoria->categoria_id = $request->category_id;
    
            $subcategoria->save();
    
            session()->flash('message', ['success', ("Se ha actualizado la subcategoría exitosamente")]);
    
            return redirect()->route('subcategory.index');

        } catch (\Exception $e) {
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function destroy(Subcategoria $subcategoria)
    public function destroy($id)
    {
        try{

            $subcategoria = Subcategoria::where('id', $id)->first();
            $subcategoria->delete();

            session()->flash('message', ['success', ("Se ha eliminado la subcategoría")]);

            return redirect()->route('subcategory.index');
        }

        catch (\Exception $exception){

            session()->flash('message', ['warning', ("No es posible eliminar la subcategoría porque está en uso")]);

            return redirect()->route('subcategory.index');
        }
    }

    //implementada con api/subcategoryController
    public function getSubcategoria(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
          
            $id  = $request->categoria;
            $subcategorias = Subcategoria::where('categoria_id', $id)->get(); 
            
            $response = ['data' => $subcategorias];
            
            return response()->json($response); //obtener subcategorias al crear un producto en admin

        } catch (\Exception $e) {
            //throw $th;
        }

    }
}
