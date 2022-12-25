<?php

namespace App\Http\Controllers\Admin;

use App\Cliente;
use App\Pedido;
use App\ProductoVenta;
use App\Mail\OrderStatusMail;
use App\Notifications\NotificationClient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Log;

class OrdersController extends Controller
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

        $tipo = $request->get('tipo');


        try {
            
            $pedidos = Pedido::whereHas('venta',function (Builder $query) use ($keyword) {
                $query->where('estado', '!=', '3');
                // ->where('ventas.valor','like',"%$keyword%");
            })
            // ->orWhere('fecha','like',"%$keyword%")
            // ->orWhere('id','like',"%$keyword%")
            ->buscar($tipo, $keyword)
            ->with('venta.cliente.user')
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
    
    
            $estados = $this->estados_pedido();
    
            return view('admin.pedidos.index', compact('pedidos', 'estados'));

        } catch (\Exception $e) {
            Log::debug('Error listando pedidos. error: '.json_encode($e));

            session()->flash('message', ['warning', ("ha ocurrido un error")]);
        }

    }



    public function show($id)
    {

        try {
           
            $productos = $this->productosOrder($id);

            return view('admin.pedidos.show',compact('productos'));

        } catch (\Exception $e) {
            return $e;
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        try {
           
            $pedido = Pedido::where('id', $request->pedido_id)->firstOrFail();
    
            $pedido->estado = $request->estado;
    
            $pedido->save(); // se actualiza el estado
    
            $details = [
                'cliente' => $pedido->venta->cliente->user->nombres,
                'fecha' => date('d/m/Y', strtotime($pedido->fecha)),
                'estado' => $pedido->estado,
                'url' => url('/pedidos/'. $pedido->id),
            ];

    
            if ($pedido->estado == 2) {
               $mensaje = 'Tu pedido está siendo preparado';
            }

            if ($pedido->estado == 3) {
                $mensaje = 'Tu pedido está siendo enviado';
            }

            if ($pedido->estado == 4) {
                $mensaje = 'Tu pedido ha sido entregado';
            }
    
            
            $arrayData = [
                'notificacion' => [
                    'msj' => $mensaje,
                    'url' => url('/pedidos/'. $pedido->id)
                ]
            ];
    

            Cliente::findOrFail($pedido->venta->cliente->id)->notify(new NotificationClient($arrayData));
    
            Mail::to($pedido->venta->cliente->user->email)->send(new OrderStatusMail($details));
    
            session()->flash('message', ['success', ("Se ha actualizado el estado del pedido")]);
    
            return back();


        } catch (\Exception $e) {

            session()->flash('message', ['warning', ("ha ocurrido un error")]);

            Log::debug('Error actualizando el pedido. Pedido: '.json_encode($pedido).'Error: '.
                json_encode($e)
            );

            return redirect()->back();
        }
        
    }



    public function imprimirPedido(Request $request, $id)
    {   

        try {

            $productos = $this->productosOrder($id);
    
            $pdf = \PDF::loadView('admin.pdf.pedido',['productos'=>$productos])
            ->setPaper('a4', 'landscape');
            
            return $pdf->download('pedido-'.$productos[0]->venta->pedido->id.'.pdf'); //imprimir pedido en pdf
     
        } catch (\Exception $e) {

            Log::debug('Error imprimiendo el pedido en orders admin. Error: '.json_encode($e));
        }
    }



    public function reportePedidosPdf()
    {

        try {
           
            $pedidos = Pedido::whereHas('venta',function (Builder $query) {
                $query->where('estado', '!=', '3');
            })
            ->with('venta.cliente.user')
            ->orderBy('created_at', 'DESC')
            ->get();
    
            $count = $pedidos->count();
    
            $pdf = \PDF::loadView('admin.pdf.listadopedidos',['pedidos'=>$pedidos, 'count'=>$count])
                ->setPaper('a4', 'landscape');
            
            return $pdf->download('listadopedidos.pdf'); //listado de pedidos en pdf


        } catch (\Exception $e) {

            session()->flash('message', ['warning', ("ha ocurrido un error")]);

            Log::debug('Error imprimiendo los pedidos. Pedido: '.json_encode($pedidos).'Error: '.
                json_encode($e)
            );
        }
    }



    public function productosOrder($id) //esta función se reutiliza
    {
        
        try {

            return ProductoVenta::whereHas('venta.pedido',
                function (Builder $query) use ($id) {
                $query->where('id', $id);
                })
                ->with(['venta.pedido', 'venta.cliente.user'])
                ->get();
            
        } catch (\Exception $e) {

            Log::debug('Error la función productosOrder. Error: '.json_encode($e));
        }
    }

   
    public function estados_pedido()
    {
        return [
            1,
            2,
            3,
            4
        ];
    }

}
