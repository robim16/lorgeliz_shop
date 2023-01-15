<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Pago;
use App\User;
use App\Notifications\NotificationPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
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
        $busqueda = $request->get('busqueda');

        try {
            
            $pagos = Pago::orWhere('pagos.venta_id','like',"%$busqueda%")
            ->orWhere('pagos.estado','like',"%$busqueda%")
            ->orWhere('pagos.id', $busqueda)
            ->orWhere('pagos.monto', $busqueda)
            ->orderBy('pagos.fecha', 'DESC')
            ->paginate(5);
    
            return view('admin.pagos.index', compact('pagos'));

        } catch (\Exception $e) {
            Log::debug('Error consultando los pagos.Error: '.json_encode($e));
        }
        
    }


    public function store($x_ref_payco, $total, $venta_id, $x_cod_response)
    {

        try {

            $pago = new Pago();
            $pago->ref_epayco = $x_ref_payco;
            $pago->fecha = \Carbon\Carbon::now();
            $pago->monto = $total;
            $pago->venta_id = $venta_id;
            $pago->estado =  $x_cod_response;
    
            $pago->save();//guardar el pago
            
        } catch (\Exception $e) {
            Log::debug('Error guardando el pago.Error: '.json_encode($e));
        }

    }


    public function printPay(Request $request, $id)
    {

        try {
           
            $pago = Pago::where('pagos.id', $id)
            ->orderBy('pagos.fecha', 'DESC')
            ->first();
    
            $pdf = \PDF::loadView('admin.pdf.pago',['pago'=>$pago])
            ->setPaper('a4', 'landscape');
            
            return $pdf->download('pago-'.$pago->id.'.pdf');

        } catch (\Exception $e) {
            Log::debug('Error imprimiendo el pago.Error: '.json_encode($e));
        }

    }


    public function pdfPagosReporte()
    {

        try {
            
            $pagos = Pago::orderBy('pagos.fecha')
            ->get();
    
            // $count = 0;
            // foreach ($pagos as $pago) {
            //     // $count = $count + 1;
            //     $count += 1;
            // }

            $count = $pagos->count();
    
            $pdf = \PDF::loadView('admin.pdf.listadopagos',['pagos'=>$pagos, 'count'=>$count])
            ->setPaper('a4', 'landscape');
            
            return $pdf->download('listadopagos.pdf');

        } catch (\Exception $e) {
            Log::debug('Error imprimiendo el reporte de pagos.Error: '.json_encode($e));
        }

    }



    public function anular(Pago $pago)
    {

        try {
          
            $monto = $pago->monto;
    
            $venta = $pago->venta;
    
            $saldo_venta = $venta->saldo;
    
            $venta->saldo = $saldo_venta + $monto;
    
            $venta->estado = 2;
            
            $venta->save();
    
            $pago->estado = 5;
    
            $pago->save();
    
            session()->flash('message', ['success', ("Se ha anulado el pago exitosamente")]);
    
            return back();

        } catch (\Exception $e) {
            Log::debug('Error anulando el pago.Error: '.json_encode($e));
        }
        
    }
}
