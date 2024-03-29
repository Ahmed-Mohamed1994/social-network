<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;
    public function posts(){
        return $this->hasMany('App\Post');
    }

    public function likes(){
        return $this->hasMany('App\Like');
    }

    public function likes_comment(){
        return $this->hasMany('App\LikeComment');
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }
}
