<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Categoria extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'slug'];

    public static function boot () {
        parent::boot();
        
        static::creating(function(Categoria $categoria) {
          
            try {
              
                $slug = \Str::slug($categoria->nombre);
                
                $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
                
                $categoria->slug = $count ? "{$slug}-{$count}" : $slug;

            } catch (\Exception $e) {
                
                Log::debug('Error crando el slug de la categoría.Error: '.json_encode($e));
            }
          
        });

    }

    public function subcategorias (){
        return $this->hasMany(Subcategoria::class);
    }

   // public function productos (){
       //return $this->hasMany(Producto::class);
    //}

}
