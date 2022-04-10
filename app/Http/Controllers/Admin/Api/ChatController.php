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
    public function index(Request $request){

        if (!$request->ajax()) return redirect('/chats/admin');
        $buscar = $request->buscar;
        
        $user = auth()->user()->id;

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
}
