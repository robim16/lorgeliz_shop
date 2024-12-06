<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Producto extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;
    
    const NUEVO = 1;
    const OFERTA = 2;
    

    //protected $with = ['tipo']; carga una relación cuando se recupera este modelo

    protected $fillable = ['nombre', 'tipo_id', 'marca', 'talla', 'precioanterior', 'precioactual', 'porcentajededescuento', 'descripcion_corta', 'descripcion_larga', 'especificaciones','slug'];

    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }

    //public function devoluciones (){
        //return $this->hasMany(Devolucione::class);
    //}

    //public function compras (){
        //return $this->belongsToMany(Compra::class);
    //}

    //public function ventas (){
        //return $this->belongsToMany(Venta::class);
    //}

    public function colors()
    {
        return $this->belongsToMany(Color::class)
            ->withPivot('id')
            ->using(ColorProducto::class);
    }

  
}
