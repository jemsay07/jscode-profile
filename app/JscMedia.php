<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JscMedia extends Model
{
    protected $guarded = [];

    public function post(){
        return $this->hasMany(JscPosts::class);
    }
}
