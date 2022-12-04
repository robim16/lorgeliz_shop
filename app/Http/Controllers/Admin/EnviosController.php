<?php

namespace App\Http\Controllers\Admin;

use App\Cliente;
use App\Envio;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnviosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $envios = Envio::with('venta.cliente.user:id,nombres,apellidos')
            ->orderBy('id')->get();

        return view('admin.envios.index', compact('envios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Cliente::with('user')
        ->whereHas('ventas')
        // ->orderBy('nombres')
        // ->orderBy('apellidos')
        ->get();

        return view('admin.envios.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $envio = Envio::create($request->except('cliente_id'));

        session()->flash('message', ['success', ("Se registrado la guía de envío exitosamente")]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
