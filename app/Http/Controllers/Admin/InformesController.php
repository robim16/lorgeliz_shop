<?php

namespace App\Http\Controllers\Admin;

use App\Cliente;
use App\ColorProducto;
use App\Devolucione;
use App\Http\Controllers\Controller;
use App\Pago;
use App\Producto;
use App\ProductoVenta;
use App\ProductoReferencia;
use App\Venta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InformesController extends Controller
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



    public function informeVentas(Request $request)
    {
        //obtener ventas por meses

        $anio = date('Y');


        try {
           
            $ventas = Venta::selectRaw('MONTH(fecha) as mes, YEAR(fecha) as anio,
                COUNT(id) as cantidad, SUM(valor) as total')
                ->whereYear('fecha',$anio)
                // ->where('estado', '!=', '3')
                ->estado()
                ->groupBy(DB::raw('MONTH(fecha)'),DB::raw('YEAR(fecha)'))
                ->paginate(5);

        
            return view('admin.informes.ventas.index',compact('ventas'));

        } catch (\Exception $e) {

            Log::debug('Error en la vista del informe de ventas. Error: '.json_encode($e));
        }

    }



    public function pdfInformeVentas(Request $request)
    {

        // $fecha_de = $request->get('fecha_de');
        // $fecha_a = $request->get('fecha_a');

        $anio = date('Y');

        
        
        try {
           
            $ventas = Venta::selectRaw('MONTH(fecha) as mes, YEAR(fecha) as anio
                , COUNT(id) as cantidad, SUM(valor) as total')
                ->whereYear('fecha',$anio)
                ->groupBy(DB::raw('MONTH(fecha)'),DB::raw('YEAR(fecha)'))
                ->get();
    
    

            $count = $ventas->count();
    
            $pdf = \PDF::loadView('admin.pdf.informeventas', ['ventas'=>$ventas, 'count'=>$count])
                ->setPaper('a4', 'landscape');
            
            return $pdf->download('ventas.pdf');

        } catch (\Exception $e) {

            Log::debug('Error imprimiendo el pdf del informe de ventas. Error: '.json_encode($e));
        }

    }



    public function mostrarVentas(Request $request,$mes)
    {

        $fecha_de = $request->get('fecha_de');

        $fecha_a = $request->get('fecha_a');

        $anio = date('Y');

        if ($fecha_de == '') {
           $fecha_de = '01/01/'.$anio;
        }

        if ($fecha_a == '') {
            $fecha_a = \Carbon\Carbon::now();
        }


        //obtener ventas en el mes seleccionado

        try {

            $ventas = ProductoVenta::whereHas('venta', function (Builder $query) 
                use ($mes, $anio, $fecha_de, $fecha_a) {
                    $query->whereMonth('fecha',$mes)
                    ->whereYear('fecha',$anio)
                    ->whereBetween('fecha',[$fecha_de, $fecha_a])
                    ->orderBy('created_at', 'DESC');
                })
                ->with('venta.factura')
                ->select('id','venta_id', DB::raw('SUM(cantidad) as cantidad'))
                ->groupBy('venta_id')
                ->paginate(5);

    
            return view('admin.informes.ventas.show',compact('ventas'));
           
        } catch (\Exception $e) {

            Log::debug('Error en show del informe de ventas. Error: '.json_encode($e));
        }
        
    }



    public function pdfVentaShow(Request $request)
    {

        $mes = date('m', strtotime($request->mes));
        $anio = date('Y');

        try {
            
            $ventas = ProductoVenta::whereHas('venta', function (Builder $query) 
                use ($mes,$anio) {
                    $query->whereMonth('fecha',$mes)
                    ->whereYear('fecha',$anio);
                })
                ->with('venta.factura')
                ->select('id','venta_id', DB::raw('SUM(cantidad) as cantidad'))
                ->groupBy('venta_id')
                ->get();
    
    

            $count = $ventas->count();

    
            $pdf = \PDF::loadView('admin.pdf.informeventashow',['ventas'=>$ventas, 'count'=>$count])
                ->setPaper('a4', 'landscape');

            return $pdf->download('ventas_mes.pdf');

        } catch (\Exception $e) {

            Log::debug('Error en el show pdf del informe de ventas. Error: '.json_encode($e));
        }

    }



    public function ventaProductos(Request $request)
    {
       
        $busqueda = $request->busqueda;

        try {
           
            $productos = ProductoVenta::whereHas('productoReferencia.colorProducto.producto', 
                function (Builder $query) use ($busqueda) {
                    $query->orWhere('nombre','like',"%$busqueda%");//no funciona el filtro
                })
                ->with('productoReferencia')
                ->select('producto_referencia_id', DB::raw('SUM(cantidad) as cantidad'))
                ->orderBy('cantidad', 'DESC')
                ->groupBy('producto_referencia_id')
                ->paginate(5);
    
            
            return view('admin.informes.productos.index',compact('productos'));

        } catch (\Exception $e) {

            Log::debug('Error en la vista del informe de ventas de productos. Error: '.json_encode($e));
        }

    }



    public function pdfInformeProductos(Request $request)
    {


        try {
           
            $productos = ProductoVenta::with('productoReferencia')
                ->select('producto_referencia_id', DB::raw('SUM(cantidad) as cantidad'))
                ->groupBy('producto_referencia_id')
                ->orderBy('cantidad', 'DESC')
                ->get();
            
           

            $count = $productos->count();

    
            $pdf = \PDF::loadView('admin.pdf.informeproductos',['productos'=>$productos, 'count'=>$count])
                ->setPaper('a4', 'landscape');
    
            return $pdf->download('productos.pdf');

        } catch (\Exception $e) {

            Log::debug('Error en el pdf del informe de ventas de productos. Error: '.json_encode($e));
        }

    }
    



    public function informeClientes(Request $request)
    {
        $busqueda = $request->busqueda;


        try {
           
            $clientes = Venta::when($busqueda, function ($query) use ($busqueda) {
                return $query->whereHas('cliente.user',  function (Builder $query) use ($busqueda) {
                    $query->orWhere('id','like',"%$busqueda%")
                    ->orWhere('nombres','like',"%$busqueda%")
                    ->orWhere('apellidos','like',"%$busqueda%")
                    ->orWhere('users.telefono','like',"%$busqueda%");//no funcionan los filtros
                });
            })
            ->with('cliente.user')
            ->select('cliente_id', DB::raw('COUNT(id) as cantidad'))
            ->groupBy('cliente_id')
            ->orderBy('cantidad', 'DESC')
            ->paginate(5);
    
            return view('admin.informes.clientes.index',compact('clientes'));

        } catch (\Exception $e) {

            Log::debug('Error en la vista del informe de clientes. Error: '.json_encode($e));
        }

    }



    public function pdfInformeClientes(Request $request)
    {
       
        try {
           
            $clientes = Venta::with('cliente.user')
                ->select('cliente_id', DB::raw('COUNT(id) as cantidad'))
                ->groupBy('cliente_id')
                ->orderBy('cantidad', 'DESC')
                ->get();
        

            $count = $clientes->count();

    
            $pdf = \PDF::loadView('admin.pdf.informeclientes',['clientes'=>$clientes, 'count'=>$count])
                ->setPaper('a4', 'landscape');
    
            return $pdf->download('clientes.pdf');

        } catch (\Exception $e) {

            Log::debug('Error en el pdf del informe de clientes. Error: '.json_encode($e));
        }

    }



    public function informePagos(Request $request)
    {

        $anio = date('Y');


        try {
            
            $pagos = Pago::selectRaw('MONTH(fecha) as mes, YEAR(fecha) as anio,
                COUNT(id) as cantidad, SUM(monto) as total')
                ->whereYear('fecha',$anio)
                ->groupBy(DB::raw('MONTH(fecha)'),DB::raw('YEAR(fecha)'))
                ->paginate(5);
        
            return view('admin.informes.pagos.index',compact('pagos'));

        } catch (\Exception $e) {

            Log::debug('Error en la vista del informe de pagos. Error: '.json_encode($e));
        }

    }



    public function pdfInformePagos(Request $request)
    {

        $anio = date('Y');

        try {

            $pagos = Pago::selectRaw('MONTH(fecha) as mes, YEAR(fecha) as anio,
                COUNT(id) as cantidad, SUM(monto) as total')
                ->whereYear('fecha',$anio)
                ->groupBy(DB::raw('MONTH(fecha)'),DB::raw('YEAR(fecha)'))
                ->get();
           

            $count = $pagos->count();
    
            $pdf = \PDF::loadView('admin.pdf.informepagos',['pagos'=>$pagos, 'count'=>$count])
                ->setPaper('a4', 'landscape');
    
            return $pdf->download('pagos.pdf');
            
        } catch (\Exception $e) {

            Log::debug('Error en el pdf del informe de pagos. Error: '.json_encode($e));
        }

    }



    public function mostrarPagos(Request $request,$mes)
    {

        $fecha_de = $request->get('fecha_de');
        
        $fecha_a = $request->get('fecha_a');

        $anio = date('Y');

        if ($fecha_de == '') {
           $fecha_de = '01/01/'.$anio;
        }

        if ($fecha_a == '') {
            $fecha_a = \Carbon\Carbon::now();
        }

       
        try {

            $pagos = Pago::whereMonth('fecha',$mes)
                ->whereBetween('fecha',[$fecha_de, $fecha_a])
                ->groupBy('id')
                ->orderBy('created_at', 'DESC')
                ->paginate(5);
    
            return view('admin.informes.pagos.show',compact('pagos'));
           
        } catch (\Exception $e) {

            Log::debug('Error en show del informe de pagos. Error: '.json_encode($e));
        }

    }



    public function pdfPagosShow(Request $request)
    {
        //informe de pagos en el mes

        try {
            
            $mes = date('m', strtotime($request->mes));
    
            $pagos = Pago::whereMonth('fecha',$mes)
                ->orderBy('created_at', 'DESC')
                ->get();
            
          

            $count = $pagos->count();
    
            $pdf = \PDF::loadView('admin.pdf.informepagosmes',['pagos'=>$pagos, 'count'=>$count])
                ->setPaper('a4', 'landscape');
    
            return $pdf->download('pagos_mes.pdf');

        } catch (\Exception $e) {

            Log::debug('Error en el pdf del show del informe de pagos. Error: '.json_encode($e));
        }

    }



    public function informe_saldos_clientes()
    {
        try {

            $saldos_pendientes = Venta::with('cliente')
                ->where('saldo', '>', '0')
                ->where('estado','2')
                ->select('cliente_id', DB::raw('COUNT(id) as facturas'),
                DB::raw('SUM(saldo) as saldos'))
                ->groupBy('cliente_id')
                ->paginate(5);
    
            return view('admin.informes.saldos.index',compact('saldos_pendientes'));
           
        } catch (\Exception $e) {

            Log::debug('Error la vista del informe de saldos de clientes. Error: '.json_encode($e));
        }
    }



    public function informeSaldosClientesPdf()
    {

        try {
           
            $saldos_pendientes = Venta::with('cliente')
                ->where('saldo', '>', '0')
                ->where('estado','2')
                ->select('cliente_id', DB::raw('COUNT(id) as facturas'),
                DB::raw('SUM(saldo) as saldos'))
                ->groupBy('cliente_id')
                ->get();
    

            $count = $saldos_pendientes->count();
    
            $pdf = \PDF::loadView('admin.pdf.informesaldos',['saldos_pendientes'=>$saldos_pendientes,
             'count'=>$count])->setPaper('a4', 'landscape');

            return $pdf->download('saldosclientes.pdf');

        } catch (\Exception $e) {
           
            Log::debug('Error el pdf del informe de saldos de clientes. Error: '.json_encode($e));
        }
        
    }



    public function facturasPendientesCliente(Cliente $cliente)
    {
        try {
          
            $saldos_pendientes = Venta::with('cliente')
                ->where('saldo', '>', '0')
                ->where('estado','2')
                ->where('cliente_id', $cliente->id)
                ->orderBy('fecha', 'DESC')
                ->paginate(5);
    
            return view('admin.informes.saldos.show',compact('saldos_pendientes'));

        } catch (\Exception $e) {

            Log::debug('Error la vista del informe de facturas pendientes. Error: '.json_encode($e));
        }
        
    }



    public function inventarios(Request $request)
    {

        try {
            
            $busqueda = $request->busqueda;
            
            $productos = ProductoReferencia::whereHas('colorProducto', function (Builder $query) {
                    $query->where('activo', 'Si');
                })
                ->whereHas('colorProducto.producto', function (Builder $query) use($busqueda) {
                    $query->where('nombre', 'like',"%$busqueda%");
                })
                ->with(['talla', 'colorProducto'])//faltan los filtros
                ->where('stock', '<=', '5')
                ->orderBy('stock', 'ASC')
                ->paginate(10);
                
    
            return view('admin.informes.inventarios.index',compact('productos'));

        } catch (\Exception $e) {

            Log::debug('Error la vista del informe de inventarios. Error: '.json_encode($e));
        }

    }
}
