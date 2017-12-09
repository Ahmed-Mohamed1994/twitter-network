<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mention extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function tweet(){
        return $this->belongsTo('App\Tweet');
    }

    public function comment(){
        return $this->belongsTo('App\Comment');
    }
}
