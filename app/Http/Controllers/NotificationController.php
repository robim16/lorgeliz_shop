<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use App\Cliente;
use Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        $unreadNotifications = Auth::user()->cliente->unreadNotifications;
        
        return $unreadNotifications;
        
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
            Log::debug('Error leyendo la notificación del cliente.Error: '.json_encode($e));
        }

    }
}

