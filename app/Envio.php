<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $fillable = ['costo', 'guia', 'direccion', 'municipio',
     'departamento', 'transportadora','comentarios', 'fecha', 'venta_id'
    ];

    public $timestamps = false;

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }
    
}
