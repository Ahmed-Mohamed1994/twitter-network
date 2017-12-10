<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class User extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    use Searchable;

    public function follows(){
        return $this->hasMany('App\Follow');
    }

    public function tweets(){
        return $this->hasMany('App\Tweet');
    }

    public function likes(){
        return $this->hasMany('App\Like');
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }

    public function mentions(){
        return $this->hasMany('App\mention');
    }

    public function searchableAs()
    {
        return 'username';
    }
}
