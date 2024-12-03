<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Cliente extends Model
{
    protected $fillable = ['user_id'];
    use Notifiable;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function carritos()
    {
        return $this->hasMany(Carrito::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
