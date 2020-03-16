<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $guarded = [];
    
    public function users(){
        return $this->belongsTo(User::class, 'user_status');
    }

    public function posts(){
        return $this->hasMany(JscPosts::class, 'post_author');
    }
    
}
