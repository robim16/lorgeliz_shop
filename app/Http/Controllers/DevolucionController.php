<?php

namespace App\Http\Controllers;


use App\Devolucione;
use App\Jobs\SendEmail;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Mail\AdminDevolucionMail;
use App\Notifications\NotificationAdminNewDevolution;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class DevolucionController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $busqueda = $request->busqueda;


        try {

            $productos = Devolucione::whereHas(
                'venta',
                function (Builder $query) {
                    $query->where('cliente_id', auth()->user()->cliente->id);
                }
            )
                ->with([
                    'venta:id', 'venta.pedido:id,venta_id', 'productoReferencia:id,talla_id,color_producto_id',
                    'productoReferencia.colorProducto:id,color_id,slug,producto_id',
                    'productoReferencia.colorProducto.color:id,nombre', 'productoReferencia.talla:id,nombre',
                    'productoReferencia.colorProducto.producto:id,nombre',
                    'productoReferencia.colorProducto.imagenes' => function ($query) {
                        $query->select('id', 'url', 'imageable_id');
                    }
                ])
                ->paginate(5);

            return view('user.devoluciones.index', compact('productos'));
        } catch (\Exception $e) {
            Log::debug('Error consultando las devoluciones del cliente.Error: ' . json_encode($e));
        }
    }



    public function show(Request $request, $id)
    {
        $busqueda = $request->busqueda;


        try {

            $productos = Devolucione::whereHas(
                'venta',
                function (Builder $query) {
                    $query->where('cliente_id', auth()->user()->cliente->id);
                }
            )
                ->with(['venta', 'productoReferencia'])
                ->where('id', $id)
                ->paginate(5);


            return view('user.devoluciones.show', compact('productos'));
        } catch (\Exception $e) {
            Log::debug('Error consultando la devolución del cliente.Error: ' . json_encode($e));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //podría implementare en api

        if (!request()->ajax()) {
            abort(401, 'Acceso denegado');
        }

        try {

            $producto = $request->producto;
            $venta = $request->venta;
            $cantidad = $request->cantidad;

            $devoluciones = Devolucione::where('producto_referencia_id', $producto) //$ref
                ->where('venta_id', $venta)
                ->count(); // verificamos que no se haya solicitado la devolución anteriormente

            if ($devoluciones == 0) {
                $devolucion = new Devolucione();
                $devolucion->fecha = \Carbon\Carbon::now();
                $devolucion->cantidad = $cantidad;
                $devolucion->producto_referencia_id = $producto; //$ref
                $devolucion->venta_id = $venta;

                $devolucion->save();

                $admin = User::where('role_id', 2)->first();
                $user = auth()->user();

                // return $admin->nombres;



                $details = [
                    'title' => 'Se ha solicitado una nueva devolucion',
                    'user' => $admin->nombres,
                    'cliente' => $user->nombres . ' ' . $user->apellidos,
                    'url' => url('/admin/devoluciones/' . $devolucion->id),
                ];


                // Mail::to($admin->email)->send(new AdminDevolucionMail($details));

                SendEmail::dispatch($details, $admin);


                $mensaje = 'nueva devolución solicitada';

                //notificacion para el admin
                $arrayData = [
                    'notificacion' => [
                        'msj' => $mensaje,
                        'url' => url('/admin/devoluciones/' . $devolucion->id)
                    ]
                ];


                // User::findOrFail($admin->id)->notify(new NotificationAdminNewDevolution($arrayData));



            }

            $response = ['data' => $devoluciones];

            return response()->json($response);
        } catch (\Exception $e) {
            Log::debug('Error creando la devolución del cliente.Error: ' . json_encode($e));
        }
    }


    //implementado en rutas api/devolucion
    public function verificar(Request $request)
    {
        if (!request()->ajax()) {
            abort(401, 'Acceso denegado');
        }

        return Devolucione::where('venta_id', $request->venta)
            ->where('producto_referencia_id', $request->producto)
            ->first();
    }
}
