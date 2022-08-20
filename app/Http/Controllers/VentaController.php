<?php

namespace App\Http\Controllers;


use App\CarritoProducto;
use App\Carrito;
use App\Events\ProductoAgotado;
// use App\Events\SalesEvent;
use App\Factura;
use App\Pago;
use App\Pedido;
use App\Producto;
use App\ProductoReferencia;
use App\ProductoVenta;
use App\User;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClienteMessageMail;
use App\Mail\AdminVentaMail;
use App\Notifications\NotificationAdmin;


class VentaController extends Controller
{
    public $x_ref_payco;
    public $x_cod_response;
    public $x_amount;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('epaycoConfirm');
    }

    public function epayco_register(Request $request)
    {
        //dd($request);
        $p_cust_id_cliente = '71480';
        $p_key = '03311b932a61ca0805ee7f7d5ca7f0dd7faad74c';
        $this->x_ref_payco = $request->x_ref_payco;
        $x_transaction_id = $request->x_transaction_id;
        $this->x_amount = $request->x_amount;
        $x_currency_code = $request->x_currency_code;
        $x_signature = $request->x_signature;
        //$signature = hash('sha256', $p_cust_id_cliente. '^' . $p_key . '^' . $this->x_ref_payco . '^' . $x_transaction_id . '^' . $this->x_amount . '^' .$x_currency_code);
       
        $signature = hash('sha256',
                       $p_cust_id_cliente.'^'
                      .$p_key.'^'
                      .$this->x_ref_payco.'^'
                      .$x_transaction_id.'^'
                      .$this->x_amount.'^'
                      .$x_currency_code
                    );
        //Validamos la firma
        if ($x_signature == $signature) {
        //if($this->x_cod_response = $request->x_cod_response) {
        /*Si la firma esta bien podemos verificar los estado de la transacción*/
        //$this->x_cod_response = $request->x_cod_response;
        switch ((int) $this->x_cod_response) {
        case 1:
        # code transacción aceptada
            $this->store();
        break;
        case 2:
        # code transacción rechazada
        break;
        case 3:
        # code transacción pendiente
            $this->store();
        break;
        case 4:
        # code transacción fallida
        break;
        }
        } else {
        die("Firma no valida");
        }
                
    }
