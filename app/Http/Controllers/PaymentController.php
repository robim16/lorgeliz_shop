<?php

namespace App\Http\Controllers;

use App\Pago;
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

    public function response()
    {
        return view('epayco.response');
    }

}
