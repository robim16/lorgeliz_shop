<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Color extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'slug'];

    protected $table = 'colores';

    public static function boot () {
        parent::boot();
        
        static::creating(function(Color $color) {
          
            try {
              
                $slug = \Str::slug($color->nombre);
                
                $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
                
                $color->slug = $count ? "{$slug}-{$count}" : $slug;

            } catch (\Exception $e) {
               Log::debug('Error creando el slug del color.Error: '.json_encode($e));
            }
          
        });

    }

  

    public function productos()
    {
        return $this->belongsToMany(Producto::class);
    }
    
}
