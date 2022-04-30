<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Pago;
use App\User;
use App\Notifications\NotificationPay;
use Illuminate\Http\Request;


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
        
        $pagos = Pago::orWhere('pagos.venta_id','like',"%$busqueda%")
        ->orWhere('pagos.estado','like',"%$busqueda%")
        ->orWhere('pagos.id', $busqueda)
        ->orWhere('pagos.monto', $busqueda)
        ->orderBy('pagos.fecha', 'DESC')
        ->paginate(5);

        return view('admin.pagos.index', compact('pagos'));
    }

    public function store($x_ref_payco, $total, $venta_id, $x_cod_response)
    {
        $pago = new Pago();
        $pago->ref_epayco = $x_ref_payco;
        $pago->fecha = \Carbon\Carbon::now();
        $pago->monto = $total;
        $pago->venta_id = $venta_id;
        $pago->estado =  $x_cod_response;

        $pago->save();//guardar el pago

    }

    public function printPay(Request $request, $id)
    {
        $pago = Pago::where('pagos.id', $id)
        ->orderBy('pagos.fecha', 'DESC')
        ->first();

        $pdf = \PDF::loadView('admin.pdf.pago',['pago'=>$pago])
        ->setPaper('a4', 'landscape');
        
        return $pdf->download('pago-'.$pago->id.'.pdf');
    }

    public function pdfPagosReporte()
    {
        $pagos = Pago::orderBy('pagos.fecha')
        ->get();

        $count = 0;
        foreach ($pagos as $pago) {
            // $count = $count + 1;
            $count += 1;
        }

        $pdf = \PDF::loadView('admin.pdf.listadopagos',['pagos'=>$pagos, 'count'=>$count])
        ->setPaper('a4', 'landscape');
        
        return $pdf->download('listadopagos.pdf');
    }

    public function anular(Pago $pago)
    {
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
    }
}
