<?php

namespace App\Http\Controllers\Admin;

use App\Cliente;
use App\Pedido;
use App\User;
use App\Venta;
use App\Http\Controllers\Controller;
use App\Mail\ClientePrivateMail;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ClienteController extends Controller
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


    public function index(Request $request)
    {

        $keyword = $request->get('keyword');

        try {
            

            $clientes = User::with('cliente')
                ->where('users.nombres','like',"%$keyword%")
                ->orWhere('users.apellidos','like',"%$keyword%")
                ->orWhere('users.identificacion','like',"%$keyword%")
                ->orWhere('users.direccion','like',"%$keyword%")
                ->orWhere('users.telefono','like',"%$keyword%")
                ->orWhere('users.email','like',"%$keyword%")
                ->paginate(5);
    
            // return $clientes;
    
            return view('admin.clientes.index', compact('clientes'));

        } catch (\Exception $e) {
            return $e;
        }

    }



    public function show($id)
    {

        try {

           
            $pedidos = Venta::with(['pedido', 'factura', 'cliente.user.imagene'])
                ->where('cliente_id', $id)
                ->where('cliente_id', $id)
                // ->where('estado', '!=', '3')
                ->estado();
                // ->paginate(10);


            $total_general = $pedidos->sum('valor');

            $pedidos = $pedidos->paginate(10);


            $total_pagina = $pedidos->sum('valor');

    
            // $total = 0;
    
            // foreach ($pedidos as $key => $value) {
            //   $total = $total + $value->valor;
            // }
            
            // return view('admin.clientes.show', compact('pedidos', 'total'));

            return view('admin.clientes.show', compact('pedidos', 'total_general', 'total_pagina'));

        } catch (\Exception $e) {
            //throw $th;
        }

    }



    public function sendMessage()
    {
        
        try {
            
            $info = \request('info');

            $data = [];
            
            parse_str($info, $data);


            $cliente = Cliente::with('user')
                ->where('id', $data['cliente_id'])
                ->first();


            Mail::to($cliente->user->email)->send(new ClientePrivateMail($cliente->user->nombres, $data['mensaje']));
            
            $success = true;

            
            // return new ClientePrivateMail($cliente->user->nombres, $data['mensaje']);


            return response()->json(['response' => $success]);

        } catch (\Exception $e) {
            $success = false;
        }
    
    }



    public function pdfListadoClientes()
    {

        try {
           
            $clientes = Cliente::join('users','clientes.user_id', '=', 'users.id')
                ->select('clientes.id','users.nombres', 'users.apellidos','users.departamento',
                'users.municipio','users.direccion','users.telefono','users.email')
                ->paginate(10);
    
            $count = 0;
            foreach ($clientes as $cliente) {
                $count = $count + 1;
            }
    
            $pdf = \PDF::loadView('admin.pdf.listadoclientes',['clientes'=>$clientes, 'count'=>$count])
            ->setPaper('a4', 'landscape');
            
            return $pdf->download('listadoclientes.pdf');
            
        } catch (\Exception $e) {

            Log::debug('Error imprimiendo el listado de clientes. Error: '.json_encode($e));
        }

    }


    //en desuso, se implemento en /api/clienteController
    public function clientesChat(Request $request)
    {
       
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        

        $clientes = User::when($buscar, function ($query) use ($buscar, $criterio) {
            return $query->where('users.'.$criterio, 'like', '%'. $buscar . '%');
        })
        ->with('imagene', 'cliente')
        ->paginate(5);
        

        return [
            'pagination' => [
                'total'        => $clientes->total(),
                'current_page' => $clientes->currentPage(),
                'per_page'     => $clientes->perPage(),
                'last_page'    => $clientes->lastPage(),
                'from'         => $clientes->firstItem(),
                'to'           => $clientes->lastItem(),
            ],
            'clientes' => $clientes
        ];

        // return ['clientes' => $clientes];
    }
}
