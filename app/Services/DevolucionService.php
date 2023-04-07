<?php

namespace App\Services;

use App\Devolucione;
use App\Jobs\SendEmail;
use App\User;
use Illuminate\Http\Request;

class DevolucionService
{
    
    public function saveDevolucion(Request $request)
    {
        
        $producto = $request->producto;

        $venta = $request->venta;

        $cantidad = $request->cantidad;

        $devoluciones = Devolucione::where('producto_referencia_id', $producto)//$ref
            ->where('venta_id', $venta)
            ->count(); // verificamos que no se haya solicitado la devolución anteriormente


        if ($devoluciones == 0) {
            
            $devolucion = new Devolucione();
            $devolucion->fecha = \Carbon\Carbon::now();
            $devolucion->cantidad = $cantidad;
            $devolucion->producto_referencia_id = $producto; //$ref
            $devolucion->venta_id = $venta;

            $devolucion->save();

            $admin = User::where('role_id', 2)->first();
            $user = auth()->user();

            
        
            $details = [
                'title' => 'Se ha solicitado una nueva devolucion',
                'user' => $admin->nombres,
                'cliente' => $user->nombres.' '.$user->apellidos,
                'url' => url('/admin/devoluciones/'. $devolucion->id),
            ];

            
            SendEmail::dispatch($details, $admin);

            // Mail::to($admin->email)->send(new AdminDevolucionMail($details));

            // User::findOrFail($admin->id)->notify(new AdminDevolucionMail($details));

        } 

        $response = ['data' => $devoluciones];
        
        return $response;
    }
}
