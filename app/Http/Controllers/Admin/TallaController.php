<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Producto;
use App\ProductoReferencia;
use App\Talla;
use App\Tipo;
use Illuminate\Http\Request;

class TallaController extends Controller
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

    public function getTalla(Request $request, $id)
    { //implementada en admin/api/tallaController. En desuso
        if (!$request->ajax()) return redirect('/');

        //$id  = $request->producto;
        $tipo = Producto::where('id', $id)->firstOrFail(); 

        $tallas = Talla::join('talla_tipo', 'tallas.id', 'talla_tipo.talla_id')
        ->where('talla_tipo.tipo_id', $tipo->tipo_id)
        ->get();

        return ['tallas' => $tallas];
        
        //$response = ['data' => $tallas];
        
        //return response()->json($response); //obtener tallas al actualizar stock en el modal

    }

    public function tallasTipoId(Request $request)
    //implementada en admin/api/tallaController. En desuso
    //obtener las tallas de un tipo de producto para mostrar en el select las que ya han sido seleccionadas, en la vista index de tipo de producto
    {   
        if (!$request->ajax()) return redirect('/');

        $id  = $request->id;

        $tallas = Talla::join('talla_tipo', 'tallas.id', 'talla_tipo.talla_id')
        ->where('talla_tipo.tipo_id', $id)
        ->get();
        
        $response = ['data' => $tallas];
        
        return response()->json($response);
    }

    public function store(Request $request)
    {
        $tipo = Tipo::where('id', $request->tipo_id)->firstOrFail();

        $tallas = $request->tallas_id;

        if ($tallas) {

            $tipo->tallas()->detach();
            foreach($tallas as $talla){
                $tipo->tallas()->attach($talla);
            }
            
            session()->flash('message', ['success', ("Se han creado las tallas")]);

            return back();
        }else {
            session()->flash('message', ['danger', ("Debes indicar las tallas")]);

            return back();
        }
       
    }
}
