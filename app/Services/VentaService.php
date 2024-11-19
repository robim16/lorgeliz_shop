<?php

namespace App\Services;

use App\Carrito;
use App\Http\Controllers\Admin\PaymentController;
use App\Jobs\SendVentaMail;
use App\Mail\AdminVentaMail;
use App\Notifications\NotificationAdmin;
use App\ProductoReferencia;
use App\User;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class VentaService 
{

    public function completar_venta(Request $request, $invoice)
    {
        try {

            $x_ref_payco = ($request->x_ref_payco) ? $request->x_ref_payco : 0; // si no viene la ref. se pone 0
            $x_cod_response = ($request->x_cod_response) ? $request->x_cod_response : 3;
            $x_amount = ($request->x_amount) ? $request->x_amount : 0;
        
            DB::beginTransaction();
        
            if ($x_cod_response == 1 || $x_cod_response == 3) { 
                // si la transacción es aceptada o está pendiente

            
                $car = Carrito::where('cliente_id', auth()->user()->cliente->id)
                    ->where('estado', 1)
                    ->firstOrFail();// se busca el carrito del cliente
    
    
                $venta = new Venta();
                $venta->fecha = \Carbon\Carbon::now();
                $venta->factura_id = $invoice;
                $venta->subtotal =  $car->subtotal;
                $venta->envio =  $car->envio;
                $venta->valor =  $car->total;
                $venta->cliente_id = auth()->user()->cliente->id;
                $venta->saldo = $car->total - $x_amount;
                // si el pago no fue por epayco o está pendiente, la venta queda con saldo


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


                // SendVentaMail::dispatch($details, $admin);

                User::findOrFail($admin->id)->notify(new NotificationAdmin($arrayData));

                DB::commit();

                $products = array();

                $products['data'] = array();

                $stocks = ProductoReferencia::select('color_producto_id as id',
                    DB::raw('SUM(stock) as stock'))
                    ->groupBy('color_producto_id')
                    ->get();
                
                
                $agotados = $stocks->filter(function ($value) {
                    return $value->stock == 0;
                });
                
                // broadcast(new ProductoAgotado($agotados));

                // broadcast(new SalesEvent());


                if ($request->x_ref_payco == 0) {
                   
                    $response = ['data' => 'success', 'pedido' => $venta->pedido->id];
    
                    return response()->json($response);
                }

                return $venta;
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return $e;
        }
    }

}




