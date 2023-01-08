<?php

namespace App\Http\Controllers;

use App\Jobs\SendClientToAdminMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClientToAdminMail;
use Illuminate\Support\Facades\Log;

class ContactoController extends Controller
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

    public function contacto()
    {
        return view('user.contact');
    }

    public function sendMail(Request $request)
    {

        try {
          

            $admin = User::where('role_id', 2)->first();
            $user = auth()->user();
        
            $details = [
                'title' => 'Has recibido un email de un cliente',
                'cliente' => $user->nombres.' '.$user->apellidos,
                'mensaje' => $request->mensaje,
                'url' => url('/admin/clientes/'. $user->cliente->id),
            ];
    

            // Mail::to($admin->email)->send(new ClientToAdminMail($details));
    
            // return new ClientToAdminMail($details);

            SendClientToAdminMail::dispatch($details, $admin);

            session()->flash('message', ['success', ("Se ha enviado el email exitosamente")]);

            return back();


        } catch (\Exception $e) {

            Log::debug('Error el email desde el formulario de contacto'.'error:'.' '.json_encode($e));
        }


    }

}
