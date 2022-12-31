<?php

namespace App\Http\Controllers;
use App\Chat;
use App\Events\ChatEvent;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// use Illuminate\Support\Collection;

class ChatController extends Controller
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


    //retorna en el messenger los mensajes recibidos por el usuario
    public function index(Request $request)
    {

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $user = auth()->user()->id;

      
        try {
           
            $chats = Chat::with('user.imagene')
                ->where('from_id', $user)
                ->orWhere('to_id', $user)
                ->orderBy('created_at')
                ->get();
    
            return ['chats'=> $chats, 'user' => $user];

        } catch (\Exception $e) {
            Log::debug('Error obteniendo mensajes del usuario'.'Usuario:'.' '.
                json_encode(auth()->user()->id).' '.'Error:'.json_encode($e));
        }

    }



    //obtiene los mensajes recibidos para mostrar en las notificaciones de mensajes del usuario
    public function messages(Request $request)
    {

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
            
            $user = auth()->user()->id;
    
            
            $chats = Chat::with('user.imagene')
                ->where('to_id', $user)
                ->whereNull('read_at')
                ->orderBy('created_at', 'DESC')
                ->get();
            
            return ['chats'=> $chats];

        } catch (\Exception $e) {

            Log::debug('Error obteniendo notificaciones del usuario'.'Usuario:'.' '.
                json_encode(auth()->user()->id).' '.'Error:'.json_encode($e));
        }

    }



    public function store(Request $request)
    {

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
            
            $user = User::where('role_id', 2)->first();
                
            $chat = new Chat();
            $chat->from_id = auth()->user()->id;
            $chat->to_id = $user->id;
            $chat->mensaje = $request->mensaje;
            $chat->fecha = \Carbon\Carbon::now();
    
            $chat->save();
    
            
            $data = array();
            $data['chats'] = array();
            $data['cliente'] = array();
    
            $msg = Chat::with('user.imagene')->where('id', $chat->id)->first();
        
            $data['chats'] = $msg;
            $data['cliente'] = auth()->user()->id;
    
    
            broadcast(new ChatEvent($data))->toOthers();
          
            $response = ['data' => 'success'];
                
            return response()->json($response);

        } catch (\Exception $e) {
           Log::debug('Error guardando el mensaje del usuario'.'Usuario:'.' '.
                json_encode(auth()->user()->id).' '.'Error:'.json_encode($e));
        }

    }



    public function read_at(Request $request, $chat)
    {

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
          
            $chat = Chat::where('id', $chat)->first();
            $chat->read_at = \Carbon\Carbon::now();
    
            $chat->save();

        } catch (\Exception $e) {
            
            Log::debug('Error leyendo el mensaje del usuario'.'Usuario:'.' '.
                json_encode(auth()->user()->id).' '.'Error:'.json_encode($e));
        }

    }

}
