<?php

namespace App\Http\Controllers\Admin;

use App\Configuracion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        return view('admin.configuracion.index');
        $configuracion = Configuracion::where('nit', '78900765')->first();

        return view('admin.configuracion.index', compact('configuracion'));
    }


    public function update(Request $request, Configuracion $configuracion)
    {
        
        try {
           
            $configuracion->update($request->all());
    
            session()->flash('message', ['success', ("Se ha actualizado la configuración exitosamente")]);
            return redirect()->route('configuracion.index');
            
        } catch (\Exception $e) {
            //throw $th;
        }
    }
}
