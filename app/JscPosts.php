<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JscPosts extends Model
{
    protected $guarded = [];

    protected $dates = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function media(){
        return $this->belongsTo(JscMedia::class, 'post_media_id');
    }

    public function posts_meta(){
        return $this->hasMany(JscPostsMeta::class, 'post_id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'id');
    }

    public function user_role(){
        return $this->belongsTo(UserRoles::class, 'id');
    }

}
