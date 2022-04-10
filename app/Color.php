<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'slug'];

    protected $table = 'colores';

    public static function boot () {
        parent::boot();
        
        static::creating(function(Color $color) {
          
          $slug = \Str::slug($color->nombre);
          
          $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
          
          $color->slug = $count ? "{$slug}-{$count}" : $slug;
          
        });

    }

    //public function colorProductos () {
        //return $this->hasMany(ColorProducto::class);
    //}

    public function productos () {
        return $this->belongsToMany(Producto::class);
    }
}
