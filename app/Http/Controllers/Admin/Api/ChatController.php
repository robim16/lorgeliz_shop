<?php

namespace App\Http\Controllers\Admin\Api;

use App\Chat;
use App\Events\ChatEvent;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function index(Request $request)
    {


        if (!request()->ajax()) {
            abort(401, 'Acceso denegado');
        }

        $buscar = $request->buscar;

        // $user = auth()->user()->id;

        $user = $request->user()->id;
        

        try {

            $chats = User::has('chats')
            ->addSelect([
                'mensaje' => Chat::select('mensaje')
                    ->whereColumn('from_id', 'users.id')
                    ->orderBy('created_at', 'DESC')
                    ->limit(1)
            ])
            ->addSelect([
                'fecha' => Chat::select('created_at')
                    ->whereColumn('from_id', 'users.id')
                    ->orderBy('created_at', 'DESC')
                    ->limit(1)
            ])
            ->where('users.nombres', 'like', "%$buscar%")
            ->whereNotIn('users.id', [$user])
            ->with('imagene')
            ->orderBy('fecha', 'DESC')
            ->paginate(5);
    
            return [
                'pagination' => [
                    'total'        => $chats->total(),
                    'current_page' => $chats->currentPage(),
                    'per_page'     => $chats->perPage(),
                    'last_page'    => $chats->lastPage(),
                    'from'         => $chats->firstItem(),
                    'to'           => $chats->lastItem(),
                ],
                'chats' => $chats
            ];

        } catch (\Exception $e) {
            //throw $th;
        }
    }


     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $user = $request->user()->id;

        try {

            $chat = new Chat();
            // $chat->from_id = auth()->user()->id;
            $chat->from_id = $user;
            $chat->to_id = $request->cliente;
            $chat->mensaje = $request->mensaje;
            $chat->fecha = \Carbon\Carbon::now();
    
            $chat->save();
    
            $data = array();
            $data['chats'] = array();

            $msg = Chat::with('user.imagene')
                ->where('id', $chat->id)
                ->first();
    
            $data['chats'] = $msg;
    
            
            broadcast(new ChatEvent($data))->toOthers();
    
            $response = ['data' => 'success', 'msg' => $msg];//respondemos con los mensajes para actualizar el chat
                
            return response()->json($response);
            
        } catch (\Exception $e) {
            //throw $th;
        }
       
    }

}
