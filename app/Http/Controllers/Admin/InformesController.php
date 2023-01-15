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

        // $ventas=DB::table('ventas as v')
        // ->select(DB::raw('MONTH(v.fecha) as mes'),
        // DB::raw('YEAR(v.fecha) as anio'),
        // DB::raw('COUNT(v.id) as cantidad'),
        // DB::raw('SUM(v.valor) as total'))
        // ->whereYear('v.fecha',$anio)
        // ->where('v.estado', '!=', '3')
        // ->groupBy(DB::raw('MONTH(v.fecha)'),DB::raw('YEAR(v.fecha)'))
        // ->paginate(5); 

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
            //throw $th;
        }

    }



    public function pdfInformeVentas(Request $request)
    {

        // $fecha_de = $request->get('fecha_de');
        // $fecha_a = $request->get('fecha_a');

        $anio = date('Y');

        // if ( $fecha_de =='' && $fecha_a =='') {

            // $ventas=DB::table('ventas as v')
            // ->select(DB::raw('MONTH(v.fecha) as mes'),
            // DB::raw('YEAR(v.fecha) as anio'),
            // DB::raw('COUNT(v.id) as cantidad'),
            // DB::raw('SUM(v.valor) as total'))
            // ->whereYear('v.fecha',$anio)
            // ->groupBy(DB::raw('MONTH(v.fecha)'),DB::raw('YEAR(v.fecha)'))
            // ->get();


            
            // }

        try {
            
            $ventas = Venta::selectRaw('MONTH(fecha) as mes, YEAR(fecha) as anio
                ,COUNT(id) as cantidad, SUM(valor) as total')
                ->whereYear('fecha',$anio)
                ->groupBy(DB::raw('MONTH(fecha)'),DB::raw('YEAR(fecha)'))
                ->get();
    
            // $count = 0;
            // foreach ($ventas as $venta) {
            //     $count += 1;
            // }

            $count = $ventas->count();
    
            $pdf = \PDF::loadView('admin.pdf.informeventas',['ventas'=>$ventas, 'count'=>$count])
                ->setPaper('a4', 'landscape');

            return $pdf->download('ventas.pdf');

        } catch (\Exception $e) {
            //throw $th;
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

        // $ventas=DB::table('ventas')
        // ->join('producto_venta', 'ventas.id', '=', 'producto_venta.venta_id')
        // ->join('facturas', 'ventas.factura_id', '=', 'facturas.id')
        // ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
        // ->join('users', 'clientes.user_id', '=', 'users.id')
        // ->select('ventas.*','users.nombres', 'clientes.id as cliente', 'facturas.prefijo', 'facturas.consecutivo',
        // DB::raw('SUM(producto_venta.cantidad) as cantidad'))
        // ->whereMonth('ventas.fecha',$mes)
        // ->whereBetween('ventas.fecha',[$fecha_de, $fecha_a])
        // ->groupBy('ventas.id')
        // ->orderBy('ventas.created_at', 'DESC')
        // ->paginate(5); //obtener ventas en el mes seleccionado

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
            //throw $th;
        }
        
    }



    public function pdfVentaShow(Request $request)
    {
        $mes = date('m', strtotime($request->mes));
        $anio = date('Y');

        // $ventas=DB::table('ventas')
        // ->join('producto_venta', 'ventas.id', '=', 'producto_venta.venta_id')
        // ->join('facturas', 'ventas.factura_id', '=', 'facturas.id')
        // ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
        // ->join('users', 'clientes.user_id', '=', 'users.id')
        // ->select('ventas.*','users.nombres', 'clientes.id as cliente', 'facturas.prefijo', 'facturas.consecutivo',
        // DB::raw('SUM(producto_venta.cantidad) as cantidad'))
        // ->whereMonth('ventas.fecha',$mes)
        // ->groupBy('ventas.id')
        // ->get();

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
    
    
            // $count = 0;
            // foreach ($ventas as $venta) {
            //     // $count = $count + 1;
            //     $count += 1;
            // }

            $count = $ventas->count();
    
            $pdf = \PDF::loadView('admin.pdf.informeventashow',['ventas'=>$ventas, 'count'=>$count])
            ->setPaper('a4', 'landscape');
            
            return $pdf->download('ventas_mes.pdf');
            
        } catch (\Exception $e) {
            //throw $th;
        }

    }



    public function ventaProductos(Request $request)
    {
       
        $busqueda = $request->busqueda;

        // $productos = DB::table('productos')
        // ->orWhere('productos.nombre','like',"%$busqueda%")
        // ->orWhere('colores.nombre','like',"%$busqueda%")
        // ->orWhere('tallas.nombre','like',"%$busqueda%")
        // ->join('color_producto', 'productos.id', '=', 'color_producto.producto_id')
        // ->join('colores', 'color_producto.color_id', '=', 'colores.id')
        // ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
        // ->join('tallas', 'tallas.id', '=', 'producto_referencia.talla_id')
        // ->join('producto_venta', 'producto_referencia.id', '=', 'producto_venta.producto_referencia_id')
        // ->select('color_producto.id as cop', 'productos.id as codigo', 'productos.nombre', 'colores.nombre as color',
        // 'tallas.nombre as talla', DB::raw('SUM(producto_venta.cantidad) as cantidad')
        // )->groupBy('producto_referencia.id')
        // ->orderBy('cantidad', 'DESC')
        // ->paginate(5); //informe de productos más vendidos

        // when($buscar, function ($query) use ($buscar, $criterio) {
        //     return $query->where('users.'.$criterio, 'like', '%'. $buscar . '%');
        // })

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
            //throw $th;
        }

    }



    public function pdfInformeProductos(Request $request)
    {

        // $productos = DB::table('productos')
        // ->join('color_producto', 'productos.id', '=', 'color_producto.producto_id')
        // ->join('colores', 'color_producto.color_id', '=', 'colores.id')
        // ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
        // ->join('tallas', 'tallas.id', '=', 'producto_referencia.talla_id')
        // ->join('producto_venta', 'producto_referencia.id', '=', 'producto_venta.producto_referencia_id')
        // ->select('color_producto.id as cop','productos.id as codigo','productos.nombre','colores.nombre as color',
        // 'tallas.nombre as talla',DB::raw('SUM(producto_venta.cantidad) as cantidad')
        // )->groupBy('producto_referencia.id')
        // ->orderBy('cantidad', 'DESC')
        // ->get();

        try {
           
            $productos = ProductoVenta::with('productoReferencia')
            ->select('producto_referencia_id', DB::raw('SUM(cantidad) as cantidad'))
            ->groupBy('producto_referencia_id')
            ->orderBy('cantidad', 'DESC')
            ->get();
            
            // $count = 0;
            // foreach ($productos as $producto) {
            //     // $count = $count + 1;
            //     $count += 1;
            // }
    
            $count = $productos->count();

            $pdf = \PDF::loadView('admin.pdf.informeproductos',['productos'=>$productos, 'count'=>$count])
            ->setPaper('a4', 'landscape');
    
            return $pdf->download('productos.pdf');

        } catch (\Exception $e) {
            //throw $th;
        }

    }
    


    public function informeClientes(Request $request)
    {
        $busqueda = $request->busqueda;

        // $clientes = DB::table('clientes')
        // ->orWhere('users.id','like',"%$busqueda%")
        // ->orWhere('users.nombres','like',"%$busqueda%")
        // ->orWhere('users.apellidos','like',"%$busqueda%")
        // ->orWhere('users.telefono','like',"%$busqueda%")
        // ->orWhere('users.email','like',"%$busqueda%")
        // ->join('ventas', 'clientes.id', '=', 'ventas.cliente_id')
        // ->join('users', 'clientes.user_id', '=', 'users.id')
        // ->select('users.id as user','users.nombres', 'users.apellidos', 'users.telefono', 'users.email',
        // 'clientes.id as id_cliente',
        // DB::raw('COUNT(ventas.id) as cantidad'))
        // ->groupBy('ventas.cliente_id')
        // ->orderBy('cantidad', 'DESC')
        // ->paginate(5);

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
            //throw $th;
        }


    }



    public function pdfInformeClientes(Request $request)
    {
        // $clientes = DB::table('clientes')
        // ->join('ventas', 'clientes.id', '=', 'ventas.cliente_id')
        // ->join('users', 'clientes.user_id', '=', 'users.id')
        // ->select('users.id as user','users.nombres', 'users.telefono', 'users.email',
        // 'clientes.id as id_cliente',
        // DB::raw('COUNT(ventas.id) as cantidad'))
        // ->groupBy('ventas.cliente_id')
        // ->orderBy('cantidad', 'DESC')
        // ->get();

        try {

            $clientes = Venta::with('cliente.user')
            ->select('cliente_id', DB::raw('COUNT(id) as cantidad'))
            ->groupBy('cliente_id')
            ->orderBy('cantidad', 'DESC')
            ->get();
    
            // $count = 0;
            // foreach ($clientes as $cliente) {
            //     // $count = $count + 1;
            //     $count += 1;
            // }

            $count = $clientes->count();
    
            $pdf = \PDF::loadView('admin.pdf.informeclientes',['clientes'=>$clientes, 'count'=>$count])
            ->setPaper('a4', 'landscape');
            return $pdf->download('clientes.pdf');
           
        } catch (\Exception $e) {
            //throw $th;
        }

    }



    public function informePagos(Request $request)
    {
        $anio = date('Y');

        // $pagos=DB::table('pagos as p')
        // ->select(DB::raw('MONTH(p.fecha) as mes'),
        // DB::raw('YEAR(p.fecha) as anio'),
        // DB::raw('COUNT(p.id) as cantidad'),
        // DB::raw('SUM(p.monto) as total'))
        // ->whereYear('p.fecha',$anio)
        // ->groupBy(DB::raw('MONTH(p.fecha)'),DB::raw('YEAR(p.fecha)'))
        // ->paginate(5);

        try {
           
            $pagos = Pago::selectRaw('MONTH(fecha) as mes, YEAR(fecha) as anio,
            COUNT(id) as cantidad, SUM(monto) as total')
            ->whereYear('fecha',$anio)
            ->groupBy(DB::raw('MONTH(fecha)'),DB::raw('YEAR(fecha)'))
            ->paginate(5);
    
            return view('admin.informes.pagos.index',compact('pagos'));

        } catch (\Exception $e) {
            //throw $th;
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
            
            // $count = 0;
            // foreach ($pagos as $pago) {
            //     $count += 1;
            // }

            $count = $pagos->count();
    
            $pdf = \PDF::loadView('admin.pdf.informepagos',['pagos'=>$pagos, 'count'=>$count])
            ->setPaper('a4', 'landscape');
    
            return $pdf->download('pagos.pdf');
           
        } catch (\Exception $e) {
            //throw $th;
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

        // $pagos=DB::table('pagos')
        // ->join('ventas', 'pagos.venta_id', '=', 'ventas.id')
        // ->select('pagos.*')
        // ->whereMonth('pagos.fecha',$mes)
        // ->whereBetween('pagos.fecha',[$fecha_de, $fecha_a])
        // ->groupBy('pagos.id')
        // ->orderBy('pagos.created_at', 'DESC')
        // ->paginate(5);

        try {

            $pagos = Pago::whereMonth('fecha',$mes)
            ->whereBetween('fecha',[$fecha_de, $fecha_a])
            ->groupBy('id')
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
    
            return view('admin.informes.pagos.show',compact('pagos'));
           
        } catch (\Exception $e) {
            //throw $th;
        }

    }



    public function pdfPagosShow(Request $request)
    {
        //informe de pagos en el mes

        $mes = date('m', strtotime($request->mes));

        try {
           
            $pagos = Pago::whereMonth('fecha',$mes)
            ->orderBy('created_at', 'DESC')
            ->get();
            
            // $count = 0;
            // foreach ($pagos as $pago) {
            //     $count += 1;
            // }
    
            $count = $pagos->count();

            $pdf = \PDF::loadView('admin.pdf.informepagosmes',['pagos'=>$pagos, 'count'=>$count])
            ->setPaper('a4', 'landscape');
    
            return $pdf->download('pagos_mes.pdf');


        } catch (\Exception $e) {
            //throw $th;
        }

    }



    public function informe_saldos_clientes()
    {

        try {
            
            $saldos_pendientes = Venta::with('cliente')
            ->where('saldo', '>', '0')
            ->where('estado', '=', '2')
            ->select('cliente_id', DB::raw('COUNT(id) as facturas'),
            DB::raw('SUM(saldo) as saldos'))
            ->groupBy('cliente_id')
            ->paginate(5);
    
            return view('admin.informes.saldos.index',compact('saldos_pendientes'));

        } catch (\Exception $e) {
            //throw $th;
        }
        
    }



    public function informeSaldosClientesPdf()
    {

        try {
            
            $saldos_pendientes = Venta::with('cliente')
            ->where('saldo', '>', '0')
            ->where('estado', '=', '2')
            ->select('cliente_id', DB::raw('COUNT(id) as facturas'),
            DB::raw('SUM(saldo) as saldos'))
            ->groupBy('cliente_id')
            ->get();
    
            // $count = 0;
            // foreach ($saldos_pendientes as $saldos_pendiente) {
            //     $count += 1;
            // }
            $count = $saldos_pendientes->count();
    
            $pdf = \PDF::loadView('admin.pdf.informesaldos',['saldos_pendientes'=>$saldos_pendientes,
             'count'=>$count])->setPaper('a4', 'landscape');

            return $pdf->download('saldosclientes.pdf');

        } catch (\Exception $e) {
            //throw $th;
        }

    }



    public function facturasPendientesCliente(Cliente $cliente)
    {

        try {
          
            $saldos_pendientes = Venta::with('cliente')
            ->where('saldo', '>', '0')
            ->where('estado', '=', '2')
            ->where('cliente_id', $cliente->id)
            ->orderBy('fecha', 'DESC')
            ->paginate(5);
    
            return view('admin.informes.saldos.show',compact('saldos_pendientes'));

        } catch (\Exception $e) {
            //throw $th;
        }

    }
    

}
