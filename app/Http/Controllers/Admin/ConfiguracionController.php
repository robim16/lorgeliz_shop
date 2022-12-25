<?php

namespace App\Http\Controllers\Admin;

use App\Configuracion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConfiguracionController extends Controller
{
    public function index()
    {
        try {
           
            $configuracion = Configuracion::where('nit', '78900765')->first();
    
            return view('admin.configuracion.index', compact('configuracion'));

        } catch (\Exception $e) {
            
            Log::debug('Error editando la configuración.Error: '.json_encode($e));
        }

    }


    public function update(Request $request, Configuracion $configuracion)
    {
        
        try {
           
            $configuracion->update($request->all());
    
            session()->flash('message', ['success', ("Se ha actualizado la configuración exitosamente")]);
            return redirect()->route('configuracion.index');
            
        } catch (\Exception $e) {

            Log::debug('Error editando la configuración.Error: '.json_encode($e));
        }
    }
}
