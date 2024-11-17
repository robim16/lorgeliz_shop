<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkout(Request $request, $invoice)
    {
        try {
         
            $response = Http::get('https://secure.epayco.co/validation/v1/reference/'.$request->ref_payco);
            $transaction =  $response["data"];
    
            $x_ref_payco = $transaction["x_ref_payco"];
            $x_cod_response = $transaction["x_cod_response"];
            $x_amount = $transaction["x_amount"];

            $request = new Request([
                'x_ref_payco'   => $x_ref_payco,
                'x_cod_response'   => $x_cod_response,
                'x_amount'   => $x_amount,
                'invoice'   => $invoice,
            ]);
    
            $venta =  new VentaController();
            $venta->store($request);
        
            return view('epayco.response', compact('transaction'));
        } catch (\Throwable $th) {
            // throw $th;
            return $th;
        }
    }
}
