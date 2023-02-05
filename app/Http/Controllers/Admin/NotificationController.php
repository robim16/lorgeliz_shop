<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notification;
use App\Cliente;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    //notificaciones del admin
    public function index(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        return Auth::user()->unreadNotifications;
    }


    //leer notificación del admin
    public function update(Request $request, $id)
    {

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}


        try {
            
            $notification = Notification::where('id', $request->id)->firstOrFail();
            $notification->read_at =  \Carbon\Carbon::now();
    
            $notification->save();

        } catch (\Exception $e) {
            Log::debug('Error leyendo la notificación del admin.Error: '.json_encode($e));
            return $e;
        }

    }
}
