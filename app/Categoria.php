<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'slug'];

    public static function boot () {
        parent::boot();
        
        static::creating(function(Categoria $categoria) 
        {
          
          $slug = \Str::slug($categoria->nombre);
          
          $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
          
          $categoria->slug = $count ? "{$slug}-{$count}" : $slug;
          
        });

    }

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class);
    }

   // public function productos (){
       //return $this->hasMany(Producto::class);
    //}

}
