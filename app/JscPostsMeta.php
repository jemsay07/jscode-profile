<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JscPostsMeta extends Model
{
    protected $guarded = [];

    public function posts(){
        return $this->belongsTo(JscPosts::class);
    }

}
