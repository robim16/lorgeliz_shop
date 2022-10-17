<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{

    public function index(Request $request)
    {
        // if (!$request->ajax()) return back();

        try {
           
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
            
        } catch (\Exception $e) {
            //throw $th;
        }

    }
}
