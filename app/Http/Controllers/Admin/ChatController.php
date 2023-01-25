<?php

namespace App\Http\Controllers\Admin;

use App\Chat;
use App\Events\ChatEvent;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.chats.index');
    }

    //obtener chats en el index de chats
    public function chatsAjax(Request $request)
    {

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $buscar = $request->buscar;
        
        $user = auth()->user()->id;

      

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
            ->where('users.nombres', 'like',"%$buscar%")
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

        try {

            $chat = new Chat();
            $chat->from_id = auth()->user()->id;
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

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $cliente)
    {
        //chats en el messenger

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
           
            $user = auth()->user()->id;

            $mensajes = Chat::whereIn('from_id', [$user, $cliente])
            ->whereIn('to_id', [$user, $cliente])//contamos los mensajes intercambiados por el cliente y el admin
            ->exists();

            $chats = Chat::when($mensajes, function ($query) use ($user, $cliente) {
                return $query->whereIn('chats.from_id', [$user, $cliente])
                ->whereIn('chats.to_id', [$user, $cliente]);
            },
            function ($query) use($cliente) {
                return $query->where('chats.from_id', $cliente)
                ->orWhere('chats.to_id',$cliente);
            })
            ->with('user.imagene')
            ->orderBy('chats.fecha')
            ->get();

            return ['chats'=> $chats];

        } catch (\Exception $e) {
            //throw $th;
        }
       
    }


    public function lastMessage(Request $request)
    {
        //obtener los mensajes que se muestran en las notificaciones de chats del admin
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}


        try {

            $user = auth()->user()->id;
    
            $recents = Chat::selectRaw('MAX(id)')
                ->whereNotIn('from_id', [$user])
                ->whereNull('read_at')
                ->groupBy('from_id')
                ->get();
    
            $chats = Chat::with('user.imagene')
                ->whereIn('id', $recents)
                ->orderBy('created_at', 'DESC')
                ->get();

            return ['chats'=> $chats];
            
        } catch (\Exception $e) {
            //throw $th;
        }
        
    }



    public function readMessage(Request $request, $chat)
    {

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {

            $chat = Chat::where('id', $chat)->first();
            $chat->read_at = \Carbon\Carbon::now();//buscamos el mensaje y se pone como leído
    
            $chat->save();
    
            $chats = Chat::where('to_id', auth()->user()->id)
            ->where('from_id', $chat->from_id)
            ->whereNull('read_at')//buscamos todos los mensajes no leídos
            ->get();
    
            if ($chats) {
                foreach ($chats as $chat) {
                    $chat->read_at = \Carbon\Carbon::now();
                    $chat->save(); //marcamos como leidos
                }
            }
            
        } catch (\Exception $e) {
            //throw $th;
        }

    }
    
}
