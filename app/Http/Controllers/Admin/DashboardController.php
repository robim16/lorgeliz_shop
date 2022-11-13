<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
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

    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function loadVentas(Request $request)
    {

        $anio = date('Y');

        try {
          
            $ventas = DB::table('ventas as v')
            ->select(DB::raw('MONTH(v.fecha) as mes'),
            DB::raw('YEAR(v.fecha) as anio'),
            DB::raw('SUM(v.valor) as total'))
            ->whereYear('v.fecha',$anio)
            ->where('v.estado', '!=', '3')
            ->groupBy(DB::raw('MONTH(v.fecha)'),DB::raw('YEAR(v.fecha)'))
            ->get();

    
            $pagos = DB::table('pagos as p')
            ->select(DB::raw('MONTH(p.fecha) as mes'),
            DB::raw('YEAR(p.fecha) as anio'),
            DB::raw('SUM(p.monto) as total'))
            ->whereYear('p.fecha',$anio)
            ->groupBy(DB::raw('MONTH(p.fecha)'),DB::raw('YEAR(p.fecha)'))
            ->get();

    
            $pedidos = DB::table('pedidos as p')
            ->select(DB::raw('MONTH(p.fecha) as mes'),
            DB::raw('YEAR(p.fecha) as anio'),
            DB::raw('COUNT(p.id) as total'))
            ->whereYear('p.fecha',$anio)
            ->groupBy(DB::raw('MONTH(p.fecha)'),DB::raw('YEAR(p.fecha)'))
            ->get();

            
            $clientes = DB::table('clientes as c')
            ->select(DB::raw('MONTH(c.created_at) as mes'),
            DB::raw('YEAR(c.created_at) as anio'),
            DB::raw('COUNT(c.id) as total'))
            ->whereYear('c.created_at',$anio)
            ->groupBy(DB::raw('MONTH(c.created_at)'),DB::raw('YEAR(c.created_at)'))
            ->get();
    
            
            $productos = DB::table('productos as p')
            ->select(DB::raw('MONTH(p.created_at) as mes'),
            DB::raw('YEAR(p.created_at) as anio'),
            DB::raw('COUNT(p.id) as total'))
            ->whereYear('p.created_at',$anio)
            ->groupBy(DB::raw('MONTH(p.created_at)'),DB::raw('YEAR(p.created_at)'))
            ->get();
    
            $devoluciones = DB::table('devoluciones as d')
            ->select(DB::raw('MONTH(d.fecha) as mes'),
            DB::raw('YEAR(d.fecha) as anio'),
            DB::raw('COUNT(d.id) as total'))
            ->whereYear('d.fecha',$anio)
            ->groupBy(DB::raw('MONTH(d.fecha)'),DB::raw('YEAR(d.fecha)'))
            ->get();
            
    
            return ['ventas'=>$ventas, 'pedidos'=>$pedidos, 'clientes'=>$clientes, 
                'productos'=>$productos,'anio'=>$anio, 'pagos'=>$pagos, 
                'devoluciones'=>$devoluciones
            ];

        } catch (\Exception $e) {
            Log::debug('Error obteniendo la información del dashboard.Error: '.json_encode($e));
        }

    }
}
