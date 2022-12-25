<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;

class ColorProducto extends Pivot implements Auditable
{
    //protected $with = ['producto'];

    use \OwenIt\Auditing\Auditable;

    protected $table = 'color_producto';
    protected $fillable = [
        'producto_id', 
        'color_id',
        'visitas',
        'slug',
        'slider_principal'
    ];

    public static function boot () {
        parent::boot();
        
        static::creating(function(ColorProducto $colorproducto) {

            try {
               
                $nombre = request()->nombre;
    
                $id = request()->color;
    
                $color = Color::where('id', $id)->first();
                
                $slug = \Str::slug($nombre);
                
                //$count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
                
                $colorproducto->slug = "{$slug}-{$color['nombre']}";

            } catch (\Exception $e) {
                
                Log::debug('Error creando el slug del color_producto.Error: '.json_encode($e));
            }
          
        });

    }

    public $timestamps = false;

    public function producto () {
        return $this->belongsTo(Producto::class);
    }

    public function color (){
        return $this->belongsTo(Color::class);
    }

    //public function productoReferencias (){
        //return $this->hasMany(ProductoReferencia::class);
    //}

    public function imagenes (){
        return $this->morphMany('App\Imagene','imageable');
    }

    public function tallas (){
        return $this->belongsToMany(Talla::class, 'producto_referencia');
    }

    public function scopeActivo($query){
        return $query->where('activo', 'Si');
    }

    public function scopeVisitas($query){
        return $query->where('visitas', '>', '0');
    }
}
