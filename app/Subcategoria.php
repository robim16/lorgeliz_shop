<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'slug'];

    public static function boot () {
        parent::boot();
        
        static::creating(function(Subcategoria $subcategoria) {

            try {
               
                $slug = \Str::slug($subcategoria->descripcion);
                
                $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
                
                $subcategoria->slug = $count ? "{$slug}-{$count}" : $slug;

            } catch (\Exception $e) {
                //throw $th;
            }
          
        });

    }
    
    public function categoria (){
        return $this->belongsTo(Categoria::class);
    }

    public function tipos (){
        return $this->hasMany(Tipo::class);
    }

}
