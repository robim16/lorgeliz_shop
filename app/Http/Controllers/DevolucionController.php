<?php

namespace App\Http\Controllers;

Use App\Devolucione;
use App\Jobs\SendEmail;
Use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Mail\AdminDevolucionMail;

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
            
            $productos = Devolucione::whereHas('venta',
            function (Builder $query) {
               $query->where('cliente_id', auth()->user()->cliente->id);
            })
            ->with(['venta', 'productoReferencia'])
            ->paginate(5);
    
            return view('user.devoluciones.index',compact('productos'));

        } catch (\Exception $e) {
            //throw $th;
        }

    }


    
    public function show(Request $request, $id)
    {
        $busqueda = $request->busqueda;

        try {

            $productos = Devolucione::whereHas('venta',
            function (Builder $query) {
               $query->where('cliente_id', auth()->user()->cliente->id);
            })
            ->with(['venta', 'productoReferencia'])
            ->where('id', $id)
            ->paginate(5);
           
    
            return view('user.devoluciones.show',compact('productos'));
           
        } catch (\Exception $e) {
            //throw $th;
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
        
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

       
        try {


            $producto = $request->producto;

            $venta = $request->venta;

            $cantidad = $request->cantidad;
    
            $devoluciones = Devolucione::where('producto_referencia_id', $producto)//$ref
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
                    'cliente' => $user->nombres.' '.$user->apellidos,
                    'url' => url('/admin/devoluciones/'. $devolucion->id),
                ];
    
                
                SendEmail::dispatch($details, $admin);

                // Mail::to($admin->email)->send(new AdminDevolucionMail($details));
    
                // User::findOrFail($admin->id)->notify(new AdminDevolucionMail($details));
    
            } 
    
            $response = ['data' => $devoluciones];
            
            return response()->json($response);
         
        } catch (\Exception $e) {
            //throw $th;
        }
        
    }


    //implementado en rutas api/devolucion
    public function verificar(Request $request){

        try {
           
            if (!$request->ajax()) return redirect('/');
    
            return Devolucione::where('venta_id',$request->venta)
                ->where('producto_referencia_id',$request->producto)
                ->first();

                
        } catch (\Exception $e) {
            //throw $th;
        }
    }
}
