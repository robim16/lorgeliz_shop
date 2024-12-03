<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['from_id','to_id','mensaje','fecha'];

    public function user()
    {
        // $this->belongsTo(User::class);
        return $this->belongsTo(User::class,'from_id');
    }
}
