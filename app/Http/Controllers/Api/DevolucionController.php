<?php

namespace App\Http\Controllers\Api;

Use App\Devolucione;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DevolucionController extends Controller
{
    public function verify(Request $request){

        if (!$request->ajax()) return redirect('/');
        
        return Devolucione::where('venta_id',$request->venta)
        ->where('producto_referencia_id',$request->producto)
        ->first();
    }
}
