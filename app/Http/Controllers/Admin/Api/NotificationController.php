<?php

namespace App\Http\Controllers\Admin\Api;

use App\Notification;
use App\Cliente;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    //notificaciones del admin
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $unreadNotifications = Auth::user()->unreadNotifications;

        return Auth::user()->unreadNotifications;
    }

    //leer notificación del admin
    public function update(Request $request, $id)
    {
        if (!$request->ajax()) return redirect('/');

        $notification = Notification::where('id', $request->id)->firstOrFail();
        $notification->read_at =  \Carbon\Carbon::now();

        $notification->save();
    }
}
