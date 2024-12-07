<?php

namespace App\Http\Controllers;


// use App\CarritoProducto;
// use App\Carrito;
// use App\Events\ProductoAgotado;
// use App\Events\SalesEvent;
// use App\Factura;
// use App\Jobs\SendVentaMail;
// use App\ProductoReferencia;
// use App\User;
// use App\Venta;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\ClienteMessageMail;
// use App\Mail\AdminVentaMail;
// use App\Notifications\NotificationAdmin;
use App\Services\VentaService;

class VentaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('epaycoConfirm');
    }
    

    public function store(Request $request)
    {
        try {

            $invoice = $request->invoice;
            $venta_service =  new VentaService();
            $response = $venta_service->completar_venta($request, $invoice);

            return $response;

        } catch (\Exception $e) {
            return $e;
        }
    }

}
