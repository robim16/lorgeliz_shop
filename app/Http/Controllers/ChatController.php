<?php

namespace App\Http\Controllers;
use App\Chat;
use App\Events\ChatEvent;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
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
        if (!$request->ajax()) return back();

        $user = auth()->user()->id;

        // $chats = Chat::where('chats.from_id', $user)
        // ->orWhere('chats.to_id',$user)
        // ->join('users', 'chats.from_id', 'users.id')
        // ->join('imagenes', function ($join) {
        //     $join->on('users.id', '=', 'imagenes.imageable_id')
        //     ->where('imagenes.imageable_type','App\User');
        // })
        // ->select('chats.*','users.nombres', 'imagenes.url')
        // ->orderBy('chats.fecha')
        // ->get();

        $chats = Chat::with('user.imagene')
        ->where('from_id', $user)
        ->orWhere('to_id', $user)
        ->orderBy('created_at')
        ->get();

        return ['chats'=> $chats, 'user' => $user];
    }

    //obtiene los mensajes recibidos para mostrar en las notificaciones de mensajes del usuario
    public function messages(Request $request)
    {
        if (!$request->ajax()) return redirect('/cuenta');

        $user = auth()->user()->id;

        // $chats = DB::table('chats')
        // ->where('chats.to_id', $user)
        // ->join('users', 'chats.from_id', '=', 'users.id')
        // ->join('imagenes', function ($join) {
        //     $join->on('users.id', '=', 'imagenes.imageable_id')
        //     ->where('imagenes.imageable_type','App\User');
        // })
        // ->whereNull('chats.read_at')
        // ->select('chats.*','users.nombres', 'users.apellidos', 'imagenes.url', 'users.id as user')
        // ->orderBy('chats.fecha', 'DESC')
        // ->get();

        $chats = Chat::with('user.imagene')
        ->where('to_id', $user)
        ->whereNull('read_at')
        ->orderBy('created_at', 'DESC')
        ->get();
        
        return ['chats'=> $chats];

    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/cuenta');;

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

        // $data = $this->index($request);


        // foreach ($data['chats'] as $key => $value) {
        //    if ($value->id == $chat->id) {
        //         $data['chats'] = $data['chats'][$key];
        //    }
        // }
   
        
        broadcast(new ChatEvent($data))->toOthers();
      
        $response = ['data' => 'success'];
            
        return response()->json($response);

    }

    public function read_at(Request $request, $chat)
    {
        if (!$request->ajax()) return back();

        $chat = Chat::where('id', $chat)->first();
        $chat->read_at = \Carbon\Carbon::now();

        $chat->save();
    }
}