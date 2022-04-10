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
        if (!$request->ajax()) return redirect('/chats/admin');
        $buscar = $request->buscar;
        
        $user = auth()->user()->id;

        // $chats = DB::table('chats')
        // ->whereNotIn('chats.from_id', [$user])//obtenemos los mensajes escritos por los clientes al admin
        // ->join('users', 'chats.from_id', '=', 'users.id')
        // ->join('imagenes', function ($join) {
        //     $join->on('users.id', '=', 'imagenes.imageable_id')
        //     ->where('imagenes.imageable_type','App\User');
        // })
        // ->select('chats.*','users.nombres', 'users.apellidos', 'imagenes.url', 'users.id as cliente')
        // ->orderBy('chats.fecha', 'DESC')
        // ->groupBy('chats.from_id')
        // ->paginate(5);

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
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/chats/admin');

        // if ($request->admin == true) {

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

            

            // $data = $this->show($request, $request->cliente);//obtenemos los mensajes intercambiados con el cliente

            // foreach ($data['chats'] as $key => $value) {
            //     if ($value->id == $chat->id) {
            //        $data['chats'] = $data['chats'][$key];
            //     }
            // }

            broadcast(new ChatEvent($data))->toOthers();

            $response = ['data' => 'success', 'msg' => $msg];//respondemos con los mensajes para actualizar el chat
                
            return response()->json($response);
        // }
       
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
        if (!$request->ajax()) return redirect('/chats/admin');

        $user = auth()->user()->id;

        // $mensajes = Chat::whereIn('from_id', [$user, $cliente])
        // ->whereIn('to_id', [$user, $cliente])//contamos los mensajes intercambiados por el cliente y el admin
        // ->count();

        $mensajes = Chat::whereIn('from_id', [$user, $cliente])
        ->whereIn('to_id', [$user, $cliente])//contamos los mensajes intercambiados por el cliente y el admin
        ->exists();

        $chats = Chat::when($mensajes, function ($query) use ($user, $cliente) {
            return $query->whereIn('chats.from_id', [$user, $cliente])
            ->whereIn('chats.to_id', [$user, $cliente]);
        },
        function ($query) {
            return $query->where('chats.from_id', $cliente)
            ->orWhere('chats.to_id',$cliente);
        })
        ->with('user.imagene')
        ->orderBy('chats.fecha')
        ->get();

        // if ($mensajes > 0) {//si existen mensajes

        //     $chats = Chat::whereIn('chats.from_id', [$user, $cliente])
        //     ->whereIn('chats.to_id', [$user, $cliente])//mensajes intercambiados por ambos
        //     ->join('users', 'chats.from_id', 'users.id')
        //     ->join('imagenes', function ($join) {
        //         $join->on('users.id', '=', 'imagenes.imageable_id')
        //         ->where('imagenes.imageable_type','App\User');
        //     })
        //     ->select('chats.*','users.nombres', 'imagenes.url')
        //     ->orderBy('chats.fecha')
        //     ->get();
        // }

        // else{

        //     $chats = Chat::where('chats.from_id', $cliente)//buscamos los mensajes enviados o recibidos del cliente
        //     ->orWhere('chats.to_id',$cliente)
        //     ->join('users', 'chats.from_id', 'users.id')
        //     ->join('imagenes', function ($join) {
        //         $join->on('users.id', '=', 'imagenes.imageable_id')
        //         ->where('imagenes.imageable_type','App\User');
        //     })
        //     ->select('chats.*','users.nombres', 'imagenes.url')
        //     ->orderBy('chats.fecha')
        //     ->get();
        // }

        // return ['chats'=> $chats, 'user' => $user];
        return ['chats'=> $chats];
    }


    public function lastMessage(Request $request)
    {
        //obtener los mensajes que se muestran en las notificaciones de chats del admin
        if (!$request->ajax()) return redirect('/chats/admin');

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

        // $chats = DB::table('chats')
        // ->whereNotIn('chats.from_id', [$user])//mensajes enviados por los clientes
        // ->whereNull('chats.read_at')//sin leer
        // ->join('users', 'chats.from_id', '=', 'users.id')
        // // ->join('clientes', 'users.id', 'clientes.user_id')
        // ->join('imagenes', function ($join) {
        //     $join->on('users.id', '=', 'imagenes.imageable_id')
        //     ->where('imagenes.imageable_type','App\User');
        // })
        // ->select('chats.*','users.nombres', 'users.apellidos', 'imagenes.url', 'users.id as cliente')
        // ->orderBy('chats.fecha', 'DESC')
        // ->groupBy('users.id')
        // ->get();
        
        return ['chats'=> $chats];
    }

    public function readMessage(Request $request, $chat)
    {
        if (!$request->ajax()) return redirect('/chats/admin');

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
    }
}