//esta función es para probar la confirmación por el método post
    public function epaycoConfirm(Request $request)
    {
        $p_cust_id_cliente = '71480';
        $p_key = '03311b932a61ca0805ee7f7d5ca7f0dd7faad74c';
        $this->x_ref_payco = $request->x_ref_payco;
        $x_transaction_id = $request->x_transaction_id;
        $this->x_amount = $request->x_amount;
        $x_currency_code = $request->x_currency_code;
        $x_signature = $request->x_signature;
        $signature = hash('sha256', $p_cust_id_cliente . '^' . $p_key . '^' . $this->x_ref_payco . '^' . $x_transaction_id . '^' . $this->x_amount . '^' . $x_currency_code);
        $x_response = $request->x_response;
        $x_motivo = $request->x_response_reason_text;
        $x_id_invoice = $request->x_id_invoice;
        $x_autorizacion = $request->x_approval_code;
        //Validamos la firma
        //if ($x_signature == $signature) {
        /*Si la firma esta bien podemos verificar los estado de la transacción*/
        if($this->x_cod_response = $request->x_cod_response){
        //$this->x_cod_response = $request->x_cod_response;
        switch ((int) $this->x_cod_response) {
        case 1:
        # code transacción aceptada
        $this->store();
        break;
        case 2:
        # code transacción rechazada
        //echo "transacción rechazada";
        break;
        case 3:
        # code transacción pendiente
        //echo "transacción pendiente";
        break;
        case 4:
        # code transacción fallida
        //echo "transacción fallida";
        break;
        }
        } else {
        die("Firma no valida");
        }
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return back();

        try {
            $x_ref_payco = ($this->x_ref_payco) ? $this->x_ref_payco : 0; // si no viene la ref. se pone 0
            $x_cod_response = ($this->x_cod_response) ? $this->x_cod_response : 3;
            $x_amount = ($this->x_amount) ? $this->x_amount : 0;
        
            DB::beginTransaction();
        
            if ($x_cod_response == 1 || $x_cod_response == 3) {// si la transacción es aceptada o está pendiente

                $facturas = Factura::all('id');
                $consecutivo = $facturas->last();// se obtiene le ultimo id de facturas
                
                $id = $consecutivo->id + 1;// $consecutivo++
    
                $factura = new Factura();
                $factura->consecutivo = $id;
    
                $factura->save();
    
                //$car = Carrito::where('id', $request->carrito)->firstOrFail();
                $car = Carrito::where('cliente_id', auth()->user()->cliente->id)
                ->where('estado', 1)
                ->firstOrFail(); // se busca el carrito del cliente
    
                //$car->estado = '0';
                //$car->save();
    
                $venta = new Venta();
                $venta->fecha = \Carbon\Carbon::now();
                $venta->factura_id = $factura->id;
                //$venta->valor = $request->total;
                $venta->valor =  $car->total;
                $venta->cliente_id = auth()->user()->cliente->id;
                $venta->saldo = $car->total - $x_amount; // si el pago no fue por epayco o está pendiente, la venta queda con saldo

                if ($x_cod_response == 1) {
                    $venta->estado = 1; // si el pago fue exitoso, la venta queda pagada
                    $venta->save();

                    $total = $car->total;
                    $venta_id = $venta->id;
                    $payment =  new PaymentController();
                    $payment->store($x_ref_payco, $total, $venta_id, $x_cod_response);
                }
                else{
                    $venta->estado = 2;
                    $venta->save();
                }

    
                $admin = User::where('role_id', 2)->first();
    
                $details = [
                    'title' => 'Se ha efectuado una nueva venta',
                    'user' => $admin->nombres,
                    'valor' => $venta->valor,
                    'url' => url('/admin/ventas/'. $venta->id),
                ];
                
                Mail::to($admin->email)->send(new AdminVentaMail($details));

                $numVentas = DB::table('ventas')->where('id', $venta->id)->count();

                $arrayData = [
                    'ventas' => [
                        'numero' => $numVentas,
                        'msj' => 'nueva venta',
                        'url' => url('/admin/ventas/'. $venta->id)
                    ]
                ];

                // foreach ($admin as $user) {
                //     User::findOrFail($user->id)->notify(new NotificationAdmin($arrayData));
                // }

                User::findOrFail($admin->id)->notify(new NotificationAdmin($arrayData));

                DB::commit();

                $products = array();
                $products['data'] = array();

                // $carritos = CarritoProducto::join('producto_referencia', 
                // 'carrito_producto.producto_referencia_id', 'producto_referencia.id')
                // ->where('carrito_id', $car->id)
                // ->get(); // con map(1)


                // $prods = ProductoVenta::join('producto_referencia', 'producto_venta.producto_referencia_id',
                // 'producto_referencia.id')
                // ->where('venta_id', $venta->id)
                // ->select('color_producto_id')
                // ->groupBy('color_producto_id')
                // ->get();(2)

            
                // $stocks = ProductoReferencia::whereIn('color_producto_id',$prods)
                // ->select('color_producto_id as productos', DB::raw('SUM(stock) as stock'))
                // ->groupBy('color_producto_id')
                // ->get();(2)

                $stocks = ProductoReferencia::select('color_producto_id as id',
                    DB::raw('SUM(stock) as stock'))
                    ->groupBy('color_producto_id')
                    ->get();
                
                
                $agotados = $stocks->filter(function ($value) {
                    return $value->stock == 0;
                });
                
                // broadcast(new ProductoAgotado($agotados));

                // broadcast(new SalesEvent());

                $response = ['data' => 'success', 'pedido' => $venta->pedido->id];
                return response()->json($response);//$response
            }

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

}
