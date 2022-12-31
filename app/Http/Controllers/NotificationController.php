<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use App\Cliente;
use Auth;
use Log;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            
            $unreadNotifications = Auth::user()->cliente->unreadNotifications;
    
            return $unreadNotifications;
            
        } catch (\Exception $e) {
            Log::debug('Error en index de notificaciones del cliente'.'Error:'.' '.json_encode($e));
        }
        
    }
    
    
    public function setClientRead(Request $request, $id)
    {

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
           
            $notification = Notification::where('id', $request->id)->firstOrFail();
            $notification->read_at =  \Carbon\Carbon::now();
    
            $notification->save();

        } catch (\Exception $e) {
            Log::debug('Error leyendo notificación del cliente'.'Error:'.' '.json_encode($e));
        }

    }
}

